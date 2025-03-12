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
}

.modal-container {
  background-color: white;
  border-radius: 1rem;
  width: 90%;
  max-width: 30rem;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
  animation: slideIn 0.3s ease;
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #f0f0fa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.close-button {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background: linear-gradient(135deg, #f0f0fa, #e6e6fa);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #8a8aa8;
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.05);
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
  padding: 1.5rem;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(-2rem); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@media (max-width: 48rem) {
  .modal-container {
    width: 95%;
    max-width: none;
    border-radius: 0.8rem;
  }
  
  .modal-header, .modal-body {
    padding: 1.2rem;
  }
  
  .close-button {
    width: 2.2rem;
    height: 2.2rem;
    top: 0.8rem;
    right: 0.8rem;
  }
}
</style>