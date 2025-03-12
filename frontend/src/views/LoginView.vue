<template>
  <div class="login-container animate__animated animate__fadeIn">
    <h2 class="login-title animate__animated animate__fadeInDown">ログイン</h2>

    <div v-if="error" class="error-message animate__animated animate__shakeX">
      {{ error }}
    </div>

    <form @submit.prevent="handleLogin" class="login-form animate__animated animate__fadeInUp" style="--animate-delay: 0.2s">
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input 
          v-model="form.email" 
          type="email"
          id="email"
          required
          placeholder="メールアドレスを入力してください"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label for="password">パスワード</label>
        <input 
          v-model="form.password"  
          type="password"
          id="password"
          required
          autocomplete="current-password"
          class="form-input"
        >
      </div>

      <div class="form-action">
        <button type="submit" :disabled="loading" class="btn btn-primary">
          <span v-if="loading" class="loading-spinner"></span>
          {{ loading ? 'ログイン中...' : 'ログイン' }}
        </button>
      </div>
    </form>

    <div class="register-link animate__animated animate__fadeInUp" style="--animate-delay: 0.4s">
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

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.login-container {
  max-width: 30rem;
  margin: 2rem auto;
  padding: 2.5rem 3.5rem;
  background-color: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.login-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0.5rem;
  background: linear-gradient(90deg, #ffb6c1, #e6e6fa, #b5e8f7);
}

.login-title {
  font-size: 2.2rem;
  margin-bottom: 2rem;
  color: #6a5acd;
  font-weight: 700;
  text-align: center;
  text-shadow: 0.1rem 0.1rem 0.2rem rgba(106, 90, 205, 0.2);
}

.error-message {
  background-color: #ffe6e6;
  color: #ff6b6b;
  padding: 1rem;
  border-radius: 0.5rem;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
  border-left: 0.25rem solid #ff6b6b;
}

.login-form {
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #6a5acd;
  font-size: 1rem;
}

.form-input {
  width: 92%;
  padding: 0.9rem 1.2rem;
  border: 0.125rem solid #e6e6fa;
  border-radius: 0.75rem;
  font-size: 1rem;
  transition: all 0.3s ease;
  background-color: #f8f9ff;
}

.form-input:focus {
  outline: none;
  border-color: #b5e8f7;
  box-shadow: 0 0 0 0.25rem rgba(181, 232, 247, 0.25);
}

.form-input::placeholder {
  color: #b4b4d9;
}

.form-action {
  margin-top: 2rem;
  text-align: center;
}

.btn {
  display: inline-block;
  padding: 0.9rem 2.5rem;
  border-radius: 2rem;
  font-size: 1.1rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  position: relative;
  overflow: hidden;
  z-index: 1;
  border: none;
  cursor: pointer;
}

.btn::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 0;
  background-color: rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
  z-index: -1;
}

.btn:hover::after {
  height: 100%;
}

.btn-primary {
  background-color: #6a5acd;
  color: #fff;
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.4);
  min-width: 10rem;
}

.btn-primary:hover {
  background-color: #5a4abf;
  transform: translateY(-0.3rem);
  box-shadow: 0 0.5rem 1rem rgba(106, 90, 205, 0.5);
}

.btn-primary:disabled {
  background-color: #b4b4d9;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.loading-spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
  border: 0.2rem solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s linear infinite;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.register-link {
  text-align: center;
  margin-top: 1.5rem;
  font-size: 1rem;
  color: #6a5acd;
}

.register-link a {
  color: #ff9daa;
  font-weight: 600;
  text-decoration: none;
  position: relative;
  padding-bottom: 0.2rem;
}

.register-link a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 0.125rem;
  background-color: #ff9daa;
  transition: width 0.3s ease;
}

.register-link a:hover::after {
  width: 100%;
}

@media (max-width: 48rem) {
  .login-container {
    margin: 1rem;
    padding: 1.5rem 2rem;
  }
  
  .login-title {
    font-size: 1.8rem;
  }
  
  .form-input {
    padding: 0.8rem 1rem;
  }
  
  .btn {
    padding: 0.8rem 2rem;
    font-size: 1rem;
  }
}
</style>