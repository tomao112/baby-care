<template>
  <form @submit.prevent="handleSubmit" class="child-form">
    <div class="form-group">
      <label for="name">お名前 <span class="required">*</span></label>
      <div class="input-wrapper">
        <input 
          v-model="form.name" 
          type="text" 
          id="name" 
          required 
          :disabled="loading"
          placeholder="お子様のお名前を入力"
        >
      </div>
    </div>
    
    <div class="form-group">
      <label for="birth_date">生年月日 <span class="required">*</span></label>
      <div class="input-wrapper">
        <input 
          v-model="form.birth_date" 
          type="date" 
          id="birth_date" 
          required 
          :disabled="loading"
        >
      </div>
    </div>
    
    <div class="form-group">
      <label for="gender">性別</label>
      <div class="input-wrapper">
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
        <div class="select-arrow">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label for="notes">メモ</label>
      <div class="input-wrapper">
        <textarea 
          v-model="form.notes" 
          id="notes" 
          rows="3" 
          :disabled="loading"
          placeholder="お子様に関する特記事項があれば入力してください"
        ></textarea>
      </div>
    </div>
    
    <div class="form-actions">
      <button 
        type="button" 
        class="btn-secondary" 
        @click="$emit('cancel')" 
        :disabled="loading"
      >
        <span>キャンセル</span>
      </button>
      <button 
        type="submit" 
        class="btn-primary" 
        :disabled="loading"
      >
        <span v-if="loading" class="spinner-small"></span>
        <span>{{ loading ? '保存中...' : isEdit ? '更新する' : '登録する' }}</span>
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

<style scoped>
.child-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-size: 0.95rem;
  font-weight: 600;
  color: #4a4a68;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.required {
  color: #ff6b6b;
  font-weight: bold;
}

.input-wrapper {
  position: relative;
}

input, select, textarea {
  width: 100%;
  padding: 0.8rem 1rem;
  border: 1px solid #e6e6fa;
  border-radius: 0.8rem;
  background-color: #f8f9ff;
  font-size: 1rem;
  color: #4a4a68;
  transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #6a5acd;
  box-shadow: 0 0 0 3px rgba(106, 90, 205, 0.2);
  background-color: #fff;
}

input:disabled, select:disabled, textarea:disabled {
  background-color: #f0f0fa;
  cursor: not-allowed;
  opacity: 0.7;
}

input::placeholder, textarea::placeholder {
  color: #b0b0c0;
}

select {
  appearance: none;
  padding-right: 2.5rem;
  cursor: pointer;
}

.select-arrow {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: #8a8aa8;
}

textarea {
  resize: vertical;
  min-height: 6rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.btn-secondary, .btn-primary {
  padding: 0.8rem 1.5rem;
  border-radius: 2rem;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  min-width: 8rem;
}

.btn-secondary {
  background: linear-gradient(135deg, #f0f0fa, #e6e6fa);
  color: #6a5acd;
  border: none;
}

.btn-secondary:hover:not(:disabled) {
  background: linear-gradient(135deg, #e6e6fa, #d8d8f0);
  transform: translateY(-0.2rem);
}

.btn-primary {
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  border: none;
  position: relative;
  overflow: hidden;
}

.btn-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #5a4abf, #4a3aaf);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.4);
}

.btn-primary:hover:not(:disabled)::before {
  left: 100%;
}

.btn-secondary:disabled, .btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.spinner-small {
  width: 1rem;
  height: 1rem;
  border: 0.15rem solid rgba(255, 255, 255, 0.3);
  border-top: 0.15rem solid #fff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 48rem) {
  .form-actions {
    flex-direction: column-reverse;
  }
  
  .btn-secondary, .btn-primary {
    width: 100%;
  }
}
</style>