<template>
  <div>
    <header>
      <h1>育児支援アプリ</h1>
      <nav v-if="isLoggedIn">
        <router-link to="/">ホーム</router-link>
        <router-link to="/children">お子様一覧</router-link>
        <button @click="logout">ログアウト</button>
      </nav>
    </header>

    <main>
      <router-view />
    </main>

    <footer>
      <p>&copy; {{ new Date().getFullYear()}}育児支援アプリ</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

// ログイン状態を監視
const isLoggedIn = computed(
  () => authStore.isLoggedIn
);

// ログアウト処理
const logout = async() => {
  await authStore.logout();
  router.push('/login');
}
</script>

<style>

</style>