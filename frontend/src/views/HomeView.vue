<template>
  <div class="home-container">
    <h2 class="app-title animate__animated animate__fadeInDown">Childcare Support App</h2>

    <div v-if="loading" class="loading">
      <p>読み込み中...</p>
    </div>

    <div class="content-container">
      <div class="auth-buttons">
        <div class="top_from animate__animated animate__fadeInUp" style="--animate-delay: 0.3s">
          <router-link to="/register" class="btn btn-primary">Get Started</router-link>
        </div>
        <div class="login-link animate__animated animate__fadeInUp" style="--animate-delay: 0.5s">
          <router-link to="/login" class="btn btn-secondary">Log In</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const loading = ref(true);

// ユーザ情報を取得
// const user = computed(
//   () => authStore.user
// );

onMounted(async () => {
  try {
    // ユーザ情報がない場合に取得
    if(!authStore.user) {
      await authStore.fetchUser();
    }
  } catch(error) {
    console.error('ユーザ情報の取得に失敗しました:', error);
  } finally {
    loading.value = false;
  }
});

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.home-container {
  max-width: 50rem;
  margin: 2rem auto;
  padding: 2.5rem;
  text-align: center;
  background-color: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.home-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0.5rem;
  background: linear-gradient(90deg, #ffb6c1, #e6e6fa, #b5e8f7);
}

.app-title {
  font-size: 2.8rem;
  margin-bottom: 2.5rem;
  color: #6a5acd;
  font-weight: 700;
  text-shadow: 0.1rem 0.1rem 0.2rem rgba(106, 90, 205, 0.2);
}

.loading {
  padding: 1.5rem;
  color: #6a5acd;
  font-style: italic;
}

.content-container {
  margin-top: 3.5rem;
}

.auth-buttons {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.8rem;
}

.btn {
  display: inline-block;
  padding: 1rem 2.5rem;
  border-radius: 2rem;
  font-size: 1.2rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  position: relative;
  overflow: hidden;
  z-index: 1;
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
  background-color: #ffb6c1;
  color: #fff;
  box-shadow: 0 0.3rem 0.6rem rgba(255, 182, 193, 0.4);
}

.btn-primary:hover {
  background-color: #ff9daa;
  transform: translateY(-0.3rem);
  box-shadow: 0 0.5rem 1rem rgba(255, 182, 193, 0.5);
}

.btn-secondary {
  background-color: #e6e6fa;
  color: #6a5acd;
  box-shadow: 0 0.3rem 0.6rem rgba(230, 230, 250, 0.4);
}

.btn-secondary:hover {
  background-color: #d8d8f0;
  transform: translateY(-0.3rem);
  box-shadow: 0 0.5rem 1rem rgba(230, 230, 250, 0.5);
}

.login-link {
  margin-top: 0.5rem;
}

@media (min-width: 48rem) {
  .auth-buttons {
    flex-direction: row;
    justify-content: center;
    gap: 2.5rem;
  }
  
  .login-link {
    margin-top: 0;
  }
}
</style>