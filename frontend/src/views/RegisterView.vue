<template>
  <div>
    <h2>新規登録</h2>
    <div v-if="error">
      {{ error }}
    </div>

    <form @submit.prevent="handleRegister">
      <div>
        <label for="お名前">お名前</label>
        <input 
          id="name"
          type="text"
          v-model="form.name"
          required
          autocomplate="name"
        />
      </div>

      <div>
        <label for="email">メールアドレス</label>
        <input 
          id="email"
          type="text"
          v-model="form.email"
          required
          autocomplate="email"
        />
      </div>

      <div>
        <label for="password">パスワード</label>
        <input 
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplate="password"
        />
        <small>8文字以上で入力してください</small>
      </div>

      <div>
        <label for="password_confirmation">パスワード（確認）</label>
        <input 
          id="password_confirmation"
          type="password"
          v-model="form.password_confirmation"
          required
          autocomplate="password_confirmation"
        />
      </div>

      <div>
        <button type="submit" :disabled="loading">
          {{ loading ? '登録中...' : '登録する' }}
        </button>
      </div>
    </form>

    <div>
      <p>すでにアカウントをお持ちの方は<router-link to="/login">ログイン</router-link></p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { RegisterForm } from '@/types';

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const error = ref('');

// フォームの状態
const form = reactive<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

// 新規登録
const handleRegister = async () => {
  loading.value = true;
  error.value = '';

  try {
    await authStore.reggister(form);

    // 登録成功後、ホームページにリダイレクト
    router.push('/');
  } catch(err: any) {
    console.error('登録エラー:', err);
    error.value = err.response?.data?.message || '登録に失敗しました';
  } finally {
    loading.value = false;
  }
};
</script>