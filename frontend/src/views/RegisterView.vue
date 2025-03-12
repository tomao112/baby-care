<template>
  <div class="register-container animate__animated animate__fadeIn">
    <h2 class="register-title animate__animated animate__fadeInDown">新規登録</h2>

    <div v-if="error" class="error-message animate__animated animate__shakeX">
      {{ error }}
    </div>

    <form @submit.prevent="handleRegister" class="register-form animate__animated animate__fadeInUp" style="--animate-delay: 0.2s">
      <div class="form-group">
        <label for="name">お名前</label>
        <input 
          id="name"
          type="text"
          v-model="form.name"
          required
          autocomplete="name"
          class="form-input"
          placeholder="お名前を入力してください"
        />
      </div>

      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input 
          id="email"
          type="email"
          v-model="form.email"
          required
          autocomplete="email"
          class="form-input"
          placeholder="メールアドレスを入力してください"
        />
      </div>

      <div class="form-group">
        <label for="password">パスワード</label>
        <input 
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="new-password"
          class="form-input"
        />
        <small class="form-hint">8文字以上で入力してください</small>
      </div>

      <div class="form-group">
        <label for="password_confirmation">パスワード（確認）</label>
        <input 
          id="password_confirmation"
          type="password"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
          class="form-input"
        />
      </div>

      <div class="form-action">
        <button type="submit" :disabled="loading" class="btn btn-primary">
          <span v-if="loading" class="loading-spinner"></span>
          {{ loading ? '登録中...' : '登録する' }}
        </button>
      </div>
    </form>

    <div class="login-link animate__animated animate__fadeInUp" style="--animate-delay: 0.4s">
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
    await authStore.register(form);

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

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.register-container {
  max-width: 30rem;
  margin: 2rem auto;
  padding: 2.5rem 3.5rem;
  background-color: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.register-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0.5rem;
  background: linear-gradient(90deg, #ffb6c1, #e6e6fa, #b5e8f7);
}

.register-title {
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

.register-form {
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
  width: 100%;
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

.form-hint {
  display: block;
  margin-top: 0.5rem;
  font-size: 0.85rem;
  color: #8a8aaa;
  font-style: italic;
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

.login-link {
  text-align: center;
  margin-top: 1.5rem;
  font-size: 1rem;
  color: #6a5acd;
}

.login-link a {
  color: #ff9daa;
  font-weight: 600;
  text-decoration: none;
  position: relative;
  padding-bottom: 0.2rem;
}

.login-link a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 0.125rem;
  background-color: #ff9daa;
  transition: width 0.3s ease;
}

.login-link a:hover::after {
  width: 100%;
}

@media (max-width: 48rem) {
  .register-container {
    margin: 1rem;
    padding: 1.5rem 2rem;
  }
  
  .register-title {
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