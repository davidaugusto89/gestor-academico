<template>
  <div :class="['flex flex-col', spacingClass]" :style="customStyle">
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    spacing: {
      type: String,
      default: 'md',
    },
    customGap: {
      type: String,
      default: '',
    },
  })

  const { spacing, customGap } = props

  const spacingClass = computed(() => {
    if (customGap) return ''

    switch (spacing) {
      case 'sm':
        return 'mb-2'
      case 'lg':
        return 'mb-6'
      default:
        return 'mb-3'
    }
  })

  const customStyle = computed(() => (customGap ? { gap: customGap } : {}))
</script>
