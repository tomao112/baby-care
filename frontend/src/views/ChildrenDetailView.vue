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
            <router-link v-if="child?.id" :to="{ name: 'growth-Record', params: { id: child.id.toString() }}" class="">
              <button class="add-record-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>記録を追加</span>
              </button>
            </router-link>
            
            <router-link v-if="child?.id" :to="{ name: 'growth-Record', params: { id: child.id.toString() }}" class="view-records-button">
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
            
            <router-link v-if="child?.id" :to="{ name: 'growth-Record', params: { id: child.id.toString() }}" class="mobile-view-records-button">
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
          <div class="month-selector">
            <button class="month-button" @click="previousMonth">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
              </svg>
            </button>
            <span class="current-month">{{ currentYear }}年{{ currentMonth }}月</span>
            <button class="month-button" @click="nextMonth">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="card-body calendar-container">
          <!-- 曜日の表示 -->
          <div class="calendar-header">
            <div class="weekday" v-for="weekday in ['日', '月', '火', '水', '木', '金', '土']" :key="weekday">
              {{ weekday }}
            </div>
          </div>
          
          <!-- カレンダーグリッド -->
          <div class="calendar-grid">
            <button 
              v-for="day in calendarDays" 
              :key="day.date"
              class="calendar-day"
              :class="{
                'today': day.isToday,
                'other-month': !day.isCurrentMonth,
                'has-poop': hasEventType(day, 'poop'),
                'has-food': hasEventType(day, 'food'),
                'has-milestone': hasEventType(day, 'milestone')
              }"
              @click="openEventDialog(day)"
            >
              <span class="day-number">{{ day.dayNumber }}</span>
              <div class="event-icons" v-if="day.events && day.events.length">
                <template v-if="day.events.length <= 5">
                  <span 
                    v-for="(event, index) in day.events" 
                    :key="`${day.date}-${event.type}-${index}`" 
                    class="event-icon"
                    :class="`event-${event.type}`"
                    :title="event.comment"
                  >
                    {{ getEventIcon(event.type) }}
                  </span>
                </template>
                <template v-else>
                  <span 
                    v-for="(event, index) in day.events.slice(0, 3)" 
                    :key="`${day.date}-${event.type}-${index}`" 
                    class="event-icon"
                    :class="`event-${event.type}`"
                    :title="event.comment"
                  >
                    {{ getEventIcon(event.type) }}
                  </span>
                  <span class="event-icon more-events" :title="`他${day.events.length - 3}件のイベント`">
                    +{{ day.events.length - 3 }}
                  </span>
                </template>
              </div>
            </button>
          </div>
        </div>

        <!-- イベント追加/編集ダイアログ -->
        <div class="event-dialog" v-if="showEventDialog" @click.self="closeEventDialog">
          <div class="dialog-content" @click.stop>
            <h4 class="dialog-title">{{ formatSelectedDate(selectedDate) }} の記録</h4>
            
            <!-- イベント一覧表示（イベントが存在する場合） -->
            <div v-if="selectedDayEvents && selectedDayEvents.length > 0" class="event-list">
              <div v-for="(event, index) in selectedDayEvents" :key="index" class="event-item">
                <div class="event-item-header">
                  <div class="event-item-left">
                    <div class="event-item-icon">{{ getEventIcon(event.type) }}</div>
                    <div class="event-item-type">{{ getEventLabel(event.type) }}</div>
                  </div>
                  <button @click="deleteEvent(event, index)" class="delete-event-button" title="削除">
                    <span class="delete-icon">×</span>
                  </button>
                </div>
                <div class="event-item-comment">{{ event.comment || 'コメントなし' }}</div>
              </div>
            </div>
            
            <!-- 新規追加フォーム -->
            <div class="add-event-form">
              <h5 class="form-subtitle">新しい記録を追加</h5>
              
              <div class="event-type-selector">
                <button 
                  v-for="type in eventTypes" 
                  :key="type.id"
                  class="event-type-button"
                  :class="{ active: selectedEventType === type.id }"
                  @click="selectedEventType = type.id"
                >
                  <span class="event-icon">{{ type.icon }}</span>
                  <span class="event-label">{{ type.label }}</span>
                </button>
              </div>

              <div class="comment-input">
                <label for="eventComment">コメント</label>
                <input 
                  type="text" 
                  id="eventComment"
                  v-model="eventComment"
                  placeholder="コメントを入力（任意）"
                >
              </div>

              <div class="dialog-actions">
                <button class="cancel-button" @click="closeEventDialog">キャンセル</button>
                <button class="save-button" @click="saveEvent" :disabled="!selectedEventType">
                  保存
                </button>
              </div>
            </div>
          </div>
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
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useChildrenStore } from '@/stores/children';
import { Child, ChildsForm, DailyRecord } from '@/types';
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
const dailyRecords = ref<DailyRecord[] | null>([]);
// イベントの種類
const eventTypes = [
  { id: 'poop', icon: '💩', label: 'うんち' },
  { id: 'milestone', icon: '🎉', label: '記念日' },
  { id: 'food', icon: '🍼', label: '離乳食' }
];

