import { User, AuthResponse, LoginForm, RegisterForm } from '@/types';
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
    isLoggedIn: (state) => !state.token
  },

  actions: {
    // ユーザ登録
    async reggister(userData: RegisterForm) {
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
        const response = await axios.post<AuthResponse>('/login', credentials);
        this.setAuth(response.data);
        return response;
      } catch(error) {
        throw error;
      }
    },

    // ログアウト
    async logout() {
      try {
        if(this.token) {
          await axios.post('/logout', {}, {
            headers: {
              Authorization: `Bearer ${this.token}`
            }
          })
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

        const response = await axios.get<User>('/user', {
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        })

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
    }
  }
})
