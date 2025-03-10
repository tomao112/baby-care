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
    console.log('ログイン開始:', form);
    const response = await authStore.login(form);
    console.log('ログイン成功:', response);
    
    // ログイン成功後、ホームページにリダイレクト
    console.log('リダイレクト前:', router);
    router.push('/');
    console.log('リダイレクト後');
  } catch (err: any) {
    console.error('ログインエラー:', err);
    
    // エラーメッセージの表示
    if (err.response) {
      console.log('エラーレスポンス:', err.response.data);
      if (err.response.data.errors) {
        const errors = err.response.data.errors;
        const messages = Object.values(errors).flat();
        error.value = messages.join('\n');
      } else if (err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = 'ログインに失敗しました。入力内容を確認してください。';
      }
    } else {
      error.value = 'ログインに失敗しました。ネットワーク接続を確認してください。';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style></style>