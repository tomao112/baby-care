import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

// APIのベースURLを設定
axios.defaults.baseURL = 'http://localhost:8000/api'
// リクエストにクッキーを含める（Sanctumを使用する場合）
axios.defaults.withCredentials = true

// アプリケーションの作成
const app = createApp(App)

// Piniaストアの使用
app.use(createPinia())

// ルーターの使用
app.use(router)

// アプリケーションのマウント
app.mount('#app')
