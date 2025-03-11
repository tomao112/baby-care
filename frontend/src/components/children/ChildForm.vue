<template>
  <form @submit.prevent="handleSubmit" class="child-form">
    <div class="form-group">
      <label for="name">お名前 <span class="required">*</span></label>
      <input 
        v-model="form.name" 
        type="text" 
        id="name" 
        required 
        :disabled="loading"
      >
    </div>
    
    <div class="form-group">
      <label for="birth_date">生年月日 <span class="required">*</span></label>
      <input 
        v-model="form.birth_date" 
        type="date" 
        id="birth_date" 
        required 
        :disabled="loading"
      >
    </div>
    
    <div class="form-group">
      <label for="gender">性別</label>
      <select 
        v-model="form.gender" 
        id="gender" 
        :disabled="loading"
      >
        <option value="">選択してください</option>
        <option value="male">男の子</option>
        <option value="female">女の子</option>
        <option value="other">その他</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="notes">メモ</label>
      <textarea 
        v-model="form.notes" 
        id="notes" 
        rows="3" 
        :disabled="loading"
      ></textarea>
    </div>
    
    <div class="form-actions">
      <button 
        type="button" 
        class="btn-secondary" 
        @click="$emit('cancel')" 
        :disabled="loading"
      >
        キャンセル
      </button>
      <button 
        type="submit" 
        class="btn-primary" 
        :disabled="loading"
      >
        {{ loading ? '保存中...' : isEdit ? '更新する' : '登録する' }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { reactive, onMounted, computed } from 'vue';
import { Child, ChildsForm as ChildFormType } from '@/types';

const props = defineProps<{
  child?: Child;
  loading: boolean;
}>();

const emit = defineEmits(['submit', 'cancel']);

// 編集モードかどうか
const isEdit = computed(() => !!props.child);

// フォームの状態
const form = reactive<ChildFormType>({
  name: '',
  birth_date: '',
  gender: null,
  notes: null
});

// 初期値の設定
onMounted(() => {
  if (props.child) {
    form.name = props.child.name;
    form.birth_date = props.child.birth_date;
    form.gender = props.child.gender;
    form.notes = props.child.notes;
  }
});

// フォーム送信
const handleSubmit = () => {
  emit('submit', { ...form });
};
</script>

<style>

</style>