<template>
  <div class="child-detail-container animate__animated animate__fadeIn">
    <div class="detail-header">
      <div class="title-wrapper">
        <h2 class="page-title animate__animated animate__fadeInDown">{{ child?.name || 'お子さま' }}の成長記録</h2>
        <div class="title-decoration"></div>
      </div>
      <div class="header-actions animate__animated animate__fadeInRight">
        <router-link :to="`/children/${route.params.id}`" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5"></path>
            <path d="M12 19l-7-7 7-7"></path>
          </svg>
          <span>子供の詳細に戻る</span>
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="loading-container animate__animated animate__fadeIn">
      <div class="spinner"></div>
      <p>読み込み中<span class="dot-animation">...</span></p>
    </div>

    <div v-else-if="child" class="growth-records-container animate__animated animate__fadeIn">
      <!-- モード切替タブ -->
      <div class="mode-tabs">
        <button 
          class="mode-tab" 
          :class="{ active: activeMode === 'view' }" 
          @click="activeMode = 'view'"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <span>記録を閲覧</span>
        </button>
        <button 
          class="mode-tab" 
          :class="{ active: activeMode === 'add' }" 
          @click="activeMode = 'add'"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <span>記録を追加</span>
        </button>
      </div>

      <!-- 記録閲覧モード -->
      <div v-if="activeMode === 'view'" class="info-card growth-record-section">
        <div class="card-header">
          <h3 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            <span>成長記録</span>
          </h3>
        </div>
        
        <div class="card-body">
          <!-- タブナビゲーション -->
          <div class="growth-tabs">
            <button class="tab-button" :class="{ active: activeTab === 'physical' }" @click="activeTab = 'physical'">身体測定</button>
            <button class="tab-button" :class="{ active: activeTab === 'development' }" @click="activeTab = 'development'">発達</button>
            <button class="tab-button" :class="{ active: activeTab === 'checkup' }" @click="activeTab = 'checkup'">健診</button>
            <button class="tab-button" :class="{ active: activeTab === 'vaccine' }" @click="activeTab = 'vaccine'">予防接種</button>
          </div>
          
          <!-- 身体測定表示 -->
          <div v-if="activeTab === 'physical'">
            <!-- 身体測定グラフエリア -->
            <div class="growth-chart-area">
              <div class="chart-filters">
                <div class="filter-group">
                  <label class="filter-label">表示項目:</label>
                  <div class="filter-options">
                    <label class="checkbox-label">
                      <input type="checkbox" checked> 身長
                    </label>
                    <label class="checkbox-label">
                      <input type="checkbox" checked> 体重
                    </label>
                  </div>
                </div>
                <div class="filter-group">
                  <label class="filter-label">期間:</label>
                  <select class="period-select">
                    <option>すべて</option>
                    <option>1年</option>
                    <option>6ヶ月</option>
                    <option>3ヶ月</option>
                  </select>
                </div>
              </div>
              
              <div class="chart-container">
                <!-- グラフがここに表示される（実装時はChart.jsなど） -->
                <div class="placeholder-chart">
                  <div class="chart-placeholder-text">
                    <div class="chart-icon">📊</div>
                    <p>成長記録がここに表示されます</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- 記録リスト -->
            <div class="growth-records-list">
              <h4 class="records-title">記録履歴</h4>

              <!-- 記録がない場合のメッセージ -->
              <div v-if="!hasRecords && !visibleRecords.length" class="empty-records">
                <div class="empty-icon">📝</div>
                <p class="empty-title">まだ記録がありません</p>
                <p class="empty-description">「記録を追加」ボタンから記録を始めましょう</p>
                <button @click="openModal()" class="start-recording-button">
                  記録を始める
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
              </div>
              
              <!-- 月ごとのカード表示 -->
              <div class="record-cards">
                <!-- v-forのitemに明示的な型を付与することでTypeScriptエラーを解消 -->
                <div v-for="(record, index) in visibleRecords" :key="index" class="record-card" :class="{'empty-card': record.isEmpty}">
                  <div class="record-date">
                    <div class="date-bubble">{{ getMonthName(record.date || record.record_date) }}</div>
                    <div class="date-year">{{ getYear(record.date || record.record_date) }}</div>
                  </div>
                  <div class="record-content">
                    <div class="record-age">{{ calculateAge(child?.birth_date, record.date) }}</div>
                    <div class="record-measurements">
                      <div class="measurement-item">
                        <span class="measurement-label">身長:</span>
                        <span class="measurement-value" :class="{'empty-value': !record.height}">
                          {{ record.height ? `${record.height}cm` : '未入力です' }}
                        </span>
                      </div>
                      <div class="measurement-item">
                        <span class="measurement-label">体重:</span>
                        <span class="measurement-value" :class="{'empty-value': !record.weight}">
                          {{ record.weight ? `${record.weight}kg` : '未入力です' }}
                        </span>
                      </div>
                    </div>
                    <div class="record-memo" :class="{'empty-memo': !record.memo}">
                      {{ record.memo || (record.isEmpty ? 'データがありません' : '特記事項はありません') }}
                    </div>
                  </div>
                  <div class="record-actions">
                    <button v-if="!record.isEmpty" class="action-button edit" @click="editRecord(record.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </button>
                    <button v-else class="action-button add" @click="addRecord(record.date)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                      </svg>
                    </button>
                    <button v-if="!record.isEmpty" class="action-button delete" @click="deleteRecord(record.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- 「もっと見る」ボタン -->
              <button v-if="hasMoreRecords" @click="showMoreRecords" class="view-more-button">
                {{ moreButtonText }}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline v-if="displayLimit === -1" points="18 15 12 9 6 15"></polyline>
                  <polyline v-else points="6 9 12 15 18 9"></polyline>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- その他のタブコンテンツ -->
          <div v-else-if="activeTab !== 'physical'" class="coming-soon-content">
            <div class="coming-soon-icon">✨</div>
            <p class="coming-soon-text">準備中です</p>
            <p class="coming-soon-description">この機能は近日公開予定です</p>
          </div>
        </div>
      </div>

      <!-- 記録追加モード -->
      <div v-else-if="activeMode === 'add'" class="info-card growth-record-form-section">
        <div class="card-header">
          <h3 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>成長記録の追加</span>
          </h3>
        </div>
        
        <div class="card-body">
          <div class="form-tabs">
            <button class="tab-button" :class="{ active: activeFormTab === 'physical' }" @click="activeFormTab = 'physical'">身体測定</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'development' }" @click="activeFormTab = 'development'">発達</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'checkup' }" @click="activeFormTab = 'checkup'">健診</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'vaccine' }" @click="activeFormTab = 'vaccine'">予防接種</button>
          </div>
          
          <!-- 身体測定フォーム -->
          <div v-if="activeFormTab === 'physical'" class="form-container">
            <form @submit.prevent="submitForm" class="growth-record-form">
              <div class="form-header">
                <div class="form-icon">📏</div>
                <div class="form-title">
                  <h4>身体測定データを記録</h4>
                  <p>{{ child ? child.name : 'お子さま' }}の成長を記録しましょう</p>
                </div>
              </div>

              <div class="form-grid">
                <div class="form-group date-group">
                  <label for="record-year-month">記録年月</label>
                  <div class="input-with-icon">
                    <input 
                      type="month" 
                      id="record-year-month" 
                      v-model="formData.year_month" 
                      required 
                      class="form-input disabled-input"
                      :disabled="true"
                      :title="'月ごとの記録です'"
                    >
                    <div class="fixed-date-notice">月ごとの記録</div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="record-height">身長 (cm) <span class="required">*</span></label>
                  <div class="input-with-icon">
                    <input type="number" id="record-height" v-model="formData.height" step="0.1" min="0" max="200" class="form-input" placeholder="例: 82.5" required>
                    <span class="input-suffix">cm</span>
                  </div>
                  <div class="input-hint">0.1cm単位で入力できます</div>
                </div>
                
                <div class="form-group">
                  <label for="record-weight">体重 (kg) <span class="required">*</span></label>
                  <div class="input-with-icon">
                    <input type="number" id="record-weight" v-model="formData.weight" step="0.01" min="0" max="100" class="form-input" placeholder="例: 11.25" required>
                    <span class="input-suffix">kg</span>
                  </div>
                  <div class="input-hint">0.01kg単位で入力できます</div>
                </div>
                
                <div class="form-group full-width">
                  <label for="record-memo">メモ</label>
                  <textarea id="record-memo" v-model="formData.memo" class="form-textarea" rows="4" placeholder="特記事項やお子さまの様子などをメモできます"></textarea>
                </div>
              </div>
              
              <div class="form-actions">
                <button type="button" @click="resetForm" class="reset-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v5h5"></path>
                    <path d="M3 3l7 7"></path>
                    <path d="M21 21v-5h-5"></path>
                    <path d="M21 21l-7-7"></path>
                  </svg>
                  リセット
                </button>
                <button type="submit" class="submit-button" :disabled="formSubmitting">
                  <span v-if="formSubmitting">
                    <svg class="spinner-icon" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="4" />
                    </svg>
                    保存中...
                  </span>
                  <span v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                      <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    記録を保存
                  </span>
                </button>
              </div>
            </form>
          </div>
          
          <!-- その他のフォーム -->
          <div v-else class="coming-soon-content">
            <div class="coming-soon-icon">✨</div>
            <p class="coming-soon-text">準備中です</p>
            <p class="coming-soon-description">この機能は近日公開予定です</p>
          </div>
        </div>
      </div>
    </div>

    <!-- モーダルフォーム (body終了タグの直前に追加) -->
    <div v-if="showFormModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-container">
        <div class="modal-header">
          <h3 class="modal-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>{{ isEditMode ? '成長記録の編集' : '成長記録の追加' }}</span>
          </h3>
          <button @click="closeModal" class="modal-close-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-tabs">
            <button class="tab-button" :class="{ active: activeFormTab === 'physical' }" @click="activeFormTab = 'physical'">身体測定</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'development' }" @click="activeFormTab = 'development'">発達</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'checkup' }" @click="activeFormTab = 'checkup'">健診</button>
            <button class="tab-button" :class="{ active: activeFormTab === 'vaccine' }" @click="activeFormTab = 'vaccine'">予防接種</button>
          </div>
          
          <!-- 身体測定フォーム -->
          <div v-if="activeFormTab === 'physical'" class="form-container">
            <form @submit.prevent="submitForm" class="growth-record-form">
              <div class="form-header">
                <div class="form-icon">📏</div>
                <div class="form-title">
                  <h4>身体測定データを記録</h4>
                  <p>{{ child ? child.name : 'お子さま' }}の成長を記録しましょう</p>
                </div>
              </div>

              <div class="form-grid">
                <div class="form-group date-group">
                  <label for="record-year-month">記録年月</label>
                  <div class="input-with-icon">
                    <input 
                      type="month" 
                      id="record-year-month" 
                      v-model="formData.year_month" 
                      required 
                      class="form-input disabled-input"
                      :disabled="true"
                      :title="'月ごとの記録です'"
                    >
                    <div class="fixed-date-notice">月ごとの記録</div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="record-height">身長 (cm) <span class="required">*</span></label>
                  <div class="input-with-icon">
                    <input type="number" id="record-height" v-model="formData.height" step="0.1" min="0" max="200" class="form-input" placeholder="例: 82.5" required>
                    <span class="input-suffix">cm</span>
                  </div>
                  <div class="input-hint">0.1cm単位で入力できます</div>
                </div>
                
                <div class="form-group">
                  <label for="record-weight">体重 (kg) <span class="required">*</span></label>
                  <div class="input-with-icon">
                    <input type="number" id="record-weight" v-model="formData.weight" step="0.01" min="0" max="100" class="form-input" placeholder="例: 11.25" required>
                    <span class="input-suffix">kg</span>
                  </div>
                  <div class="input-hint">0.01kg単位で入力できます</div>
                </div>
                
                <div class="form-group full-width">
                  <label for="record-memo">メモ</label>
                  <textarea id="record-memo" v-model="formData.memo" class="form-textarea" rows="4" placeholder="特記事項やお子さまの様子などをメモできます"></textarea>
                </div>
              </div>
              
              <div class="form-actions">
                <button type="button" @click="resetForm" class="reset-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v5h5"></path>
                    <path d="M3 3l7 7"></path>
                    <path d="M21 21v-5h-5"></path>
                    <path d="M21 21l-7-7"></path>
                  </svg>
                  リセット
                </button>
                <button type="submit" class="submit-button" :disabled="formSubmitting">
                  <span v-if="formSubmitting">
                    <svg class="spinner-icon" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="4" />
                    </svg>
                    保存中...
                  </span>
                  <span v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                      <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    記録を保存
                  </span>
                </button>
              </div>
            </form>
          </div>
          
          <!-- その他のフォーム -->
          <div v-else class="coming-soon-content">
            <div class="coming-soon-icon">✨</div>
            <p class="coming-soon-text">準備中です</p>
            <p class="coming-soon-description">この機能は近日公開予定です</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useChildrenStore } from '@/stores/children';
