<template>
  <div class="profile-container animate__animated animate__fadeIn">
    <h2 class="app-title animate__animated animate__fadeInDown">ユーザーのプロフィールを編集する画面です</h2>

    <form @submit.prevent="saveProfile">
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
        <p>読み込み中...</p>
      </div>

      <div v-else class="content-container animate__animated animate__fadeIn" style="--animate-delay: 0.2s">
        <div class="profile-card">
          <div class="profile-header">
            <div class="avatar-container animate__animated animate__fadeInUp" style="--animate-delay: 0.3s">
              <div class="avatar-wrapper">
                <img src="" alt="プロフィール画像" class="avatar-image">
                <div class="avatar-overlay">
                  <span class="avatar-icon">📷</span>
                </div>
              </div>
              <button class="avatar-button">
                <span>写真を変更</span>
                <span class="button-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                  </svg>
                </span>
              </button>
            </div>
          </div>
          
          <div class="profile-form animate__animated animate__fadeInUp" style="--animate-delay: 0.4s">
            <div class="form-group">
              <label for="username">ユーザー名</label>
              <input type="text" id="username" v-model="form.username" placeholder="ユーザー名を入力してください" />
            </div>
            
            <div class="form-group">
              <label for="email">メールアドレス</label>
              <input type="email" id="email" v-model="form.email" placeholder="メールアドレスを入力してください" />
            </div>
            <!-- <div class="form-group">
              <label for="password">パスワード</label>
              <input type="password" id="password" v-model="form.password" placeholder="パスワードを入力してください" />
            </div> -->
            <!-- <div class="form-group">
              <label for="bio">自己紹介</label>
              <textarea id="bio" placeholder="自己紹介を書いてください" rows="4"></textarea>
            </div> -->
            
            <div class="button-group animate__animated animate__fadeInUp" style="--animate-delay: 0.5s">
              <button type="submit" class="save-button">
                <span>保存する</span>
                <span class="button-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                  </svg>
                </span>
              </button>
              <button class="cancel-button">
                <span>キャンセル</span>
                <span class="button-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const loading = ref(true);

const form = reactive({
  username: '',
  email: '',
  password: '',
})


onMounted(async () => {
  try {
    // ユーザ情報がない場合に取得
    if(!authStore.user) {
      await authStore.fetchUser();
    }

    if(authStore.user) {
      form.username = authStore.user.name || '';
      form.email = authStore.user.email || '';
      // form.password = authStore.user.password || '';
    }
  } catch(error) {
    console.error('ユーザ情報の取得に失敗しました:', error);
  } finally {
    loading.value = false;
  }
});

// フォーム送信
const saveProfile = async () => {
  try {
    await authStore.updateProfile({
      name: form.username,
      email: form.email,
    });
    alert('プロフィールを更新しました');
  } catch(error) {
    alert('プロフィールの更新に失敗しました');
    throw error;
  }
}

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

:root {
  --animate-duration: 0.8s;
  --animate-delay: 0s;
}

.profile-container {
  max-width: 60rem;
  margin: 2rem auto;
  padding: 2.5rem;
  background-color: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.profile-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0.5rem;
  background: linear-gradient(90deg, #ffb6c1, #e6e6fa, #b5e8f7);
}

.app-title {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #6a5acd;
  font-weight: 700;
  text-align: center;
  text-shadow: 0.1rem 0.1rem 0.2rem rgba(106, 90, 205, 0.2);
}

.loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  color: #6a5acd;
}

.spinner {
  width: 3rem;
  height: 3rem;
  border: 0.3rem solid rgba(106, 90, 205, 0.3);
  border-radius: 50%;
  border-top-color: #6a5acd;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.content-container {
  margin-top: 1.5rem;
}

.profile-card {
  background-color: #f8f9ff;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.05);
  position: relative;
  overflow: hidden;
}

.profile-header {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem;
}

