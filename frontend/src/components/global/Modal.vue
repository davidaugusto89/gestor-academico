<template>
  <div v-if="visible" class="modal-overlay">
    <div class="modal">
      <slot></slot>
      <button @click="close">Fechar</button>
    </div>
  </div>
</template>

<script setup lang="ts">
  const emit = defineEmits(['update:visible'])
  defineProps({
    visible: Boolean,
  })

  const close = () => {
    emit('update:visible', false)
  }
</script>

<style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .modal {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 80%;
    max-width: 500px;
  }
</style>