import { Child, GrowthRecord } from '@/types';

const route = useRoute();
const router = useRouter();
const childrenStore = useChildrenStore();

// 状態
const loading = ref(false);
const error = ref('');
const formSubmitting = ref(false);
const activeMode = ref('view'); // view または add
const activeTab = ref('physical'); // physical, development, checkup, vaccine
const activeFormTab = ref('physical'); // フォーム用タブ

// 編集モード用の状態変数
const isEditMode = ref(false);
const editingRecordId = ref<number | null>(null);

// GrowthRecordの拡張型定義
interface ExtendedGrowthRecord extends GrowthRecord {
  isEmpty?: boolean;
  record_date?: string;
}

// 子供の情報
interface ChildWithRecords extends Child {
  growth_records?: ExtendedGrowthRecord[];
}

const child = ref<ChildWithRecords | null>(null);

// const month = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

// 表示制限の初期値
const displayLimit = ref(6);

// 日本の現在「年月」のみを取得する関数
const getCurrentYearMonth = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  return `${year}-${month}`;
};

// フォームデータの型
interface GrowthRecordForm {
  year_month: string; // 年月のみの形式 (YYYY-MM)
  height: number | null;
  weight: number | null;
  memo: string;
}

// フォームデータの初期化
const formData = ref<GrowthRecordForm>({
  year_month: getCurrentYearMonth(),
  height: null,
  weight: null,
  memo: ''
});