// 状態管理
const showEventDialog = ref(false);
const selectedDate = ref('');
const selectedEventType = ref('');
const eventComment = ref('');

// 現在の年月を管理する
const currentDate = ref(new Date());
const currentYear = computed(() => currentDate.value.getFullYear());
const currentMonth = computed(() => currentDate.value.getMonth() + 1);

// 型定義を追加
interface CalendarEvent {
  type: string;
  comment: string;
}

interface CalendarDay {
  date: string;
  dayNumber: string | number;
  isCurrentMonth: boolean;
  isToday?: boolean;
  hasEvent?: boolean;
  events: CalendarEvent[];
}

// 選択された日のイベント
const selectedDayEvents = ref<CalendarEvent[]>([]);

// イベントタイプからラベルを取得
const getEventLabel = (type: string): string => {
  const eventType = eventTypes.find(t => t.id === type);
  return eventType ? eventType.label : '不明なイベント';
};

// イベントダイアログを開く関数を修正
const openEventDialog = (day: CalendarDay) => {
  if (!day.isCurrentMonth) return;
  selectedDate.value = day.date;
  
  // その日のイベントをセット
  selectedDayEvents.value = day.events || [];
  
  // 新規追加用のフォームをリセット
  selectedEventType.value = '';
  eventComment.value = '';
  
  showEventDialog.value = true;
};

// イベントを削除する関数
const deleteEvent = async (event: CalendarEvent, index: number) => {
  if (!confirm(`${getEventLabel(event.type)}の記録を削除しますか？`)) {
    return;
  }
  
  try {
    // APIを使って実際に削除する
    // 注意: ここではAPIから返された記録IDが必要です
    // 現状の実装では各イベントにIDがないため、実際の環境では調整が必要です
    // 例: selectedDayEvents.value[index].id を使うなど
    
    // 仮の実装: 現在の選択日の記録をすべて取得してインデックスで削除
    if (child.value) {
      // その日付の記録を取得
      const dateStr = selectedDate.value;
      const response = await childrenStore.fetchDailyRecordByMonth(
        child.value.id.toString(),
        parseInt(dateStr.split('-')[0]), // 年
        parseInt(dateStr.split('-')[1])  // 月
      );
      
      // 同じ日付の記録をフィルタリング
      const dailyRecords = response.data.filter(
        record => record.record_date.startsWith(dateStr)
      );
      
      if (dailyRecords.length > index) {
        // インデックスに一致する記録を削除
        const recordToDelete = dailyRecords[index];
        await childrenStore.deleteDailyRecord(recordToDelete.id);
        console.log('記録を削除しました:', recordToDelete);
      }
    }
    
    // 画面表示上で削除
    selectedDayEvents.value.splice(index, 1);
    
    // calendarDaysの該当日からも削除
    const currentCalendarDays = calendarDays.value;
    const selectedDayInCalendar = currentCalendarDays.find(day => day.date === selectedDate.value);
    if (selectedDayInCalendar) {
      // 該当のイベントを削除（インデックスではなく、内容で削除）
      selectedDayInCalendar.events = selectedDayInCalendar.events.filter((e, i) => i !== index);
      selectedDayInCalendar.hasEvent = selectedDayInCalendar.events.length > 0;
    }
    
    // 削除後、イベントがなくなったら自動的にダイアログを閉じる
    if (selectedDayEvents.value.length === 0) {
      closeEventDialog();
    }
  } catch (err) {
    console.error('イベントの削除に失敗しました:', err);
    alert('削除できませんでした。もう一度試してください。');
  }
};

