import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth';

// ビューのインポート
import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
// import ChildrenView from '@/views/ChildrenView.vue';
// import ChildrenDetailView from '@/views/ChildrenDetailView.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { requiresAuth: true}
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
  // {
  //   path: '/children',
  //   name: 'children',
  //   component: ChildrenView,
  //   meta: { requiresAuth: true}
  // },
  // {
  //   path: '/children/:id',
  //   name: 'child-detail',
  //   component: ChildrenDetailView,
  //   props: true,
  //   meta: { requiresAuth: true}
  // },
]

const router = createRouter({
  history: createWebHistory(),
  routes
});

// ナビゲーションガード
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // 認証が必要なルートかつログインしていない場合
  if(to.meta.requiresAuth && !authStore.isLoggedIn) {
    next({ name: 'login'});
  } else {
    next();
  }
})

export default router