// モーダル表示フラグ
const showFormModal = ref(false);

// 月名を取得する関数（型を修正）
const getMonthName = (dateString: string | undefined): string => {
  if (!dateString) {
    console.warn('日付が空です');
    return '';
  }
  
  try {
    const date = new Date(dateString);
    
    if (isNaN(date.getTime())) {
      console.warn('無効な日付:', dateString);
      return '';
    }
    
    return `${date.getMonth() + 1}月`;
  } catch (err) {
    console.error('日付解析エラー:', err);
    return '';
  }
};

// 年を取得する関数（型を修正）
const getYear = (dateString: string | undefined): string => {
  if (!dateString) {
    console.warn('年を取得する日付が空です');
    return '';
  }
  
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
      console.warn('無効な日付（年）:', dateString);
      return '';
    }
    return date.getFullYear().toString();
  } catch (err) {
    console.error('年の解析エラー:', err);
    return '';
  }
};

// 記録データの正規化関数
const normalizeRecordDate = (record: any): ExtendedGrowthRecord => {
  // dateフィールドがなくてrecord_dateがある場合
  if (!record.date && record.record_date) {
    return {
      ...record,
      date: record.record_date
    };
  }
  
  // record_dateフィールドがなくてdateがある場合
  if (!record.record_date && record.date) {
    return {
      ...record,
      record_date: record.date
    };
  }
  
  // 両方あるか両方ない場合はそのまま返す
  return record as ExtendedGrowthRecord;
};

