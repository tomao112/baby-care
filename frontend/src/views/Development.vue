<template>
  <div class="development-container">
    <!-- ヘッダー部分 -->
    <div class="development-header">
      <h2 class="title">発達記録</h2>
      <div class="month-selector">
        <button class="month-button">
          <i class="fas fa-chevron-left"></i>
        </button>
        <span class="current-month">2024年3月</span>
        <button class="month-button">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>

    <!-- カレンダー部分 -->
    <div class="calendar">
      <!-- 曜日の行 -->
      <div class="weekdays">
        <div class="weekday">日</div>
        <div class="weekday">月</div>
        <div class="weekday">火</div>
        <div class="weekday">水</div>
        <div class="weekday">木</div>
        <div class="weekday">金</div>
        <div class="weekday">土</div>
      </div>

      <!-- カレンダーのグリッド -->
      <div class="calendar-grid">
        <!-- サンプルの日付セル -->
        <div class="date-cell">
          <span class="date">1</span>
          <div class="event-icons">
            <div class="event-icon poop" title="うんち">💩</div>
            <div class="event-icon milestone" title="記念日：はじめて笑った">🎉</div>
          </div>
        </div>
        <!-- 他の日付セルのサンプル -->
        <div class="date-cell">
          <span class="date">2</span>
          <div class="event-icons">
            <div class="event-icon meal" title="離乳食開始">🍼</div>
          </div>
        </div>
        <!-- 残りの日付セル（サンプル用に数個だけ表示） -->
        <div class="date-cell"><span class="date">3</span></div>
        <div class="date-cell"><span class="date">4</span></div>
        <div class="date-cell"><span class="date">5</span></div>
        <!-- ... 他の日付セル ... -->
      </div>
    </div>

    <!-- ツールチップ（ホバー時の詳細表示） -->
    <div class="tooltip" style="display: none;">
      <h4 class="tooltip-date">2024年3月1日</h4>
      <div class="tooltip-content">
        <p><span class="icon">💩</span> うんち：午前10:30</p>
        <p><span class="icon">🎉</span> 記念日：はじめて笑った</p>
      </div>
    </div>

    <!-- 凡例 -->
    <div class="legend">
      <div class="legend-item">
        <span class="icon">💩</span> うんち
      </div>
      <div class="legend-item">
        <span class="icon">🎉</span> 記念日
      </div>
      <div class="legend-item">
        <span class="icon">🍼</span> 離乳食
      </div>
    </div>
  </div>
</template>

<style scoped>
.development-container {
  padding: 20px;
  max-width: 1000px;
  margin: 0 auto;
}

.development-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.title {
  font-size: 1.5rem;
  color: #333;
}

.month-selector {
  display: flex;
  align-items: center;
  gap: 15px;
}

.month-button {
  background: none;
  border: 1px solid #ddd;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.month-button:hover {
  background-color: #f0f0f0;
}

.current-month {
  font-size: 1.2rem;
  font-weight: bold;
  min-width: 120px;
  text-align: center;
}

.calendar {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.weekdays {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  background-color: #f8f9fa;
  border-bottom: 1px solid #eee;
}

.weekday {
  padding: 10px;
  text-align: center;
  font-weight: bold;
  color: #666;
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
  gap: 1px;
  background-color: #eee;
}

.date-cell {
  background: white;
  min-height: 100px;
  padding: 5px;
  position: relative;
}

.date {
  font-size: 0.9rem;
  color: #333;
  display: block;
  margin-bottom: 5px;
}

.event-icons {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
}

.event-icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-size: 1rem;
  cursor: pointer;
  transition: transform 0.2s;
}

.event-icon:hover {
  transform: scale(1.2);
}

.tooltip {
  position: absolute;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
  padding: 12px;
  min-width: 200px;
  z-index: 1000;
}

.tooltip-date {
  margin: 0 0 8px 0;
  color: #333;
  font-size: 0.9rem;
}

.tooltip-content {
  font-size: 0.85rem;
}

.tooltip-content p {
  margin: 4px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.legend {
  margin-top: 20px;
  display: flex;
  gap: 20px;
  justify-content: center;
  padding: 10px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  color: #666;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .calendar-grid {
    gap: 0;
  }

  .date-cell {
    min-height: 80px;
    font-size: 0.8rem;
  }

  .event-icon {
    width: 20px;
    height: 20px;
    font-size: 0.9rem;
  }

  .legend {
    flex-wrap: wrap;
  }
}
</style>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

// propsの定義
interface Props {
  childId?: number;
}

const props = defineProps<Props>();

// ツールチップの表示制御
const showTooltip = (event: MouseEvent, content: string) => {
  const tooltip = document.querySelector('.tooltip') as HTMLElement;
  if (tooltip) {
    tooltip.style.display = 'block';
    tooltip.style.left = `${event.pageX + 10}px`;
    tooltip.style.top = `${event.pageY + 10}px`;
  }
};

// ツールチップの非表示
const hideTooltip = () => {
  const tooltip = document.querySelector('.tooltip') as HTMLElement;
  if (tooltip) {
    tooltip.style.display = 'none';
  }
};

onMounted(() => {
  console.log('Child ID:', props.childId);
});
</script> 