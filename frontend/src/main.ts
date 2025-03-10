import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'

// Axiosのデフォルト設定
axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = false // クッキーを含める場合

// アプリケーションの作成
const app = createApp(App)

// Piniaの使用
app.use(createPinia())

// ルーターの使用
app.use(router)

// アプリケーションのマウント
app.mount('#app')