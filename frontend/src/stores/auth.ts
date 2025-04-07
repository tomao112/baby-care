import { User, AuthResponse, LoginForm, RegisterForm, ProfileUpdateForm } from '@/types';
import { defineStore } from 'pinia';
import axios from 'axios';

interface AuthState {
  user: User | null;
  token: string | null;
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: localStorage.getItem('token') || null
  }),

  getters: {
    isLoggedIn: (state) => !!state.token
  },

  actions: {
    // ログインユーザーの情報を格納
    setUserInfo(userData: User) {
      this.user = userData;
    },
    // ユーザ登録
    async register(userData: RegisterForm) {
      try {
        const response = await axios.post<AuthResponse>('/register', userData);
        this.setAuth(response.data);
        return response;
      } catch(error) {
        throw error;
      }
    },

// ログイン
  async login(credentials: LoginForm) {
    try {
      console.log('ログイン試行:', credentials);
      const response = await axios.post('/login', credentials);
      console.log('ログインレスポンス:', response.data);
      
      // ログイン成功後、トークンとユーザー情報を保存
      this.setAuth(response.data);
      console.log('認証情報設定完了:', this.user, this.token);
      
      return response;
    } catch (error) {
      console.error('ログインエラー詳細:', error);
      throw error;
    }
  },

    // ログアウト
    async logout() {
      try {
        if(this.token) {
          await axios.post('/logout', {})
        }
      } catch(error) {
        console.log('ログアウト中にエラーが発生しました:', error);
      } finally {
        this.clearAuth();
      }
    },

    // 現在のユーザ情報取得
    async fetchUser() {
      try {
        if(!this.token) return null;

        const response = await axios.get<User>('/user')

        this.user = response.data;
        return response;
      } catch(error) {
        this.clearAuth();
        throw error;
      }
    },

    // 認証情報を設定
    setAuth(data: AuthResponse) {
      this.user = data.user;
      this.token = data.token;
      localStorage.setItem('token', data.token);

      // デフォルトのヘッダーにトークンを設定
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    },

    // 認証情報をクリア
    clearAuth() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');

      // ヘッダーからトークンと削除
      delete axios.defaults.headers.common['Authorization'];
    },

    // ユーザー情報更新
    async updateProfile(profileData: ProfileUpdateForm) {
      try {
        const response = await axios.put('/update', profileData);
        // ユーザー情報の更新
        this.user = response.data;

        return response.data;
      } catch(error) {
        console.error('プロフィール更新エラー：', error);
        throw error;
      }
    },
  }
})