// もっと見るボタンのテキスト
const moreButtonText = computed(() => {
  if (displayLimit.value === -1) {
    return '表示を減らす';
  }
  return `もっと見る (全${getAllMonthsData.value.length}ヶ月分)`;
});

// hasRecordsをcomputedに変更
const hasRecords = computed(() => {
  return child.value?.growth_records && child.value.growth_records.length > 0;
});

// 誕生月から現在までの全ての月のデータ生成
const getAllMonthsData = computed((): ExtendedGrowthRecord[] => {
  if (!child.value || !child.value.birth_date) {
    return [];
  }
  
  // 誕生日と現在の日付
  const birthDate = new Date(child.value.birth_date);
  const currentDate = new Date();
  
  // 結果を格納する配列
  const result: ExtendedGrowthRecord[] = [];
  
  // 月数の計算 (現在の年 - 誕生年) * 12 + (現在の月 - 誕生月)
  let totalMonths = (currentDate.getFullYear() - birthDate.getFullYear()) * 12 + 
                     (currentDate.getMonth() - birthDate.getMonth());
  
  console.log(`子供の誕生日: ${birthDate.toLocaleDateString()}, 月齢: ${totalMonths}ヶ月`);
  
  // すべての月のカードを生成（誕生月から現在まで）
  for (let i = 0; i <= totalMonths; i++) {
    // 誕生月からi月後の日付
    const targetDate = new Date(
      birthDate.getFullYear(), 
      birthDate.getMonth() + i, 
      15  // 月の中旬を指定
    );
    
    const targetYear = targetDate.getFullYear();
    const targetMonth = targetDate.getMonth();
    
    // この年月にデータがあるか探す
    const existingRecord = child.value.growth_records?.find((record: ExtendedGrowthRecord) => {
      // date または record_date のどちらかを使用（型アサーションを使用）
      const recordDateStr = record.date || record.record_date || '';
      
      if (!recordDateStr) {
        console.warn('日付のないレコード:', record);
        return false;
      }
      
      try {
        const recordDate = new Date(recordDateStr);
        // 年月の比較
        return recordDate.getMonth() === targetMonth && 
               recordDate.getFullYear() === targetYear;
      } catch (err) {
        console.error('日付解析エラー:', recordDateStr, err);
        return false;
      }
    });
    
    if (existingRecord) {
      // 既存データがあればそれを使用
      // date フィールドを確実に持つように
      const recordWithDate = {
        ...existingRecord,
        date: existingRecord.date || (existingRecord as any).record_date
      };
      result.push(recordWithDate as ExtendedGrowthRecord);
    } else {
      // データがなければ空のカードを作成
      result.push({
        id: -(targetYear * 100 + targetMonth), // 一意のID
        child_id: child.value.id,
        date: targetDate.toISOString().split('T')[0],
        height: null,
        weight: null,
        memo: null,
        created_at: '',
        updated_at: '',
        isEmpty: true
      });
    }
  }
  
  // 新しい月から古い月の順にソート（降順）
  return result.sort((a, b) => {
    const dateA = new Date(a.date || '');
    const dateB = new Date(b.date || '');
    // 年の比較を先に行い、同じ年なら月を比較（降順）
    if (dateB.getFullYear() !== dateA.getFullYear()) {
      return dateB.getFullYear() - dateA.getFullYear();
    }
    return dateB.getMonth() - dateA.getMonth();
  });
});

