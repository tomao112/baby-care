<template>
  <div class="home-container animate__animated animate__fadeIn">
    <h2 class="app-title animate__animated animate__fadeInDown">育児支援アプリへようこそ</h2>

    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>読み込み中...</p>
    </div>

    <div v-else class="content-container">
      <div class="welcome-message animate__animated animate__fadeIn" style="--animate-delay: 0.2s">
        <p v-if="authStore.user">{{ authStore.user.name }}さん、おかえりなさい！</p>
        <p>お子様の成長を記録して、大切な瞬間を残しましょう</p>
      </div>

      <div class="quick-actions animate__animated animate__fadeInUp" style="--animate-delay: 0.3s">
        <div class="action-card" @click="navigateTo('/children')">
          <div class="action-icon">👶</div>
          <h3>お子様一覧</h3>
          <p>登録されているお子様の情報を確認できます</p>
          <button class="action-button">
            <span>一覧を見る</span>
            <span class="button-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
            </span>
          </button>
        </div>

        <div class="action-card" @click="navigateTo('/children/new')">
          <div class="action-icon">➕</div>
          <h3>お子様を追加</h3>
          <p>新しくお子様の情報を登録できます</p>
          <button class="action-button">
            <span>追加する</span>
            <span class="button-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
            </span>
          </button>
        </div>

        <div class="action-card" @click="navigateTo('/profile')">
          <div class="action-icon">👤</div>
          <h3>プロフィール</h3>
          <p>アカウント情報の確認・変更ができます</p>
          <button class="action-button">
            <span>確認する</span>
            <span class="button-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
            </span>
          </button>
        </div>
      </div>

      <div class="recent-activity animate__animated animate__fadeInUp" style="--animate-delay: 0.4s">
        <h3 class="section-title">最近の記録</h3>
        <div class="activity-empty">
          <p>まだ記録がありません</p>
          <p>お子様を登録して、成長の記録を始めましょう！</p>
          <button class="start-button" @click="navigateTo('/children/new')">
            <span>記録を始める</span>
            <span class="button-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
              </svg>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const loading = ref(true);

// ナビゲーション関数
const navigateTo = (path: string) => {
  router.push(path);
};

onMounted(async () => {
  try {
    // ユーザ情報がない場合に取得
    // if(!authStore.user) {
    //   await authStore.fetchUser();
    // }
  } catch(error) {
    console.error('ユーザ情報の取得に失敗しました:', error);
  } finally {
    loading.value = false;
  }
});

</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.home-container {
  max-width: 60rem;
  margin: 2rem auto;
  padding: 2.5rem;
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
  font-size: 2.2rem;
  margin-bottom: 2rem;
  color: #6a5acd;
  font-weight: 700;
  text-align: center;
  text-shadow: 0.1rem 0.1rem 0.2rem rgba(106, 90, 205, 0.2);
}

.loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  color: #6a5acd;
}

.spinner {
  width: 3rem;
  height: 3rem;
  border: 0.3rem solid rgba(106, 90, 205, 0.3);
  border-radius: 50%;
  border-top-color: #6a5acd;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.content-container {
  margin-top: 1.5rem;
}

.welcome-message {
  text-align: center;
  margin-bottom: 2.5rem;
  color: #4a4a68;
  font-size: 1.1rem;
  line-height: 1.6;
}

.welcome-message p:first-child {
  font-weight: 600;
  color: #6a5acd;
  font-size: 1.3rem;
  margin-bottom: 0.5rem;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.action-card {
  background-color: #f8f9ff;
  border-radius: 1rem;
  padding: 1.8rem;
  text-align: center;
  box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  color: #4a4a68;
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 0.15rem solid transparent;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.action-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.action-card:hover {
  transform: translateY(-0.5rem);
  box-shadow: 0 0.8rem 2rem rgba(0, 0, 0, 0.1);
  border-color: #e6e6fa;
}

.action-card:hover::after {
  opacity: 1;
}

.action-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.action-card h3 {
  color: #6a5acd;
  margin-bottom: 0.8rem;
  font-size: 1.2rem;
}

.action-card p {
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 1.5rem;
}

.action-button {
  background: linear-gradient(135deg, #6a5acd, #8a7bea);
  color: white;
  border: none;
  border-radius: 2rem;
  padding: 0.7rem 1.4rem;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: auto;
  width: 100%;
  max-width: 10rem;
  position: relative;
  overflow: hidden;
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.3);
}

.action-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.action-button:hover {
  transform: translateY(-0.2rem);
  box-shadow: 0 0.5rem 1rem rgba(106, 90, 205, 0.4);
  background: linear-gradient(135deg, #5a4abf, #7a6bda);
}

.action-button:hover::before {
  left: 100%;
}

.button-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}

.action-button:hover .button-icon {
  transform: translateX(0.3rem);
}

.section-title {
  color: #6a5acd;
  font-size: 1.4rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 0.15rem solid #e6e6fa;
}

.recent-activity {
  background-color: #f8f9ff;
  border-radius: 1rem;
  padding: 1.8rem;
  box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.05);
}

.activity-empty {
  text-align: center;
  padding: 2rem;
  color: #8a8aa8;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.activity-empty p:first-child {
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.activity-empty p:nth-child(2) {
  margin-bottom: 1.5rem;
}

.start-button {
  background: linear-gradient(135deg, #ffb6c1, #ff9daa);
  color: white;
  border: none;
  border-radius: 2rem;
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 0.3rem 0.6rem rgba(255, 182, 193, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  position: relative;
  overflow: hidden;
}

.start-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.start-button:hover {
  background: linear-gradient(135deg, #ff9daa, #ff8499);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.5rem 1rem rgba(255, 182, 193, 0.5);
}

.start-button:hover::before {
  left: 100%;
}

.start-button:hover .button-icon {
  transform: rotate(90deg);
}

@media (max-width: 48rem) {
  .home-container {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .app-title {
    font-size: 1.8rem;
  }
  
  .quick-actions {
    grid-template-columns: 1fr;
  }
  
  .action-button, .start-button {
    width: 100%;
    max-width: 100%;
  }
}
</style>