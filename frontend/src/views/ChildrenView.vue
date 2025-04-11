<template>
  <div class="children-container animate__animated animate__fadeIn">
    <div class="children-header">
      <div class="title-wrapper">
        <h2 class="page-title animate__animated animate__fadeInDown">お子さま一覧</h2>
        <div class="title-decoration"></div>
      </div>
      <button @click="showAddForm = true" class="add-button animate__animated animate__fadeInRight">
        <span class="button-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14"></path>
            <path d="M5 12h14"></path>
          </svg>
        </span>
        <span>お子さま追加</span>
        <span class="button-effect"></span>
      </button>
    </div>

    <div v-if="childrenStore.loading" class="loading-container animate__animated animate__fadeIn">
      <div class="spinner"></div>
      <p>読み込み中<span class="dot-animation">...</span></p>
    </div>

    <!-- <div v-else-if="childrenStore.error">
      {{ childrenStore.error }}
    </div> -->

    <div v-else-if="children.length === 0" class="empty-state animate__animated animate__fadeIn">
      <div class="empty-icon-wrapper">
        <div class="empty-icon">👶</div>
        <div class="empty-icon-decoration"></div>
      </div>
      <p class="empty-title">お子さまの情報が登録されていません</p>
      <p class="empty-description">お子さまを登録して、成長記録を始めましょう</p>
      <button @click="showAddForm = true" class="start-button">
        <span>お子さま登録</span>
        <span class="button-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14"></path>
            <path d="M5 12h14"></path>
          </svg>
        </span>
        <span class="button-effect"></span>
      </button>
    </div>

    <div v-else class="children-list animate__animated animate__fadeIn">
      <Child-item
        v-for="child in children"
        :key="child.id"
        :child="child"
        @edit="editChild"
        @delete="confirmDelete"
        class="animate__animated animate__fadeInUp"
        :style="`--animate-delay: ${0.1 * (children.indexOf(child) + 1)}s`"
      />
      
      <div class="add-card animate__animated animate__fadeInUp" @click="showAddForm = true" 
           :style="`--animate-delay: ${0.1 * (children.length + 1)}s`">
        <div class="add-card-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14"></path>
            <path d="M5 12h14"></path>
          </svg>
        </div>
        <p class="add-card-text">新しいお子さまを追加</p>
      </div>
    </div>

    <!-- 子供追加フォーム -->
    <modal-dialog v-if="showAddForm" @close="showAddForm = false">
      <template #header>
        <div class="modal-header-content">
          <h3>お子さまの登録</h3>
          <div class="modal-header-decoration"></div>
        </div>
      </template>
      <template #default>
        <child-form
          :loading="formLoading"
          @submit="addChild"
          @cancel="showAddForm = false"
        />
      </template>
    </modal-dialog>

    <!-- 子供編集フォーム -->
    <modal-dialog v-if="showEditForm" @close="showEditForm = false">
      <template #header>
        <div class="modal-header-content">
          <h3>お子さま情報の編集</h3>
          <div class="modal-header-decoration"></div>
        </div>
      </template>
      <template #default>
        <child-form 
          v-if="selectedChild"
          :child="selectedChild"
          :loading="formLoading"
          @submit="updateChild"
          @cancel="showEditForm = false"
        />
      </template>
    </modal-dialog>

    <!-- 削除確認ダイアログ -->
    <modal-dialog v-if="showDeleteConfirm" @close="showDeleteConfirm = false">
      <template #header>
        <div class="modal-header-content">
          <h3>削除の確認</h3>
          <div class="modal-header-decoration"></div>
        </div>
      </template>
      <template #default>
        <div class="confirm-content">
          <div class="warning-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
              <line x1="12" y1="9" x2="12" y2="13"></line>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
          </div>
          <p class="confirm-message">「{{ selectedChild?.name }}」の情報を削除してもよろしいですか？</p>
          <p class="confirm-warning">この操作は取り消せません</p>
          <div class="confirm-actions">
            <button @click="showDeleteConfirm = false" class="cancel-button">
              <span>キャンセル</span>
              <span class="button-effect"></span>
            </button>
            <button @click="deleteChild" :disabled="formLoading" class="delete-button">
              <span v-if="formLoading" class="spinner-small"></span>
              <span>{{ formLoading ? '削除中...' : '削除する' }}</span>
              <span class="button-effect"></span>
            </button>
          </div>
        </div>
      </template>
    </modal-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useChildrenStore } from '@/stores/children';