// 表示用のデータ（件数制限あり）
const visibleRecords = computed((): ExtendedGrowthRecord[] => {
  const records = getAllMonthsData.value;
  if (displayLimit.value === -1) {
    return records;
  }
  return records.slice(0, displayLimit.value);
});

// hasMoreRecords
const hasMoreRecords = computed(() => {
  return getAllMonthsData.value.length > displayLimit.value && displayLimit.value !== -1;
});

// 子供の年齢を計算（例: 1歳3ヶ月）
const calculateAge = (birthDate: string | undefined, recordDate: string | undefined): string => {
  if (!birthDate || !recordDate) return '年齢不明';
  
  const birth = new Date(birthDate);
  const record = new Date(recordDate);
  
  const years = record.getFullYear() - birth.getFullYear();
  let months = record.getMonth() - birth.getMonth();
  
  if (months < 0) {
    months += 12;
  }
  
  if (years === 0) {
    return `${months}ヶ月`;
  } else {
    return `${years}歳${months}ヶ月`;
  }
};

// onMountedフックを修正
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
    
    console.log('子供の情報:', child.value);
    
    // 成長記録を取得
    try {
      const recordsResponse = await childrenStore.fetchGrowthRecord(childId.toString());
      
      if (recordsResponse && recordsResponse.data) {
        // 最初のレコードの構造を確認
        if (recordsResponse.data.length > 0) {
          console.log('最初のレコード構造:', recordsResponse.data[0]);
        }
        
        // データを正規化
        child.value.growth_records = recordsResponse.data.map(normalizeRecordDate);
        
        // 正規化後の確認
        if (child.value.growth_records && child.value.growth_records.length > 0) {
          console.log('正規化後の最初のレコード:', child.value.growth_records[0]);
        }
      } else {
        child.value.growth_records = [];
      }
    } catch (recordErr: any) {
      console.error('成長記録の取得でエラー:', recordErr);
      console.error('エラー詳細:', recordErr.response?.data);
      child.value.growth_records = [];
    }
    
  } catch(err: any) {
    console.error('子供の情報の取得に失敗しました:', err);
    error.value = err.response?.data?.message || 'データの取得に失敗しました';
    if (child.value) {
      child.value.growth_records = [];
    }
  } finally {
    loading.value = false;
  }
  
  formData.value.year_month = getCurrentYearMonth();
});

// モーダルを開く関数を修正
const openModal = (yearMonth?: string) => {
  console.log(`モーダルを開きます。編集モード:${isEditMode.value}, 指定年月:${yearMonth || '無し'}`);
  
  // 編集モードの場合は、既存の年月をそのまま使用
  if (!isEditMode.value) {
    if (yearMonth) {
      // 特定の年月が指定された場合
      formData.value.year_month = yearMonth;
      console.log('指定された年月をセット:', yearMonth);
    } else {
      // 新規追加で年月が指定されていない場合は現在の年月を使用
      formData.value.year_month = getCurrentYearMonth();
      console.log('現在の年月をセット:', formData.value.year_month);
    }
  } else {
    // 編集モードの場合は既存の年月をそのまま維持（変更しない）
    console.log('編集モード: 既存の年月を維持:', formData.value.year_month);
  }
  
  // モーダル表示
  document.body.classList.add('modal-open');
  showFormModal.value = true;
};

// モーダルを閉じる関数
const closeModal = () => {
  document.body.classList.remove('modal-open');
  showFormModal.value = false;
  
  isEditMode.value = false;
  editingRecordId.value = null;
};

