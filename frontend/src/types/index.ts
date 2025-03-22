// ユーザ情報の型
export interface User {
  id: number;
  name: string;
  email: string;
  password: string;
  created_at: string;
  updated_at: string;
}

// 子供情報の型
export interface Child {
  id: number;
  user_id: number;
  name: string;
  birth_date: string;
  gender: 'male' | 'female' | 'other' | null;
  notes: string | null
  created_at: string;
  updated_at: string;
}

// 認証情報の型
export interface AuthResponse {
  user: User;
  token: string;
}

// ログインフォームの型
export interface LoginForm {
  email: string;
  password: string;
}

// 登録フォームの型
export interface RegisterForm {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

//プロフィール更新フォームの型
export interface ProfileUpdateForm {
  name: string;
  email: string;
}

export interface ChildsForm {
  name: string;
  birth_date: string;
  gender: 'male' | 'female' | 'other' | null;
  notes: string | null;
}