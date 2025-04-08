// ユーザ情報の型
export interface User {
  id: number;
  name: string;
  email: string;
  password: string;
  created_at: string;
  updated_at: string;
}

export interface Child {
  id: number;
  user_id: number;
  name: string;
  birth_date: string;
  gender: "male" | "female" | "other" | null;
  notes: string | null;
  created_at: string;
  updated_at: string;
  growth_records?: GrowthRecord[];
  daily_records?: DailyRecord[];
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

// 子供情報フォームの型
export interface ChildsForm {
  name: string;
  birth_date: string;
  gender: 'male' | 'female' | 'other' | null;
  notes: string | null;
}





export interface GrowthRecord {
  id: number;
  child_id: number;
  date: string;
  height?: number | null;
  weight?: number | null;
  head_circumference?: number | null;
  memo?: string | null;
  created_at: string;
  updated_at: string;
}

export interface DailyRecord {
  id: number;
  child_id: number;
  daily_event_stamps_id: number;
  record_date: string;
  record_time: string;
  comment: string;
  created_at: string;
  updated_at: string;
}

