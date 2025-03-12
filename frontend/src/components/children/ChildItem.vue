<template>
  <div class="child-card">
    <div class="card-header">
      <div class="child-avatar" :class="getGenderClass(child.gender)">
        <span class="avatar-text">{{ getInitial(child.name) }}</span>
      </div>
      <div class="child-info">
        <h3 class="child-name">{{ child.name }}</h3>
        <div class="child-age">{{ calculateAge(child.birth_date) }}</div>
      </div>
      <div class="card-actions">
        <button @click="$emit('edit', child)" class="action-button edit-button" title="編集">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
        </button>
        <button @click="$emit('delete', child)" class="action-button delete-button" title="削除">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <div class="card-body">
      <div class="info-row">
        <div class="info-label">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <span>生年月日</span>
        </div>
        <div class="info-value">{{ formatDate(child.birth_date) }}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span>性別</span>
        </div>
        <div class="info-value">
          <span class="gender-badge" :class="getGenderClass(child.gender)">
            {{ formatGender(child.gender) }}
          </span>
        </div>
      </div>
    </div>
    
    <div class="card-footer">
      <router-link :to="{ name: 'child-detail', params: { id: child.id } }" class="detail-link">
        <span>詳細を見る</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14"></path>
          <path d="M12 5l7 7-7 7"></path>
        </svg>
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Child } from '@/types';

defineProps<{
  child: Child;
}>();

defineEmits(['edit', 'delete']);

// 名前のイニシャルを取得
const getInitial = (name: string): string => {
  return name ? name.charAt(0) : '?';
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
  return new Date(dateString).toLocaleDateString('ja-JP', {
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
.child-card {
  background-color: #ffffff;
  border-radius: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.child-card:hover {
  transform: translateY(-0.3rem);
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12);
}

.card-header {
  padding: 1.2rem;
  display: flex;
  align-items: center;
  position: relative;
  border-bottom: 1px solid #f0f0fa;
}

.child-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 600;
  color: white;
  margin-right: 1rem;
  flex-shrink: 0;
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

.child-info {
  flex: 1;
  min-width: 0;
}

.child-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: #4a4a68;
  margin: 0 0 0.2rem 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.child-age {
  font-size: 0.9rem;
  color: #8a8aa8;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
  margin-left: 0.5rem;
  flex-shrink: 0;
}

.action-button {
  background: none;
  border: none;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #8a8aa8;
  padding: 0;
  flex-shrink: 0;
}

.edit-button {
  background-color: rgba(106, 90, 205, 0.05);
}

.edit-button:hover {
  background-color: rgba(106, 90, 205, 0.15);
  color: #6a5acd;
  transform: translateY(-2px);
  box-shadow: 0 3px 5px rgba(106, 90, 205, 0.2);
}

.delete-button {
  background-color: rgba(255, 145, 145, 0.05);
}

.delete-button:hover {
  background-color: rgba(255, 145, 145, 0.15);
  color: #ff9191;
  transform: translateY(-2px);
  box-shadow: 0 3px 5px rgba(255, 145, 145, 0.2);
}

.card-body {
  padding: 1rem 1.2rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.6rem 0;
  border-bottom: 1px dashed #f0f0fa;
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  display: flex;
  align-items: center;
  color: #8a8aa8;
  font-size: 0.9rem;
  flex-shrink: 0;
  margin-right: 1rem;
}

.info-label svg {
  margin-right: 0.5rem;
  flex-shrink: 0;
}

.info-value {
  font-weight: 500;
  color: #4a4a68;
  text-align: right;
}

.gender-badge {
  display: inline-block;
  padding: 0.3rem 0.8rem;
  border-radius: 1rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
}

.card-footer {
  padding: 1rem 1.2rem;
  background-color: #f8f9ff;
  border-top: 1px solid #f0f0fa;
}

.detail-link {
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #6a5acd;
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  padding: 0.7rem;
  border-radius: 0.5rem;
  background-color: rgba(106, 90, 205, 0.05);
}

.detail-link:hover {
  background-color: rgba(106, 90, 205, 0.15);
  transform: translateY(-2px);
  box-shadow: 0 3px 5px rgba(106, 90, 205, 0.2);
}

.detail-link svg {
  margin-left: 0.5rem;
  transition: transform 0.3s ease;
  flex-shrink: 0;
}

.detail-link:hover svg {
  transform: translateX(0.3rem);
}
</style>