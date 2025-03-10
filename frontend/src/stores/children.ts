import { defineStore } from 'pinia';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { useAuthStore } from './auth';
import type { Child, ChildsForm } from '@/types';

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
        const response = await axios.get<Child>(`/cildren/${id}`, {
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

      try {
        const authStore = useAuthStore();
        const response = await axios.put<Child>(`.children/${id}`, childData, {
          headers: {
            Authorization: `Bearer ${authStore.token}`
          }
        });

        // 更新された子供の情報で配列を更新
        const index = this.children.findIndex(child => child.id === Number(id));
        if(index !== -1) {
          this.children[index] = response.data;
        }

        if(this.currentChild && this.currentChild.id === Number(id)) {
          this.currentChild = response.data;
        }

        return response;
      } catch(error) {
        const axiosError = error as AxiosError<ErrorResponse>;
        this.error = axiosError.response?.data?.message || 'データの更新に失敗しました';
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
        throw EvalError;
      } finally {
        this.loading = false;
      }
    }
  }
});