// 特定の年月の記録を追加する関数
const addRecord = (dateString: string | undefined) => {
  if (!dateString) return;
  
  // 日付から年月のみを抽出 (YYYY-MM-DD -> YYYY-MM)
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
      console.warn('無効な日付:', dateString);
      return;
    }
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const yearMonth = `${year}-${month}`;
    
    formData.value.year_month = yearMonth;
    openModal(yearMonth);
  } catch (err) {
    console.error('日付解析エラー:', err);
  }
};

// showMoreRecords関数
const showMoreRecords = () => {
  if (displayLimit.value === -1) {
    displayLimit.value = 6;
  } else {
    displayLimit.value = -1;
  }
};

// 編集ボタンの処理
const editRecord = (id: number) => {
  console.log(`ID:${id}の記録を編集します`);
  
  // 型を明確に
  const recordToEdit = child.value?.growth_records?.find(
    (record): record is ExtendedGrowthRecord => record.id === id
  );
  
  if (recordToEdit) {
    console.log('編集対象の記録:', recordToEdit);
    
    isEditMode.value = true;
    editingRecordId.value = Number(id);
    
    // 数値型の確認
    const height = typeof recordToEdit.height === 'number' ? recordToEdit.height : null;
    const weight = typeof recordToEdit.weight === 'number' ? recordToEdit.weight : null;
    
    // 記録の年月を取得
    let yearMonth = '';
    const recordDate = recordToEdit.date || (recordToEdit as any).record_date || '';
    
    if (recordDate) {
      try {
        const date = new Date(recordDate);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        yearMonth = `${year}-${month}`;
      } catch (err) {
        console.error('日付解析エラー:', err);
        yearMonth = getCurrentYearMonth();
      }
    }
    
    // フォームデータをセット
    formData.value = {
      year_month: yearMonth,
      height: height,
      weight: weight,
      memo: recordToEdit.memo || ''
    };
    
    console.log('フォームデータをセット:', formData.value);
    
    // 編集モードでモーダルを開く
    openModal();
  } else {
    console.error(`ID:${id}の記録が見つかりません`);
  }
};

// フォームのリセット関数を修正
const resetForm = () => {
  formData.value = {
    year_month: getCurrentYearMonth(),
    height: null,
    weight: null,
    memo: ''
  };
};

// フォームの送信処理を修正
const submitForm = async () => {
  formSubmitting.value = true;
  
  try {
    // バリデーション
    if (!formData.value.height) {
      throw new Error('身長を入力してください');
    }
    
    if (!formData.value.weight) {
      throw new Error('体重を入力してください');
    }
    
    // 更新と新規作成で異なる処理
    if (isEditMode.value && editingRecordId.value) {
      // 更新時はrecord_dateを送信しない（既存の日付を保持するため）
      const submitData = {
        child_id: child.value?.id,
        // record_date: フィールドを省略
        height: formData.value.height as number,
        weight: formData.value.weight as number,
        memo: formData.value.memo || ''
      };
      
      console.log('更新データ (日付を含まない):', submitData);
      
      try {
        const response = await childrenStore.updateGrowthRecord(editingRecordId.value, submitData);
        console.log('更新成功:', response);
      } catch (updateErr) {
        console.error('更新エラー:', updateErr);
        throw updateErr;
      }
    } else {
      // 新規作成時は年月から日付を生成
      // APIに送信するデータの準備
      const yearMonthParts = formData.value.year_month.split('-');
      const year = parseInt(yearMonthParts[0]);
      const month = parseInt(yearMonthParts[1]);
      
      // その月の末日を取得 (翌月の0日 = 当月の末日)
      const lastDay = new Date(year, month, 0).getDate();
      const record_date = `${formData.value.year_month}-${String(lastDay).padStart(2, '0')}`;
      
      const submitData = {
        child_id: child.value?.id,
        record_date: record_date, // 年月の末日をセット
        height: formData.value.height as number,
        weight: formData.value.weight as number,
        memo: formData.value.memo || ''
      };
      
      console.log('新規作成データ:', submitData);
      
      try {
        const response = await childrenStore.createGrowthRecord(submitData);
        console.log('作成成功:', response);
      } catch (createErr) {
        console.error('作成エラー:', createErr);
        throw createErr;
      }
    }
    
    // 成功後の処理
    resetForm();
    closeModal();
    
    // 最新データを再取得
    if (route.params.id && child.value) {
      console.log('成長記録を再取得します');
      try {
        const recordsResponse = await childrenStore.fetchGrowthRecord(route.params.id.toString());
        
        if (recordsResponse && recordsResponse.data) {
          console.log('取得した成長記録:', recordsResponse.data);
          
          // データを正規化して日付フィールドを確実に持つようにする
          child.value.growth_records = recordsResponse.data.map(record => normalizeRecordDate(record));
        }
      } catch (fetchErr) {
        console.error('記録の再取得に失敗:', fetchErr);
      }
    }
    
  } catch (err: any) {
    console.error('送信エラー:', err);
    if (err.response) {
      console.error('エラー詳細:', err.response.data);
    }
    alert(err.message || '記録の保存に失敗しました');
  } finally {
    formSubmitting.value = false;
  }
};