// イベントを保存
const saveEvent = async () => {
  if (!selectedEventType.value) {
    return; // イベントタイプが選択されていない場合は処理しない
  }
  
  try {
    // 新しいイベントオブジェクト
    const newEvent: CalendarEvent = {
      type: selectedEventType.value,
      comment: eventComment.value || ''
    };
    
    // APIを使って実際に保存する
    if (child.value) {
      // スタンプIDをタイプから逆引き
      const stampIdMap: Record<string, number> = {
        'poop': 1,     // うんち
        'milestone': 2, // 記念日
        'food': 3,     // 離乳食
      };
      
      const stampId = stampIdMap[selectedEventType.value];
      
      // APIリクエスト用データ
      const eventData = {
        record_date: selectedDate.value,
        daily_event_stamps_id: stampId,
        comment: eventComment.value,
        record_time: new Date().toTimeString().slice(0, 8), // 現在時刻を追加 (HH:MM:SS)
        child_id: parseInt(child.value.id.toString()) // 数値型のchild_idを明示的に追加
      };
      
      // 保存API呼び出し
      const response = await childrenStore.createDailyRecord(
        child.value.id.toString(),
        eventData
      );
      
      console.log('保存成功:', response.data);
      
      // 保存が成功したら、すでに表示されているイベントをクリアしてから追加
      // 同じ日のイベントが重複して表示されないようにする
      const currentCalendarDays = calendarDays.value;
      const selectedDayInCalendar = currentCalendarDays.find(day => day.date === selectedDate.value);
      
      if (selectedDayInCalendar) {
        // その日の同じタイプのイベントをフィルタリング（重複を防ぐ）
        selectedDayInCalendar.events = selectedDayInCalendar.events.filter(
          event => event.type !== selectedEventType.value
        );
        
        // 新しいイベントを追加
        selectedDayInCalendar.events.push(newEvent);
        selectedDayInCalendar.hasEvent = true;
        
        // 選択中の日のイベントも同様に更新
        selectedDayEvents.value = selectedDayEvents.value.filter(
          event => event.type !== selectedEventType.value
        );
        selectedDayEvents.value.push(newEvent);
      }
    
      // フォームをリセット（ダイアログは閉じない）
      selectedEventType.value = '';
      eventComment.value = '';
    }
  } catch (err) {
    console.error('イベントの保存に失敗しました:', err);
    alert('保存できませんでした。もう一度試してください。');
  }
};

// 前月へ移動
const previousMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
};

// 翌月へ移動
const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
};

