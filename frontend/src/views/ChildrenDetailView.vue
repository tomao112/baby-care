<template>
  <div class="child-detail-container animate__animated animate__fadeIn">
    <div class="detail-header">
      <div class="title-wrapper">
        <h2 class="page-title animate__animated animate__fadeInDown">{{ child?.name || 'お子さま詳細' }}</h2>
        <div class="title-decoration"></div>
      </div>
      <div class="header-actions animate__animated animate__fadeInRight">
        <router-link to="/children" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5"></path>
            <path d="M12 19l-7-7 7-7"></path>
          </svg>
          <span>一覧に戻る</span>
        </router-link>
        <button v-if="child" @click="showEditForm = true" class="edit-button">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          <span>編集</span>
        </button>
      </div>
    </div>

    <div v-if="loading" class="loading-container animate__animated animate__fadeIn">
      <div class="spinner"></div>
      <p>読み込み中<span class="dot-animation">...</span></p>
    </div>

    <div v-else-if="child" class="child-info-container animate__animated animate__fadeIn">
      <div class="info-card">
        <div class="card-header">
          <h3 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>基本情報</span>
          </h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">お名前</div>
              <div class="info-value">{{ child.name }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">生年月日</div>
              <div class="info-value">{{ formatDate(child.birth_date) }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">年齢</div>
              <div class="info-value">{{ calculateAge(child.birth_date) }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">性別</div>
              <div class="info-value">
                <span class="gender-badge" :class="getGenderClass(child.gender)">
                  {{ formatGender(child.gender) }}
                </span>
              </div>
            </div>
            <div class="info-item notes-item">
              <div class="info-label">メモ</div>
              <div class="info-value notes-value">{{ child.notes || '特記事項なし' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="info-card growth-record-section">
        <div class="card-header">
          <h3 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            <span>成長記録</span>
          </h3>
          <div class="header-actions">
            <router-link :to="`/children/${child.id}/growth-records`" class="">
              <button class="add-record-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>記録を追加</span>
              </button>
            </router-link>
            
            <router-link :to="`/children/${child.id}/growth-records`" class="view-records-button">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
              <span>すべての記録を見る</span>
            </router-link>
          </div>
        </div>
        
        <div class="card-body">
          <div class="empty-records">
            <div class="empty-icon">📝</div>
            <p class="empty-title">まだ成長記録がありません</p>
            <p class="empty-description">「記録を追加」ボタンから記録を始めましょう</p>
            
            <router-link :to="`/children/${child.id}/growth-records`" class="mobile-view-records-button">
              すべての記録を見る
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
            </router-link>
          </div>
        </div>
      </div>

      <div class="info-card development-section">
        <div class="card-header">
          <h3 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>発達記録</span>
          </h3>
        </div>
        <div class="card-body">
          <Development :childId="child?.id" />
        </div>
      </div>
    </div>

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
import Development from './Development.vue';

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
// const growthRecord = ref<GrowthRecord[]>([]);

// 初期データの読み込み
onMounted(async () => {
  loading.value = true;
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
    loading.value = false;
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
    formLoading.value = false;
  }
};

// 性別に基づくクラスを取得
const getGenderClass = (gender: string | null): string => {
  if (!gender) return 'gender-unknown';
  
  const genderClassMap: Record<string, string> = {
    'male': 'gender-male',
    'female': 'gender-female',
    'other': 'gender-other',
  };
  
  return genderClassMap[gender] || 'gender-unknown';
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

<style scoped>
.child-detail-container {
  max-width: 60rem;
  margin: 2rem auto;
  padding: 0 1.5rem;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.title-wrapper {
  position: relative;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  color: #4a4a68;
  margin: 0 0 0.5rem 0;
}

.title-decoration {
  height: 0.3rem;
  width: 4rem;
  background: linear-gradient(90deg, #6a5acd, #9370db);
  border-radius: 1rem;
}

.header-actions {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.back-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #6a5acd;
  font-weight: 600;
  padding: 0.6rem 1rem;
  border-radius: 0.5rem;
  background-color: rgba(106, 90, 205, 0.05);
  transition: all 0.3s ease;
}

.back-link svg {
  margin-right: 0.5rem;
}

.back-link:hover {
  background-color: rgba(106, 90, 205, 0.15);
  transform: translateX(-0.2rem);
}

.edit-button {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.edit-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.edit-button:hover {
  background: linear-gradient(135deg, #5a4abf, #4a3aaf);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.4);
}

.edit-button:hover::before {
  left: 100%;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  color: #8a8aa8;
}

.spinner {
  width: 3rem;
  height: 3rem;
  border: 0.3rem solid rgba(106, 90, 205, 0.1);
  border-top: 0.3rem solid #6a5acd;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

.dot-animation {
  display: inline-block;
  animation: dots 1.5s infinite;
}

.child-info-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.info-card {
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.card-header {
  padding: 1.2rem 1.5rem;
  border-bottom: 1px solid #f0f0fa;
  background-color: #f8f9ff;
}

.section-title {
  display: flex;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: #4a4a68;
  margin: 0;
}

.section-title svg {
  margin-right: 0.8rem;
  color: #6a5acd;
}

.card-body {
  padding: 1.5rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr));
  gap: 1.5rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.notes-item {
  grid-column: 1 / -1;
}

.info-label {
  font-size: 0.9rem;
  color: #8a8aa8;
  font-weight: 500;
}

.info-value {
  font-size: 1.1rem;
  color: #4a4a68;
  font-weight: 500;
}

.notes-value {
  background-color: #f8f9ff;
  padding: 1rem;
  border-radius: 0.5rem;
  font-size: 1rem;
  white-space: pre-line;
  min-height: 3rem;
}

.gender-badge {
  display: inline-block;
  padding: 0.3rem 0.8rem;
  border-radius: 1rem;
  font-size: 0.9rem;
  font-weight: 600;
  color: white;
}

.gender-male {
  background: linear-gradient(135deg, #64b5f6, #2196f3);
}

.gender-female {
  background: linear-gradient(135deg, #f48fb1, #ec407a);
}

.gender-other, .gender-unknown {
  background: linear-gradient(135deg, #9575cd, #673ab7);
}

.coming-soon {
  opacity: 0.8;
}

.coming-soon-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  text-align: center;
}

.coming-soon-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  animation: pulse 2s infinite;
}

.coming-soon-text {
  font-size: 1.2rem;
  font-weight: 600;
  color: #6a5acd;
  margin: 0 0 0.5rem 0;
}

.coming-soon-description {
  color: #8a8aa8;
  margin: 0;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes dots {
  0%, 20% { content: '.'; }
  40% { content: '..'; }
  60%, 100% { content: '...'; }
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

@media (max-width: 48rem) {
  .child-detail-container {
    margin: 1rem;
    padding: 0;
  }
  
  .detail-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .header-actions {
    width: 100%;
  }
  
  .back-link, .edit-button {
    flex: 1;
    justify-content: center;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
}

.growth-record-section .card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.add-record-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-record-button:hover {
  background: linear-gradient(135deg, #5a4abf, #4a3aaf);
  transform: translateY(-0.1rem);
  box-shadow: 0 0.2rem 0.4rem rgba(106, 90, 205, 0.4);
}

.growth-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
}

.tab-button {
  padding: 0.6rem 1.2rem;
  border: none;
  background-color: #f0f2ff;
  color: #6a5acd;
  font-weight: 600;
  border-radius: 2rem;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.tab-button.active {
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  box-shadow: 0 0.2rem 0.4rem rgba(106, 90, 205, 0.3);
}

.tab-button:hover:not(.active) {
  background-color: #e0e4ff;
}

.growth-chart-area {
  background-color: #f8f9ff;
  border-radius: 0.8rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.chart-filters {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.filter-label {
  font-size: 0.9rem;
  color: #6a5acd;
  font-weight: 600;
}

.filter-options {
  display: flex;
  gap: 1rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  cursor: pointer;
  font-size: 0.9rem;
  color: #4a4a68;
}

.period-select {
  padding: 0.4rem 0.8rem;
  border-radius: 0.5rem;
  border: 1px solid #ddddf0;
  background-color: white;
  color: #4a4a68;
  font-size: 0.9rem;
}

.chart-container {
  height: 300px;
  width: 100%;
  position: relative;
}

.placeholder-chart {
  height: 100%;
  background-color: white;
  border-radius: 0.5rem;
  border: 1px dashed #ddddf0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chart-placeholder-text {
  text-align: center;
  color: #8a8aa8;
}

.chart-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.records-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #4a4a68;
  margin: 0 0 1.5rem 0;
  display: flex;
  align-items: center;
}

.records-title::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, #ddddf0, transparent);
  margin-left: 1rem;
}

.record-cards {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}

.record-card {
  background-color: white;
  border-radius: 0.8rem;
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.05);
  padding: 1rem;
  display: flex;
  gap: 1rem;
  transition: all 0.3s ease;
  border: 1px solid #f0f0fa;
}

.record-card:hover {
  box-shadow: 0 0.4rem 0.8rem rgba(0, 0, 0, 0.08);
  transform: translateY(-0.2rem);
}

.record-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 0.3rem;
}

.date-bubble {
  background: linear-gradient(135deg, #6a5acd, #9370db);
  color: white;
  font-weight: 600;
  padding: 0.4rem 0.6rem;
  border-radius: 1rem;
  font-size: 0.8rem;
  margin-bottom: 0.3rem;
}

.date-year {
  font-size: 0.8rem;
  color: #8a8aa8;
}

.record-content {
  flex: 1;
}

.record-age {
  font-weight: 600;
  color: #4a4a68;
  margin-bottom: 0.5rem;
}

.record-measurements {
  display: flex;
  gap: 1rem;
  margin-bottom: 0.8rem;
  flex-wrap: wrap;
}

.measurement-item {
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.measurement-label {
  font-size: 0.9rem;
  color: #8a8aa8;
}

.measurement-value {
  font-weight: 600;
  color: #4a4a68;
}

.record-memo {
  font-size: 0.9rem;
  color: #4a4a68;
  background-color: #f8f9ff;
  padding: 0.5rem;
  border-radius: 0.5rem;
  border-left: 3px solid #6a5acd;
}

.record-actions {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.action-button {
  border: none;
  background: none;
  color: #6a5acd;
  cursor: pointer;
  padding: 0.3rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.action-button:hover {
  background-color: rgba(106, 90, 205, 0.1);
}

.view-more-button {
  margin: 2rem auto 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: 1px solid #ddddf0;
  color: #6a5acd;
  padding: 0.6rem 1.5rem;
  border-radius: 2rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.view-more-button:hover {
  background-color: rgba(106, 90, 205, 0.05);
  border-color: #6a5acd;
}

@media (max-width: 48rem) {
  .chart-filters {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .filter-group {
    width: 100%;
  }
  
  .record-cards {
    grid-template-columns: 1fr;
  }
}

.growth-record-section .header-actions {
  display: flex;
  gap: 0.8rem;
}

.view-records-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: rgba(106, 90, 205, 0.1);
  color: #6a5acd;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  text-decoration: none;
  transition: all 0.3s ease;
}

.view-records-button:hover {
  background-color: rgba(106, 90, 205, 0.2);
  transform: translateX(0.1rem);
}

.view-records-button svg {
  transition: transform 0.3s ease;
}

.view-records-button:hover svg {
  transform: translateX(0.2rem);
}

.empty-records {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
  text-align: center;
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.7;
}

.empty-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #4a4a68;
  margin: 0 0 0.5rem 0;
}

.empty-description {
  color: #8a8aa8;
  margin: 0 0 1.5rem 0;
}

.mobile-view-records-button {
  display: none;
  align-items: center;
  gap: 0.5rem;
  background-color: #f8f9ff;
  color: #6a5acd;
  border: 1px solid rgba(106, 90, 205, 0.3);
  padding: 0.7rem 1.5rem;
  border-radius: 2rem;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.3s ease;
}

.mobile-view-records-button svg {
  transition: transform 0.3s ease;
}

.mobile-view-records-button:hover svg {
  transform: translateX(0.2rem);
}

@media (max-width: 48rem) {
  .growth-record-section .header-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .add-record-button, .view-records-button {
    width: 100%;
    justify-content: center;
  }
  
  .view-records-button {
    display: none;
  }
  
  .mobile-view-records-button {
    display: flex;
  }
}

.development-section .card-body {
  padding: 0;
}

.info-card + .info-card {
  margin-top: 2rem;
}
</style>