// 削除ボタンの処理
const deleteRecord = async (id: number) => {
  // 確認ダイアログを表示
  if (!confirm('この記録を削除してもよろしいですか？')) {
    return;
  }
  
  try {
    // APIを呼び出して記録を削除
    await childrenStore.deleteGrowthRecord(id);
    
    // 削除後にデータを更新（成長記録リストから削除対象を除外）
    if (child.value && child.value.growth_records) {
      child.value.growth_records = child.value.growth_records.filter(record => record.id !== id);
    }
    
    // 成功メッセージ
    alert('記録を削除しました');
  } catch (err: any) {
    console.error('記録の削除に失敗しました:', err);
    alert('記録の削除に失敗しました');
  }
};

// watch関数の修正 - 編集モードの時は日付を上書きしない
watch(() => showFormModal.value, (isOpen) => {
  if (isOpen && !isEditMode.value && !formData.value.year_month) {
    // モーダルが開いたとき、編集モードでなく日付が空の場合のみ現在日を設定
    formData.value.year_month = getCurrentYearMonth();
  }
});

// TypeScript エラーを修正するため、DOM要素へのアクセス方法を修正
setTimeout(() => {
  const dateInputs = document.querySelectorAll('input[type="date"]');
  if (dateInputs && dateInputs.length > 0) {
    console.log('日付フィールドの値:', (dateInputs[0] as HTMLInputElement).value);
  }
}, 100);
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

.growth-records-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.mode-tabs {
  display: flex;
  gap: 1rem;
  background-color: white;
  padding: 1rem;
  border-radius: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.08);
}

.mode-tab {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.5rem;
  border: none;
  background-color: #f0f2ff;
  color: #6a5acd;
  font-weight: 600;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  justify-content: center;
}

.mode-tab.active {
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  box-shadow: 0 0.2rem 0.4rem rgba(106, 90, 205, 0.3);
}

.mode-tab:hover:not(.active) {
  background-color: #e0e4ff;
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
  cursor: pointer;
  transition: all 0.3s ease;
}

.view-records-button:hover {
  background-color: rgba(106, 90, 205, 0.2);
}

.empty-records {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
  text-align: center;
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

.start-recording-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  border: none;
  padding: 0.7rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.start-recording-button:hover {
  background: linear-gradient(135deg, #5a4abf, #4a3aaf);
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(106, 90, 205, 0.4);
}

.delete {
  color: #ef5350;
}

.delete:hover {
  background-color: rgba(239, 83, 80, 0.1);
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

/* レスポンシブ対応 */
@media (max-width: 48rem) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .mode-tabs {
    flex-direction: column;
  }
}

.empty-value {
  color: #aaa;
  font-style: italic;
}

.empty-memo {
  color: #aaa;
  font-style: italic;
  background-color: #f8f9ff;
}

.empty-card {
  background-color: #f8f9ff;
  border: 1px dashed #ddddf0;
}

.action-button.add {
  color: #4caf50;
}

.action-button.add:hover {
  background-color: rgba(76, 175, 80, 0.1);
}

/* フォームのスタイル */
.growth-record-form-section {
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.08);
}

.form-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
}

.form-container {
  padding: 0.5rem;
  background-color: white;
  border-radius: 0.8rem;
}

.growth-record-form {
  display: flex;
  flex-direction: column;
  gap: 1.8rem;
}

.form-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.2rem;
  margin-bottom: 1.5rem;
  background-color: #f8f9ff;
  border-radius: 0.8rem;
}

.form-icon {
  font-size: 2rem;
  color: #6a5acd;
}

.form-title h4 {
  margin: 0;
  font-size: 1.2rem;
  color: #4a4a68;
}

.form-title p {
  margin: 0.3rem 0 0;
  font-size: 0.9rem;
  color: #8a8aa8;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 600;
  color: #4a4a68;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.required {
  color: #ef5350;
}

.input-with-icon {
  position: relative;
  display: flex;
  align-items: center;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 0.8rem 1rem;
  border: 1px solid #ddddf0;
  border-radius: 0.8rem;
  font-size: 1rem;
  color: #4a4a68;
  background-color: white;
  transition: all 0.3s ease;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #6a5acd;
  box-shadow: 0 0 0 3px rgba(106, 90, 205, 0.15);
}

