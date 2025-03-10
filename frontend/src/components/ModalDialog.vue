<template>
  <div class="modal-overlay" @click="closeOnBackdrop ? $emit('close') : null">
    <div class="modal-container" @click.stop>
      <div class="modal-header">
        <slot name="header">
          <h3>ダイアログ</h3>
        </slot>
        <button class="close-button" @click="$emit('close')">&times;</button>
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

<style>

</style>