import { Child, ChildsForm } from '@/types';
import ChildItem from '@/components/children/ChildItem.vue';
import ChildForm from '@/components/children/ChildForm.vue';
import ModalDialog from '@/components/ModalDialog.vue';

const childrenStore = useChildrenStore();

// 状態
const showAddForm = ref(false);
const showEditForm = ref(false);
const showDeleteConfirm = ref(false);
const formLoading = ref(false);
const selectedChild = ref<Child | null>(null);

// 子供一覧
const children = computed(
  () => childrenStore.children
);

// 初期データの読み込み
onMounted(async () => {
  if(children.value.length === 0) {
    await childrenStore.fetchChildren();
  }
});

// 子供の追加
const addChild = async (formData: ChildsForm) => {
  formLoading.value = true;
  try {
    await childrenStore.addChild(formData);
    showAddForm.value = false;
  } catch(error) {
    console.error('子供の追加に失敗しました:', error);
  } finally {
    formLoading.value = false;
  }
};

// 子供の編集
const editChild = (child: Child) => {
  selectedChild.value = child;
  showEditForm.value = true;
};

// 子供の更新
const updateChild = async (formData: ChildsForm) => {
  if(!selectedChild.value || !selectedChild.value.id) {
    console.error('更新しようとしている子供のIDが見つかりません', selectedChild.value);
    alert('子供のIDが見つかりません。ページを再読み込みしてください。');
    return;
  }

  const childId = selectedChild.value.id.toString();
  console.log(`子供ID: ${childId} の情報を更新します`, formData);

  formLoading.value = true;
  try {
    const response = await childrenStore.updateChild(childId, formData);
    console.log('更新成功:', response.data);

    // 選択中の子供データを更新
    if (response.data && selectedChild.value) {
      selectedChild.value = {
        ...selectedChild.value,
        ...formData,
        id: selectedChild.value.id
      };
    }
    
    showEditForm.value = false;
  } catch(error: any) {
    console.error('子供の更新に失敗しました:', error);
    const errorMessage = error.response?.data?.message || '子供の情報の更新に失敗しました';
    alert(`エラー: ${errorMessage}`);
  } finally {
    formLoading.value = false;
  }
};

// 削除確認
const confirmDelete = (child: Child) => {
  selectedChild.value = child;
  showDeleteConfirm.value = true;
};

// 子供の削除
const deleteChild = async () => {
  if(!selectedChild.value || !selectedChild.value.id) {
    console.error('子供のIDが見つかりません');
    return;
  }

  formLoading.value = true;
  try {
    await childrenStore.deleteChild(selectedChild.value.id.toString());
    showDeleteConfirm.value = false;
  } catch(error) {
    console.error('子供の削除に失敗しました:', error);
  }finally {
    formLoading.value = false;
  }
}

</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.children-container {
  max-width: 60rem;
  margin: 2rem auto;
  padding: 2.5rem;
  background-color: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.children-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 0.3rem;
  background: linear-gradient(90deg, #6a5acd, #9370db, #ba55d3);
  z-index: 1;
}

.children-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f0f0fa;
}

.title-wrapper {
  position: relative;
}

.page-title {
  color: #4a4a68;
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  position: relative;
}