.form-input::placeholder, .form-textarea::placeholder {
  color: #aaa;
}

.input-hint {
  font-size: 0.8rem;
  color: #8a8aa8;
}

/* .input-icon {
  position: absolute;
  right: 1rem;
  color: #6a5acd;
  pointer-events: none;
} */

.input-suffix {
  position: absolute;
  right: 1rem;
  color: #8a8aa8;
  font-weight: 500;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.8rem;
  padding-bottom: 0.5rem;
}

.reset-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.2rem;
  border: 1px solid #ddddf0;
  border-radius: 0.8rem;
  background-color: white;
  color: #8a8aa8;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.reset-button:hover {
  background-color: #f8f9ff;
  color: #6a5acd;
  border-color: #6a5acd;
}

.submit-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 0.8rem;
  background: linear-gradient(135deg, #6a5acd, #5a4abf);
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 0.2rem 0.5rem rgba(106, 90, 205, 0.3);
}

.submit-button:hover:not(:disabled) {
  background: linear-gradient(135deg, #5a4abf, #4a3aaf);
  transform: translateY(-0.1rem);
  box-shadow: 0 0.3rem 0.7rem rgba(106, 90, 205, 0.4);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner-icon {
  animation: spin 1s linear infinite;
  width: 16px;
  height: 16px;
}

.spinner-icon circle {
  opacity: 0.2;
}

.spinner-icon circle:nth-child(1) {
  animation-delay: -0.5s;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* モバイル対応 */
@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .reset-button, .submit-button {
    width: 100%;
    justify-content: center;
  }
}

/* モーダルスタイル */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.modal-container {
  background-color: white;
  border-radius: 1rem;
  width: 70%;
  max-width: 35rem;
  max-height: 75vh;
  overflow-y: auto;
  box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2);
  animation: slideIn 0.3s ease;
  padding-bottom: 1.2rem;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.2rem;
  border-bottom: 1px solid #f0f0fa;
  background-color: #f8f9ff;
}

.modal-title {
  font-size: 1.1rem;
}

.modal-close-button {
  background: none;
  border: none;
  color: #8a8aa8;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.modal-close-button:hover {
  background-color: rgba(0, 0, 0, 0.05);
  color: #4a4a68;
}

.modal-body {
  padding: 1.2rem 1rem;
  max-height: calc(75vh - 5rem);
  overflow-y: auto;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* スクロールバーのスタイル */
.modal-container::-webkit-scrollbar {
  width: 0.5rem;
}

.modal-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 1rem;
}

.modal-container::-webkit-scrollbar-thumb {
  background: #ddddf0;
  border-radius: 1rem;
}

.modal-container::-webkit-scrollbar-thumb:hover {
  background: #c0c0d0;
}

/* 数値入力フィールドのスピナーを非表示に */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  appearance: none;
  margin: 0; 
}

input[type=number] {
  -moz-appearance: textfield; /* Firefox用 */
  appearance: textfield; /* 標準プロパティを追加 */
}

/* モーダルがはみ出さないようにする修正 */
@media (max-width: 768px) {
  .modal-container {
    width: 90%;
    max-height: 85vh;
  }
  
  .modal-body {
    padding: 1rem 0.8rem;
  }
  
  .form-grid {
    gap: 0.8rem;
  }
  
  .form-header {
    padding: 0.8rem;
    margin-bottom: 1rem;
  }
  
  .form-input, .form-textarea {
    font-size: 15px;
    padding: 0.6rem;
  }
}

/* 入力ヒントも小さく */
.input-hint {
  font-size: 0.75rem;
}

/* ページ全体のスクロール禁止（モーダル表示時） */
body.modal-open {
  overflow: hidden;
}

/* フォームグループ間の間隔調整 */
.growth-record-form .form-group {
  margin-bottom: 0.5rem;
}

/* スマホでのタップ領域を広げる */
.form-tabs .tab-button {
  min-height: 2.5rem;
}

.fixed-date-notice {
  font-size: 0.8rem;
  color: #8a8aa8;
  margin-top: 0.5rem;
}

/* 既存のスタイルに追加 */

.disabled-input {
  background-color: #f5f5f5;
  color: #666;
  border-color: #e0e0e0;
  cursor: not-allowed;
}

.fixed-date-notice {
  position: absolute;
  right: 1rem;
  font-size: 0.8rem;
  color: #888;
  background-color: #efefef;
  padding: 0.2rem 0.5rem;
  border-radius: 0.4rem;
  pointer-events: none;
}

/* この行は既存のスタイルの終わりに追加してください */
</style>