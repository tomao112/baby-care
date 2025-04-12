<template>
  <div class="modal-overlay" @click="$emit('close')">
    <div class="modal-container" @click.stop>
      <div class="modal-header">
        <slot name="header"></slot>
        <button class="close-button" @click="$emit('close')" aria-label="閉じる">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <slot></slot>
      </div>
      <div class="modal-footer" v-if="$slots.footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  closeOnBackdrop: {
    type: Boolean,
    default: true
  }
});

defineEmits(['close']);
</script>

<style scoped>
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
  width: 100vw;
  height: 100vh;
  box-sizing: border-box;
}

.modal-container {
  background-color: white;
  border-radius: 1rem;
  width: 80%;
  max-width: 24rem;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0 0.4rem 1.5rem rgba(0, 0, 0, 0.15);
  animation: slideIn 0.3s ease;
  transform: scale(0.85);
  transform-origin: center;
}

/* スクロールバーのスタイル */
.modal-container::-webkit-scrollbar {
  width: 6px;
}

.modal-container::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 8px;
  margin: 4px;
}

.modal-container::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.15);
  border-radius: 8px;
}

.modal-container::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.25);
}

.modal-header {
  padding: 1.2rem;
  border-bottom: 1px solid #f0f0fa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
  background: linear-gradient(180deg, #ffffff, #fafafa);
}

.close-button {
  position: absolute;
  top: 0.8rem;
  right: 0.8rem;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background: linear-gradient(135deg, #f0f0fa, #e6e6fa);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #8a8aa8;
  box-shadow: 0 0.15rem 0.4rem rgba(0, 0, 0, 0.05);
}

.close-button:hover {
  background: linear-gradient(135deg, #e6e6fa, #d8d8f0);
  color: #6a5acd;
  transform: rotate(90deg);
}

.close-button:active {
  transform: rotate(90deg) scale(0.95);
}

.modal-body {
  padding: 1.2rem;
  border-bottom-left-radius: 1rem;
  border-bottom-right-radius: 1rem;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(-1.5rem) scale(0.85); opacity: 0; }
  to { transform: translateY(0) scale(0.85); opacity: 1; }
}

@media (max-width: 48rem) {
  .modal-container {
    width: 90%;
    max-width: none;
    border-radius: 0.8rem;
    transform: scale(0.8);
  }
  
  .modal-header {
    padding: 1rem;
    border-top-left-radius: 0.8rem;
    border-top-right-radius: 0.8rem;
  }
  
  .modal-body {
    padding: 1rem;
    border-bottom-left-radius: 0.8rem;
    border-bottom-right-radius: 0.8rem;
  }
  
  .close-button {
    width: 1.8rem;
    height: 1.8rem;
    top: 0.6rem;
    right: 0.6rem;
  }
}
</style>