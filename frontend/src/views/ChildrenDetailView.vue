<template>
  <div>
    <div>
      <h2>{{ child?.name || 'お子さま詳細' }}</h2>
      <div>
        <router-link to="/children">
          ← 一覧に戻る
        </router-link>
        <button v-if="child" @click="showEditForm = true">
          編集
        </button>
      </div>
    </div>

    <div v-if="loading">
      <p>読み込み中...</p>
    </div>

    <div v-else-if="error">
      {{ error }}
    </div>

    <div v-else-if="child">
      <div>
        <div>
          <h3>基本情報</h3>
        </div>
        <div>
          <div>
            <div>お名前</div>
            <div>{{ child.name }}</div>
          </div>
          <div>
            <div>生年月日</div>
            <div>{{ formatDate(child.birth_date) }}</div>
          </div>
          <div>
            <div>年齢</div>
            <div>{{ calculateAge(child.birth_date) }}</div>
          </div>
          <div>
            <div>性別</div>
            <div>{{ formatGender(child.gender) }}</div>
          </div>
          <div>
            <div>メモ</div>
            <div>{{ child.notes }}</div>
          </div>
        </div>
      </div>

      <!-- ここに成長記録などの追加情報を表示 -->
    </div>

    <!-- 子供編集フォーム -->
    <modal-dialog v-if="showEditForm && child" @close="showEditForm = false">
      <template #header>
        <h3>お子さま情報の編集</h3>
      </template>
      <template #default>
        <child-form 
          :child="child"
          :loading="formLoading"
          @submit="updateChild"
          @cancel="showEditForm = false"
        />
      </template>
    </modal-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useChildrenStore } from '@/stores/children';
import { Child, ChildsForm } from '@/types';
import ChildForm  from '@/components/children/ChildForm.vue';
import ModalDialog from '@/components/ModalDialog.vue';

const route = useRoute();
const router = useRouter();
const childrenStore = useChildrenStore();

// 状態
const loading = ref(false);
const error = ref('');
const showEditForm = ref(false);
const formLoading = ref(false);

// 子供の情報
const child = ref<Child | null>(null);

// 初期データの読み込み
onMounted(async () => {
  const childId = route.params.id;
  if(!childId) {
    router.push('/children');
    return;
  }

  try {
    // 子供の情報を取得
    const response = await childrenStore.fetchChild(childId.toString());
    child.value = response.data;
  } catch(err: any) {
    console.error('子供の情報の取得に失敗しました:', err);
    error.value = err.response?.data?.message || 'データの取得に失敗しました';
  } finally {
    loading.value = true;
  }
});

// 子供の更新
const updateChild = async (formData: ChildsForm) => {
  if(!child.value) return;

  formLoading.value = true;
  try {
    const response = await childrenStore.updateChild(child.value.id, formData);
    child.value = response.data;
    showEditForm.value = false;
  } catch(err: any) {
    console.error('子供の情報の更新に失敗しました:', err);
  } finally {
    loading.value = false;
  }
};

// 日付のフォーマット
const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

// 年齢の計算
const calculateAge = (birthDateString: string): string => {
  const birthDate = new Date(birthDateString);
  const today = new Date();

  let years = today.getFullYear() - birthDate.getFullYear();
  let months = today.getMonth() - birthDate.getMonth();

  if(months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) {
    years--;
    months += 12;
  }

  if(years < 1) {
    if(months < 1) {
      const days = Math.floor((today.getTime() - birthDate.getTime()) / (1000 * 60 * 60 * 24));
      return `${days}日`;
    }
    return `${months}ヶ月`;
  }

  return `${years}歳${months}ヶ月`;
};

// 性別のフォーマット
const formatGender = (gender: string | null): string => {
  if(!gender) return '未設定';

  const genderMap: Record<string, string> = {
    'male': '男の子',
    'female': '女の子',
    'other': 'その他',
  };

  return genderMap[gender] || '未設定';
};
</script>

<style></style>