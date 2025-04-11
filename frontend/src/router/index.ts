import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth';

// ビューのインポート
import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import ChildrenView from '@/views/ChildrenView.vue';
import ChildrenDetailView from '@/views/ChildrenDetailView.vue';
import Profile from '@/views/Profile.vue';
import GrowthRecord from '@/views/GrowthRecord.vue';
import Development from '@/views/Development.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
  },
  {
    path: '/children',
    name: 'children',
    component: ChildrenView,
    meta: { requiresAuth: true }
  },
  {
    path: '/children/:id',
    name: 'child-detail',
    component: ChildrenDetailView,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/children/:id/growth-records',
    name: 'growth-Record',
    component: GrowthRecord,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/children/:id/development',
    name: 'development',
    component: Development,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
});

// ナビゲーションガード
router.beforeEach((to, _from, next) => {
  console.log('ルーターガード:', to.path, 'requiresAuth:', to.meta.requiresAuth);
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isLoggedIn) {
    console.log('認証が必要なページ。未ログインのためリダイレクト');
    next('/login');
  } else {
    console.log('ページ遷移許可');
    next()
  }
})

export default router
