<template>
  <div>
    <h2>ホーム</h2>

    <div v-if="loading">
      <p>読み込み中...</p>
    </div>

    <div>
      <h3>ようこそ、{{ user?.name }}さん</h3>
      <p>育児支援アプリへようこそ！</p>

      <div>
        <router-lin to="/children">お子様一覧</router-lin>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const loading = ref(true);

// ユーザ情報を取得
const user = computed(
  () => authStore.user
);

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

<style></style>