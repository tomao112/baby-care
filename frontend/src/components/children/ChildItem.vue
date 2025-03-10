<template>
  <div>
    <div>
      <h3>{{ child.name }}</h3>
      <div>
        <button @click="$emit('edit', child)">編集</button>
        <button @click="$emit('delete', child)">削除</button>
      </div>
    </div>
    <div>
      <div>
        <div>生年月日：</div>
        <div>{{ formatDate(child.birth_date) }}</div>
      </div>
      <div>
        <div>年齢：</div>
        <div>{{ calculateAge(child.birth_date) }}</div>
      </div>
      <div>
        <div>性別：</div>
        <div>{{ formatGender(child.gender) }}</div>
      </div>
    </div>
    <div>
      <router-link to="`/children\${child.id}">詳細を見る</router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Child } from '@/types';

defineProps<{
  child: Child;
}>();

defineEmits(['edit', 'delete']);

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