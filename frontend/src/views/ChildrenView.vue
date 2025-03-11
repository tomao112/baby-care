<template>
  <div>
    <div>
      <h2>お子さま一覧</h2>
      <button @click="showAddForm = true">
        <span>+</span>お子さま追加
      </button>
    </div>

    <div v-if="childrenStore.loading">
      <p>読み込み中...</p>
    </div>

    <!-- <div v-else-if="childrenStore.error">
      {{ childrenStore.error }}
    </div> -->

    <div v-else-if="children.length === 0">
      <p>お子さまの情報が登録されていません</p>
      <button @click="showAddForm = true">
        お子さま登録
      </button>
    </div>

    <div v-else>
      <Child-item
        v-for="child in children"
        :key="child.id"
        :child="child"
        @edit="editChild"
        @delete="confirmDelete"
      />
    </div>

    <!-- 子供追加フォーム -->
    <modal-dialog v-if="showAddForm" @close="showAddForm = false">
      <template #header>
        <h3>お子さまの登録</h3>
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
        <h3>お子さま情報の編集</h3>
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
        <h3>削除の確認</h3>
      </template>
      <template #default>
        <p>「{{ selectedChild?.name }}」の情報を削除してもよろしいですか？</p>
        <p>この操作は取り消せません</p>
        <div>
          <button @click="showDeleteConfirm = false">キャンセル</button>
          <button @click="deleteChild":disabled="formLoading">
            {{ formLoading ? '削除中...' : '削除する' }}
          </button>
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
const updateChild = async (FormData: ChildsForm) => {
  if(!selectedChild.value) return;

  formLoading.value = true;
  try {
    await childrenStore.updateChild(selectedChild.value.id, FormData);
    showEditForm.value = false;
  } catch(error) {
    console.error('子供の更新に失敗しました:', error);
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
  if(!selectedChild.value) return;

  formLoading.value = true;
  try {
    await childrenStore.deleteChild(selectedChild.value.id);
    showDeleteConfirm.value = false;
  } catch(error) {
    console.error('子供の削除に失敗しました:', error);
  }finally {
    formLoading.value = false;
  }
}

</script>
