<template>
  <div class="app-container">
    <header class="app-header">
      <div class="header-content">
        <h1 class="app-logo animate__animated animate__fadeIn">育児支援アプリ</h1>
        <nav v-if="isLoggedIn" class="app-nav animate__animated animate__fadeIn">
          <router-link to="/" class="nav-link">ホーム</router-link>
          <router-link to="/children" class="nav-link">お子様一覧</router-link>
          <button @click="logout" class="nav-button">ログアウト</button>
        </nav>
      </div>
    </header>

    <main class="app-main">
      <!-- ルータービューを常に表示 -->
      <router-view />
      
      <!-- 機能紹介を表示（ホームページの場合のみ） -->
      <div class="app-features animate__animated animate__fadeIn" v-if="isHomePage">
        <div class="feature-card animate__animated animate__fadeInUp" style="--animate-delay: 0.1s">
          <div class="feature-icon">📝</div>
          <h3>成長記録</h3>
          <p>お子様の成長を記録して、大切な瞬間を残しましょう</p>
        </div>
        
        <div class="feature-card animate__animated animate__fadeInUp" style="--animate-delay: 0.2s">
          <div class="feature-icon">🔔</div>
          <h3>予防接種リマインダー</h3>
          <p>予防接種のスケジュールを管理し、通知を受け取れます</p>
        </div>
        
        <div class="feature-card animate__animated animate__fadeInUp" style="--animate-delay: 0.3s">
          <div class="feature-icon">📊</div>
          <h3>成長グラフ</h3>
          <p>身長・体重の推移をグラフで確認できます</p>
        </div>
      </div>
    </main>

    <footer class="app-footer animate__animated animate__fadeIn">
      <p>&copy; {{ new Date().getFullYear() }} 育児支援アプリ</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

// ログイン状態を監視
const isLoggedIn = computed(
  () => authStore.isLoggedIn
);

// 現在のページがホームページかどうかを確認
const isHomePage = computed(() => {
  return route.path === '/';
});

// ログアウト処理
const logout = async() => {
  await authStore.logout();
  router.push('/login');
}
</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
@import url('https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@300;400;500;700&display=swap');

:root {
  --primary-color: #6a5acd;
  --secondary-color: #ffb6c1;
  --accent-color: #b5e8f7;
  --text-color: #4a4a68;
  --light-bg: #f8f9ff;
  --white: #ffffff;
  --shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'M PLUS Rounded 1c', sans-serif;
  background-color: var(--light-bg);
  color: var(--text-color);
  line-height: 1.6;
}

.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.app-header {
  background-color: var(--white);
  box-shadow: var(--shadow);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.app-logo {
  color: var(--primary-color);
  font-size: 1.8rem;
  font-weight: 700;
  text-shadow: 0.1rem 0.1rem 0.2rem rgba(106, 90, 205, 0.2);
}

.app-nav {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-link {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  transition: all 0.3s ease;
}

.nav-link:hover {
  background-color: rgba(106, 90, 205, 0.1);
}

.nav-link.router-link-active {
  background-color: var(--primary-color);
  color: var(--white);
}

.nav-button {
  background-color: var(--secondary-color);
  color: var(--white);
  border: none;
  padding: 0.5rem 1.2rem;
  border-radius: 2rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.nav-button:hover {
  background-color: #ff9daa;
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(255, 182, 193, 0.4);
}

.app-main {
  flex: 1;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}

.app-features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr));
  gap: 2rem;
  margin: 3rem 0;
}

.feature-card {
  background-color: var(--white);
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: var(--shadow);
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-0.5rem);
  box-shadow: 0 0.8rem 2rem rgba(0, 0, 0, 0.1);
}

.feature-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.feature-card h3 {
  color: var(--primary-color);
  margin-bottom: 1rem;
  font-size: 1.3rem;
}

.feature-card p {
  color: var(--text-color);
  font-size: 0.95rem;
}

.app-footer {
  background-color: var(--white);
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 -0.2rem 1rem rgba(0, 0, 0, 0.05);
  margin-top: 2rem;
}

.app-footer p {
  color: var(--text-color);
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .app-nav {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .app-main {
    padding: 1.5rem 1rem;
  }
  
  .app-features {
    grid-template-columns: 1fr;
  }
}
</style>