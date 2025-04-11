import { defineStore } from 'pinia';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { useAuthStore } from './auth';
import type { Child, ChildsForm, DailyRecord, GrowthRecord } from '@/types';

interface ChildrenState {
  children: Child[],
  currentChild: Child | null,
  loading: boolean,
  error: string | null,
}

interface ErrorResponse {
  message?: string;
}

export const useChildrenStore = defineStore('children', {
  state: (): ChildrenState => ({
    children: [],
    currentChild: null,
    loading: false,
    error: null,
  }),

  getters: {
    hasChildren: (state) => state.children.length > 0,
  },

  actions: {
    // 子供一覧を取得
    async fetchChildren(): Promise<AxiosResponse<Child[]>> {
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        const response = await axios.get<Child[]>('/children', {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });

        this.children = response.data || [];
        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの取得に失敗しました';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    //特定の子供情報を取得
    async fetchChild(id: number | string): Promise<AxiosResponse<Child>> {
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        const response = await axios.get<Child>(`/children/${id}`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        

        this.currentChild = response.data;
        return response;
      } catch(error) {
        const axiosError =  error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの取得に失敗しました';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 子供の情報を追加
    async addChild(childData: ChildsForm): Promise<AxiosResponse<Child>> {
      this.loading = true;
      this.error = null;

      try {
        const authSotore = useAuthStore();
        const response = await axios.post<Child>('/children', childData, {
          headers: {
            Authorization: `Bearer ${authSotore.token}`
          }
        });

        // 新しい子供情報を配列に追加
        this.children.push(response.data);

        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの追加に失敗しました';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 子供の情報を更新
    async updateChild(id: number | string, childData: ChildsForm): Promise<AxiosResponse<Child>> {
      this.loading = true;
      this.error = null;

      if (!id) {
        console.error('updateChild: IDが指定されていません');
        this.error = 'IDが指定されていません';
        this.loading = false;
        throw new Error('Missing required param "id"');
      }

      const idString = String(id);
      console.log(`updateChild: ID ${idString} で子供の情報を更新します`, childData);

      try {
        const authStore = useAuthStore();
        const response = await axios.put<Child>(`/children/${idString}`, childData, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });

        console.log(`updateChild: ID ${idString} の更新に成功しました`, response.data);

        // レスポンスデータが空の場合、childDataを使用
        const updatedData = Object.keys(response.data).length === 0 ? 
          { ...childData, id: Number(idString) } : response.data;

        // 更新された子供の情報で配列を更新
        const index = this.children.findIndex(child => child.id === Number(idString));
        if(index !== -1) {
          this.children[index] = { 
            ...this.children[index], 
            ...updatedData 
          };
        }

        if(this.currentChild && this.currentChild.id === Number(idString)) {
          this.currentChild = { 
            ...this.currentChild, 
            ...updatedData 
          };
        }

        // レスポンスデータが空の場合はマージしたデータを返す
        if(Object.keys(response.data).length === 0) {
          response.data = { 
            ...this.children.find(child => child.id === Number(idString)) 
          } as Child;
        }

        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの更新に失敗しました';
        console.error(`updateChild: ID ${idString} の更新に失敗しました`, error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 子供情報を削除
    async deleteChild(id: number | string): Promise<AxiosResponse<any>> {
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        const response = await axios.delete(`/children/${id}`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });

        // 削除された子供を配列から削除
        this.children = this.children.filter(child => child.id !== Number(id));

        if(this.currentChild && this.currentChild.id === Number(id)) {
          this.currentChild = null;
        }

        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの削除に失敗しました';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 成長記録を取得
    async fetchGrowthRecord(id: number | string): Promise<AxiosResponse<GrowthRecord[]>> {
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        const response = await axios.get<GrowthRecord[]>(`/children/${id}/growth-records`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの取得に失敗しました';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 子供の成長記録を作成する
    async createGrowthRecord(data: any) {
      this.loading = true;
      this.error = null;
      
      try {
        const authStore = useAuthStore();
        console.log('認証トークン:', authStore.token?.substring(0, 10) + '...');
        
        // トークンがない場合のチェック
        if (!authStore.token) {
          console.error('認証トークンがありません');
          this.error = '認証情報がありません。再ログインしてください。';
          throw new Error('認証トークンがありません');
        }
        
        const response = await axios.post('/growth-records', data, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        
        return response;
      } catch (error) {
        // エラー詳細をログに出力
        console.error('成長記録の作成に失敗しました:', error);
        if (axios.isAxiosError(error) && error.response) {
          console.error('エラーレスポンス詳細:', error.response.data);
          this.error = error.response.data.message || '成長記録の作成に失敗しました';
        } else {
          this.error = '成長記録の作成に失敗しました';
        }
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 成長記録を更新する
    async updateGrowthRecord(id: number, data: any): Promise<AxiosResponse<GrowthRecord>> {
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        console.log('認証トークン:', authStore.token?.substring(0, 10) + '...');
        
        const response = await axios.put<GrowthRecord>(`/growth-records/${id}`, data, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        return response;
      } catch (error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        console.error('APIエラー詳細:', axiosError.response?.data);
        this.error = axiosError.response?.data?.message || '記録の更新に失敗しました';
        console.error('記録の更新に失敗しました:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // 成長記録を削除する
    async deleteGrowthRecord(id: number) {
      try {
        const authStore = useAuthStore();
        const response = await axios.delete(`/growth-records/${id}`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        return response;
      } catch (error) {
        console.error('記録の削除に失敗しました:', error);
        throw error;
      }
    },

    // 子供の育児記録を取得
    async fetchDailyRecord(childId: string): Promise<AxiosResponse<DailyRecord[]>> {
      const authStore = useAuthStore();
      const response = await axios.get<DailyRecord[]>(`/children/${childId}/daily-records`, {
        headers: {
          Authorization: `Bearer ${authStore.token}`
        }
      });
      return response;
    },

    // 月ごとの育児記録を取得
    async fetchDailyRecordByMonth(childId: string, year: number, month: number): Promise<AxiosResponse<DailyRecord[]>> {
      const authStore = useAuthStore();
      const response = await axios.get<DailyRecord[]>(`/children/${childId}/daily-records`, {
        params: { year, month },
        headers: {
          Authorization: `Bearer ${authStore.token}`
        }
      });
      return response;
    },

    async createDailyRecord(childId: string, data: any) {
      try {
        const authStore = useAuthStore();
        // リクエストデータにchild_idを追加
        const requestData = {
          ...data,
          child_id: parseInt(childId) // 文字列から数値に変換
        };
        console.log('リクエストデータ:', requestData); // デバッグ用にリクエストデータを表示
        
        // URLを元に戻す
        const response = await axios.post(`/children/${childId}/daily-records`, requestData, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        return response;
      } catch (error) {
        console.error('育児記録の作成に失敗しました:', error);
        if (axios.isAxiosError(error) && error.response) {
          console.error('サーバーエラー詳細:', error.response.data);
          this.error = error.response.data.message || '育児記録の作成に失敗しました';
        } else {
          this.error = '育児記録の作成に失敗しました';
        }
        throw error;
      }
    },

    // 育児記録を削除
    async deleteDailyRecord(recordId: number) {
      try {
        const authStore = useAuthStore();
        const response = await axios.delete(`/daily-records/${recordId}`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });
        return response;
      } catch (error) {
        console.error('育児記録の削除に失敗しました:', error);
        this.error = '育児記録の削除に失敗しました';
        throw error;
      }
    }
  }
});

