<template>
  <div>
    <h2>ログイン</h2>

    <div v-if="error">
      {{ error }}
    </div>

    <form @submit.prevent="handleLogin">
      <div>
        <label for="email">メールアドレス</label>
        <input 
          v-model="form.email" 
          type="email"
          id="email"
          required
          placeholder="メールアドレスを入力してください"
        >
      </div>

      <div>
        <label for="password">パスワード</label>
        <input 
          v-model="form.password"  
          type="password"
          id="password"
          required
          autocomplete="current-password">
      </div>

      <div>
        <button type="submit" :disabled="loading">
          {{ loading ? 'ログイン中...' : 'ログイン' }}
        </button>
      </div>
    </form>

    <div>
      <p>アカウントをお持ちでない方は<router-link to="/register">新規登録</router-link></p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { LoginForm } from '@/types';

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const error = ref('');

// フォームの状態
const form = reactive<LoginForm>({
  email: '',
  password: '',
});

// ログイン処理
const handleLogin = async () => {
  loading.value = true;
  error.value = '';

  try {
    await authStore.login(form);

    // ログイン成功後、ホームページにリダイレクト
    router.push('/');
  } catch(err: any) {
    console.error('ログインエラー:', err);
    error.value = err.response?.data?.message || 'ログインに失敗しました';
  } finally {
    loading.value = false;
  }
};
</script>

<style></style>