.title-decoration {
  position: absolute;
  bottom: -0.5rem;
  left: 0;
  width: 3rem;
  height: 0.25rem;
  background: linear-gradient(90deg, #6a5acd, #9370db);
  border-radius: 1rem;
}

.add-button {
  background: linear-gradient(135deg, #6a5acd, #9370db);
  color: white;
  border: none;
  border-radius: 2rem;
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  position: relative;
  overflow: hidden;
}

.button-effect {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.add-button:hover {
  transform: translateY(-0.2rem);
  box-shadow: 0 0.5rem 1rem rgba(106, 90, 205, 0.5);
}

.add-button:hover .button-effect {
  left: 100%;
}

.button-icon {
  display: flex;
  align-items: center;
  justify-content: center;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
}

.spinner {
  width: 3rem;
  height: 3rem;
  border: 0.3rem solid #f0f0fa;
  border-top: 0.3rem solid #6a5acd;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

.dot-animation {
  display: inline-block;
  animation: dots 1.5s infinite;
}

@keyframes dots {
  0%, 20% { content: '.'; }
  40% { content: '..'; }
  60%, 100% { content: '...'; }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
  text-align: center;
}

.empty-icon-wrapper {
  position: relative;
  margin-bottom: 1.5rem;
}

.empty-icon {
  font-size: 4rem;
  background: linear-gradient(135deg, #f8f9ff, #fff);
  width: 7rem;
  height: 7rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
  position: relative;
  z-index: 1;
}

.empty-icon-decoration {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8rem;
  height: 8rem;
  border: 0.2rem dashed #e6e6fa;
  border-radius: 50%;
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.empty-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: #4a4a68;
  margin-bottom: 0.5rem;
}

.empty-description {
  color: #8a8aa8;
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

.start-button:hover {
  background: linear-gradient(135deg, #ff9daa, #ff8499);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.5rem 1rem rgba(255, 182, 193, 0.5);
}

.start-button:hover .button-effect {
  left: 100%;
}

.children-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
  gap: 1.5rem;
}

.add-card {
  background: linear-gradient(135deg, #f8f9ff, #fff);
  border: 0.15rem dashed #e6e6fa;
  border-radius: 1rem;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  height: 100%;
  min-height: 12rem;
}

.add-card:hover {
  border-color: #d8d8f0;
  transform: translateY(-0.3rem);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
}

.add-card-icon {
  color: #9370db;
  margin-bottom: 1rem;
  transition: transform 0.3s ease;
}

.add-card:hover .add-card-icon {
  transform: rotate(90deg);
}

.add-card-text {
  color: #8a8aa8;
  font-weight: 600;
  text-align: center;
}

.modal-header-content {
  position: relative;
  padding-bottom: 0.5rem;
}

.modal-header-decoration {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 3rem;
  height: 0.2rem;
  background: linear-gradient(90deg, #6a5acd, #9370db);
  border-radius: 1rem;
}

.confirm-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 1rem 0;
}

.warning-icon {
  color: #ff6b6b;
  margin-bottom: 1.5rem;
  background: rgba(255, 107, 107, 0.1);
  width: 4rem;
  height: 4rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

.confirm-message {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  color: #4a4a68;
}

.confirm-warning {
  color: #ff6b6b;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
}

.confirm-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  width: 100%;
  margin-top: 1rem;
}

.cancel-button {
  background: linear-gradient(135deg, #f0f0fa, #e6e6fa);
  color: #6a5acd;
  border: none;
  border-radius: 2rem;
  padding: 0.7rem 1.4rem;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  flex: 1;
  max-width: 10rem;
}

.cancel-button:hover {
  background: linear-gradient(135deg, #e6e6fa, #d8d8f0);
  transform: translateY(-0.2rem);
}

.cancel-button:hover .button-effect {
  left: 100%;
}

.delete-button {
  background: linear-gradient(135deg, #ff6b6b, #ff4f4f);
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
  position: relative;
  overflow: hidden;
  flex: 1;
  max-width: 10rem;
}

.delete-button:hover:not(:disabled) {
  background: linear-gradient(135deg, #ff4f4f, #ff3333);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(255, 107, 107, 0.4);
}

.delete-button:hover:not(:disabled) .button-effect {
  left: 100%;
}

.delete-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner-small {
  width: 1rem;
  height: 1rem;
  border: 0.15rem solid rgba(255, 255, 255, 0.3);
  border-top: 0.15rem solid #fff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-right: 0.5rem;
}

@media (max-width: 48rem) {
  .children-container {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .children-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .page-title {
    font-size: 1.8rem;
  }
  
  .add-button {
    width: 100%;
  }
  
  .children-list {
    grid-template-columns: 1fr;
  }
  
  .confirm-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .cancel-button, .delete-button {
    width: 100%;
    max-width: 100%;
  }
}
</style>