.avatar-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.avatar-wrapper {
  position: relative;
  width: 9.375rem;
  height: 9.375rem;
  border-radius: 50%;
  margin-bottom: 1rem;
  box-shadow: 0 0.3rem 1rem rgba(106, 90, 205, 0.3);
  overflow: hidden;
  border: 0.3rem solid #e6e6fa;
  transition: all 0.3s ease;
}

.avatar-wrapper:hover {
  transform: scale(1.05);
  border-color: #b5c8f7;
}

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.3s ease;
}

.avatar-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(106, 90, 205, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.avatar-wrapper:hover .avatar-overlay {
  opacity: 1;
}

.avatar-wrapper:hover .avatar-image {
  transform: scale(1.1);
}

.avatar-icon {
  font-size: 2rem;
  color: white;
}

.avatar-button {
  background: linear-gradient(135deg, #6a5acd, #8a7bea);
  color: white;
  border: none;
  border-radius: 2rem;
  padding: 0.5rem 1.2rem;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  box-shadow: 0 0.2rem 0.5rem rgba(106, 90, 205, 0.3);
  position: relative;
  overflow: hidden;
  white-space: nowrap;
  min-width: 7.5rem;
}

.avatar-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.avatar-button:hover {
  transform: translateY(-0.15rem);
  box-shadow: 0 0.4rem 0.8rem rgba(106, 90, 205, 0.4);
  background: linear-gradient(135deg, #5a4abf, #7a6bda);
}

.avatar-button:hover::before {
  left: 100%;
}

.button-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}

.avatar-button:hover .button-icon {
  transform: translateX(0.3rem);
}

.profile-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-weight: 600;
  font-size: 1rem;
  color: #6a5acd;
}

input, textarea {
  padding: 0.9rem;
  border: 0.15rem solid #e6e6fa;
  border-radius: 0.75rem;
  font-size: 1rem;
  transition: all 0.3s ease;
  width: 100%;
  box-sizing: border-box;
  background-color: #fff;
  color: #4a4a68;
}

input:focus, textarea:focus {
  border-color: #6a5acd;
  outline: none;
  box-shadow: 0 0 0 0.125rem rgba(106, 90, 205, 0.2);
}

.button-group {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 1rem;
}

.save-button, .cancel-button {
  padding: 0.6rem 1.5rem;
  border-radius: 2rem;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  position: relative;
  overflow: hidden;
  white-space: nowrap;
  min-width: 8rem;
}

.save-button {
  background: linear-gradient(135deg, #6a5acd, #8a7bea);
  color: white;
  border: none;
  box-shadow: 0 0.2rem 0.5rem rgba(106, 90, 205, 0.3);
}

.save-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
  transition: left 0.6s ease;
}

.save-button:hover {
  transform: translateY(-0.15rem);
  box-shadow: 0 0.4rem 0.8rem rgba(106, 90, 205, 0.4);
  background: linear-gradient(135deg, #5a4abf, #7a6bda);
}

.save-button:hover .button-icon {
  transform: translateX(0.3rem);
}

.cancel-button {
  background-color: #f0f1fa;
  color: #6a5acd;
  border: 0.15rem solid #e6e6fa;
}

.cancel-button:hover {
  background-color: #e6e7f7;
  transform: translateY(-0.2rem);
  box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.08);
}

.cancel-button:hover .button-icon {
  transform: rotate(90deg);
}

/* レスポンシブデザイン対応 */
@media (max-width: 48rem) {
  .profile-container {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .app-title {
    font-size: 1.6rem;
  }
  
  .profile-card {
    padding: 1.5rem;
  }
  
  .avatar-wrapper {
    width: 7.5rem;
    height: 7.5rem;
  }
  
  .button-group {
    flex-direction: column;
    width: 100%;
  }
  
  .save-button, .cancel-button {
    width: 100%;
    justify-content: center;
    padding: 0.6rem;
  }
}

@media (max-width: 30rem) {
  .profile-container {
    padding: 1rem;
  }
  
  .app-title {
    font-size: 1.4rem;
  }
  
  .avatar-wrapper {
    width: 6.25rem;
    height: 6.25rem;
  }
}
</style>