// カレンダーの日付生成を修正
const calendarDays = computed(() => {
  const days: CalendarDay[] = [];
  const firstDay = new Date(currentYear.value, currentMonth.value - 1, 1);
  const lastDay = new Date(currentYear.value, currentMonth.value, 0);
  
  // 育児記録を日付ごとにグループ化
  const recordsByDate: Record<string, CalendarEvent[]> = {};
  
  console.log('育児記録データ:', dailyRecords.value); // デバッグ用
  
  if (dailyRecords.value && dailyRecords.value.length) {
    dailyRecords.value.forEach(record => {
      const recordDate = record.record_date.substring(0, 10); // YYYY-MM-DD 形式に変換
      console.log('記録日:', recordDate, 'イベントID:', record.daily_event_stamps_id); // 修正
      
      if (!recordsByDate[recordDate]) {
        recordsByDate[recordDate] = [];
      }
      
      // 記録タイプに基づいてイベントを作成
      const eventType = getEventTypeFromId(record.daily_event_stamps_id); // 修正
      console.log('変換後のイベントタイプ:', eventType);
      
      recordsByDate[recordDate].push({
        type: eventType,
        comment: record.comment || ''
      });
    });
  }
  
  console.log('日付ごとのイベント:', recordsByDate);
  
  // 月の最初の日の曜日まで空のセルを追加
  for (let i = 0; i < firstDay.getDay(); i++) {
    days.push({
      date: '',
      dayNumber: '',
      isCurrentMonth: false,
      hasEvent: false,
      events: []
    });
  }
  
  // 月の日付を追加
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const date = `${currentYear.value}-${String(currentMonth.value).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
    const dayEvents = recordsByDate[date] || [];
    
    days.push({
      date: date,
      dayNumber: i,
      isCurrentMonth: true,
      isToday: isToday(new Date(date)),
      hasEvent: dayEvents.length > 0,
      events: dayEvents
    });
  }
  
  return days;
});

// イベントタイプをIDから取得する関数を修正
const getEventTypeFromId = (stampId: number): string => {
  // ログでデバッグ
  console.log('stampId:', stampId);
  
  // DBのIDに基づいてイベントタイプを設定
  const typeMap: Record<number, string> = {
    1: 'poop',     // うんち
    2: 'milestone', // 記念日
    3: 'food',     // 離乳食
  };
  
  const type = typeMap[stampId];
  if (!type) {
    console.warn('未知のスタンプID:', stampId);
    return 'milestone'; // デフォルト値
  }
  return type;
};

// 今日の日付かどうかをチェックする関数
const isToday = (date: Date): boolean => {
  const today = new Date();
  return date.getDate() === today.getDate() &&
         date.getMonth() === today.getMonth() &&
         date.getFullYear() === today.getFullYear();
};

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

  try {
    const response = await childrenStore.fetchDailyRecord(childId.toString());
    dailyRecords.value = response.data;
    console.log('取得した育児記録:', response.data); // デバッグ用
  } catch(err: any) {
    console.error('育児記録の取得に失敗しました:', err);
    error.value = err.response?.data?.message || 'データの取得に失敗しました';
  } finally {
    loading.value = false;
  }
});

// 子供の更新
const updateChild = async (formData: ChildsForm) => {
  if(!child.value || !child.value.id) {
    console.error('子供のIDが見つかりません');
    alert('子供のIDが見つかりません。ページを再読み込みしてください。');
    return;
  }

  const childId = child.value.id.toString();
  console.log(`子供ID: ${childId} の情報を更新します`, formData);

  formLoading.value = true;
  try {
    const response = await childrenStore.updateChild(childId, formData);
    
    // レスポンスデータ処理
    if (response.data && Object.keys(response.data).length > 0) {
      child.value = response.data;
    } else {
      // レスポンスが空の場合はフォームデータで更新
      child.value = {
        ...child.value,
        ...formData
      };
    }
    
    console.log('更新後の子供データ:', child.value);
    showEditForm.value = false;
  } catch(err: any) {
    console.error('子供の情報の更新に失敗しました:', err);
    alert(err.response?.data?.message || '更新に失敗しました。もう一度お試しください。');
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

// イベントアイコンを取得
const getEventIcon = (type: string) => {
  console.log('getEventIcon 呼び出し:', type);
  
  // すべてのイベントタイプを表示
  console.log('利用可能なイベントタイプ:', eventTypes.map(t => t.id));
  
  const eventType = eventTypes.find(t => t.id === type);
  
  if (eventType) {
    return eventType.icon;
  } else {
    // 未知のタイプの場合はデフォルトアイコンを返す
    console.warn('未知のイベントタイプ:', type);
    return '📋'; // デフォルトアイコン
  }
};

// イベントダイアログを閉じる
const closeEventDialog = () => {
  showEventDialog.value = false;
  selectedEventType.value = '';
  eventComment.value = '';
};

// currentDate が変わったらデータを再取得
watch([currentYear, currentMonth], async () => {
  if (!child.value) return;
  
  try {
    // 年月を指定して育児記録を取得
    const response = await childrenStore.fetchDailyRecordByMonth(
      child.value.id.toString(), // 文字列に変換
      currentYear.value,
      currentMonth.value
    );
    dailyRecords.value = response.data;
  } catch (err: any) {
    console.error('育児記録の取得に失敗しました:', err);
  }
});

// イベントタイプがあるかチェックする関数を追加
const hasEventType = (day: CalendarDay, type: string): boolean => {
  return day.events && day.events.some(event => event.type === type);
};

// イベントタイプのカウントを取得する関数（必要に応じて）
const countEventType = (day: CalendarDay, type: string): number => {
  if (!day.events) return 0;
  return day.events.filter(event => event.type === type).length;
};

// 選択された日付を読みやすい形式に変換する関数
const formatSelectedDate = (dateStr: string): string => {
  if (!dateStr) return '';
  
  const date = new Date(dateStr);
  const year = date.getFullYear();
  const month = date.getMonth() + 1;
  const day = date.getDate();
  
  // 曜日の配列
  const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
  const weekday = weekdays[date.getDay()];
  
  return `${year}年${month}月${day}日(${weekday})`;
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
  background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
}

.section-title {
  display: flex;
  align-items: center;
  font-size: 1.3rem;
  font-weight: 700;
  color: #6a5acd;
  margin: 0;
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -0.3rem;
  left: 0;
  width: 2rem;
  height: 0.2rem;
  background: linear-gradient(90deg, #6a5acd, transparent);
  border-radius: 1rem;
}

.section-title svg {
  margin-right: 0.8rem;
  color: #6a5acd;
  filter: drop-shadow(0 1px 2px rgba(106, 90, 205, 0.3));
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

.calendar-container {
  padding: 1.5rem;
  margin-bottom: 2rem;
  background: linear-gradient(135deg, #fff8fd, #f8faff);
  border-radius: 1.5rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  border: 1px solid #f0e6ff;
}

.calendar-header {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  text-align: center;
  font-weight: bold;
  margin-bottom: 1rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 0.8rem;
  padding: 0.5rem 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.weekday {
  padding: 0.5rem;
  color: #666;
  font-size: 0.9rem;
  font-weight: 700;
  position: relative;
}

.weekday:first-child {
  color: #ff6b6b;
}

.weekday:last-child {
  color: #4dabf7;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.8rem;
  padding: 0.5rem;
}

.calendar-day {
  aspect-ratio: 1;
  border: 2px solid #eef2ff;
  border-radius: 1rem;
  padding: 0.5rem;
  background: white;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  position: relative;
}

.calendar-day:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(106, 90, 205, 0.15);
  z-index: 1;
}

.day-number {
  font-size: 1.1rem;
  font-weight: 700;
  color: #4a4a68;
  margin-bottom: 0.4rem;
  background: rgba(255, 255, 255, 0.6);
  width: 1.8rem;
  height: 1.8rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.calendar-day:hover .day-number {
  background: rgba(255, 255, 255, 0.8);
  color: #6a5acd;
}

.calendar-day.today {
  border-width: 3px;
  border-color: #6a5acd;
  box-shadow: 0 4px 12px rgba(106, 90, 205, 0.2);
  transform: scale(1.03);
  z-index: 2;
}

.calendar-day.today .day-number {
  background: rgba(106, 90, 205, 0.15);
  color: #6a5acd;
  font-weight: 800;
}

.calendar-grid .calendar-day:nth-child(7n+1) .day-number {
  color: #ff6b6b;
}

.calendar-grid .calendar-day:nth-child(7n) .day-number {
  color: #4dabf7;
}

.event-icons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.2rem;
  margin-top: 0.3rem;
  justify-content: center;
  max-width: 100%;
  background: rgba(255, 255, 255, 0.5);
  border-radius: 0.5rem;
  padding: 0.2rem;
  transition: all 0.3s ease;
}

.calendar-day:hover .event-icons {
  background: rgba(255, 255, 255, 0.7);
  transform: scale(1.05);
}

.event-icon {
  font-size: 1rem;
  transition: all 0.2s ease;
  display: inline-block;
  margin: 0.05rem;
  position: relative;
  opacity: 0.85;
}

.event-icon.event-poop {
  filter: drop-shadow(0 1px 2px rgba(255, 192, 0, 0.2));
}

.event-icon.event-food {
  filter: drop-shadow(0 1px 2px rgba(0, 160, 255, 0.2));
}

.event-icon.event-milestone {
  filter: drop-shadow(0 1px 2px rgba(128, 210, 128, 0.3));
}

.more-events {
  background: rgba(106, 90, 205, 0.2);
  border-radius: 50%;
  padding: 0.1rem 0.3rem;
  font-size: 0.75rem;
  color: #6a5acd;
  font-weight: bold;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.month-selector {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: white;
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  box-shadow: 0 3px 8px rgba(106, 90, 205, 0.1);
  border: 1px solid #f0e6ff;
}

.month-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.2rem;
  height: 2.2rem;
  border: 1px solid #f0e6ff;
  border-radius: 50%;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #6a5acd;
}

.month-button:hover {
  background: linear-gradient(135deg, #f0f2ff, #e6e9ff);
  border-color: #6a5acd;
  color: #6a5acd;
}

.current-month {
  font-size: 1.2rem;
  font-weight: 700;
  color: #4a4a68;
  min-width: 8rem;
  text-align: center;
  background: linear-gradient(90deg, #6a5acd, #9370db);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

@media (max-width: 768px) {
  .calendar-container {
    padding: 1rem;
    border-radius: 1rem;
  }

  .calendar-grid {
    gap: 0.4rem;
  }

  .calendar-day {
    padding: 0.3rem;
    border-radius: 0.8rem;
    border-width: 1px;
  }

  .day-number {
    font-size: 0.9rem;
    width: 1.5rem;
    height: 1.5rem;
    margin-bottom: 0.2rem;
  }

  .event-icons {
    padding: 0.1rem;
    gap: 0.1rem;
  }

  .event-icon {
    font-size: 0.8rem;
  }
  
  .calendar-day.today {
    border-width: 2px;
  }
  
  .month-selector {
    padding: 0.4rem 0.8rem;
  }
  
  .month-button {
    width: 1.8rem;
    height: 1.8rem;
  }
  
  .current-month {
    font-size: 1rem;
    min-width: 6rem;
  }
}

.event-list {
  margin-bottom: 1.5rem;
  max-height: 30vh;
  overflow-y: auto;
  border-radius: 0.5rem;
  background-color: #f8f9ff;
  padding: 0.6rem;
  border: 1px solid #eef0ff;
}

.event-item {
  background-color: white;
  border-radius: 0.5rem;
  padding: 0.8rem;
  margin-bottom: 0.8rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  border-left: 3px solid #eef0ff;
}

.event-item:hover {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.event-item-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.event-item-left {
  display: flex;
  align-items: center;
}

.event-item-icon {
  font-size: 1.5rem;
  margin-right: 0.8rem;
}

.event-item-type {
  font-weight: 700;
  font-size: 1.1rem;
  color: #4a4a68;
}

.event-item-comment {
  color: #666;
  font-size: 0.9rem;
  background-color: #f8f9ff;
  padding: 0.5rem;
  border-radius: 0.3rem;
  white-space: pre-line;
}

.delete-event-button {
  background: none;
  border: none;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #ccc;
  transition: all 0.2s ease;
}

.delete-event-button:hover {
  background-color: #fee;
  color: #f66;
}

.delete-icon {
  font-size: 1.2rem;
  font-weight: bold;
}

.form-subtitle {
  font-size: 1rem;
  color: #4a4a68;
  margin: 0 0 1rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #eee;
}

.add-event-form {
  margin-top: 1rem;
}

.save-button:disabled {
  background: #d0d0e0;
  color: #a0a0b0;
  cursor: not-allowed;
}

.dialog-content {
  background: white;
  border-radius: 0.8rem;
  padding: 1.2rem;
  width: 90%;
  max-width: 500px;
  max-height: 70vh;
  overflow-y: auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.dialog-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #4a4a68;
  margin: 0 0 1rem 0;
  padding-bottom: 0.6rem;
  border-bottom: 1px solid #eee;
}

.event-type-selector {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.6rem;
  margin-bottom: 1rem;
}

.event-type-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.3rem;
  padding: 0.7rem 0.5rem;
  border: 1px solid #eee;
  border-radius: 0.5rem;
  background: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.event-type-button:nth-child(1) {
  background-color: #fffdf8;
  border-color: #ffe0a0;
}

.event-type-button:nth-child(2) {
  background-color: #f5fff8;
  border-color: #a0e0a0;
}

.event-type-button:nth-child(3) {
  background-color: #f8fdff;
  border-color: #c0e8ff;
}

.event-type-button.active {
  border-width: 2px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.event-type-button:nth-child(1).active {
  background-color: #fffaf0;
  border-color: #ffd080;
  box-shadow: 0 2px 4px rgba(255, 208, 128, 0.15);
}

.event-type-button:nth-child(2).active {
  background-color: #efffef;
  border-color: #a0e0a0;
  box-shadow: 0 2px 4px rgba(128, 210, 128, 0.2);
}

.event-type-button:nth-child(3).active {
  background-color: #f0f8ff;
  border-color: #80cfff;
  box-shadow: 0 2px 4px rgba(128, 207, 255, 0.15);
}

.event-type-button .event-icon {
  font-size: 1.8rem;
  margin-bottom: 0.2rem;
}

.comment-input {
  margin-bottom: 1.2rem;
}

.comment-input label {
  display: block;
  margin-bottom: 0.4rem;
  color: #4a4a68;
  font-size: 0.9rem;
  font-weight: 500;
}

.comment-input input {
  width: 100%;
  padding: 0.7rem 0.8rem;
  border: 1px solid #eef0ff;
  border-radius: 0.5rem;
  font-size: 0.95rem;
}

.comment-input input:focus {
  outline: none;
  border-color: #6a5acd;
  box-shadow: 0 0 0 2px rgba(106, 90, 205, 0.1);
}

.dialog-actions {
  display: flex;
  justify-content: space-between;
  gap: 0.6rem;
}

.cancel-button, .save-button {
  flex: 1;
  padding: 0.7rem 0.8rem;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
}

.cancel-button {
  background: #f5f5fa;
  border: 1px solid #e0e0ea;
  color: #6a6a8a;
}

.save-button {
  background: #6a5acd;
  border: none;
  color: white;
}

.save-button:hover:not(:disabled) {
  background: #5a4abf;
}

.event-dialog {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.calendar-day.has-poop:not(.has-food):not(.has-milestone) {
  background: linear-gradient(135deg, #fffdf5, #fffaf0);
  border-color: #ffda80;
  box-shadow: 0 2px 5px rgba(255, 208, 128, 0.2);
}

.calendar-day.has-milestone:not(.has-food):not(.has-poop) {
  background: linear-gradient(135deg, #f5fff8, #efffef);
  border-color: #a0e0a0;
  box-shadow: 0 2px 5px rgba(128, 210, 128, 0.2);
}

.calendar-day.has-food:not(.has-poop):not(.has-milestone) {
  background: linear-gradient(135deg, #f0f8ff, #e6f4ff);
  border-color: #a0d0ff;
  box-shadow: 0 2px 5px rgba(128, 207, 255, 0.2);
}

.calendar-day.has-poop.has-food,
.calendar-day.has-poop.has-milestone,
.calendar-day.has-food.has-milestone,
.calendar-day.has-poop.has-food.has-milestone {
  background: #fff0f5;
  border-color: #ffb0c8;
  border-width: 2px;
  box-shadow: 0 2px 5px rgba(255, 176, 200, 0.25);
}

.calendar-day.has-poop.has-food.has-milestone::before {
  content: none;
}

.event-icon.event-poop {
  filter: drop-shadow(0 1px 2px rgba(255, 192, 0, 0.2));
}

.event-icon.event-food {
  filter: drop-shadow(0 1px 2px rgba(0, 160, 255, 0.2));
}

.event-icon.event-milestone {
  filter: drop-shadow(0 1px 2px rgba(128, 210, 128, 0.3));
}
</style>