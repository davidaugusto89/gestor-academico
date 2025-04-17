<template>
  <button
    :class="[
      'btn items-center justify-center py-2 px-4 rounded-lg font-medium transition-all duration-300 focus:outline-none align-middle flex',
      disabled ? 'opacity-55 cursor-not-allowed' : '',
      sizeClass,
      isLoading ? 'opacity-65 cursor-not-allowed' : '',
      typeClass,
    ]"
    :disabled="isLoading || disabled"
    @click="handleClick"
  >
    <Spinner v-if="isLoading" />
    <i class="material-icons text-sm mr-2" v-if="icon">{{ icon }}</i>
    <slot></slot>
  </button>
</template>

<script setup lang="ts">
  import router from '@/router'
  import { computed } from 'vue'
  import Spinner from './Spinner.vue'

  const props = defineProps({
    type: {
      type: String,
      default: 'primary',
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
    width: {
      type: String,
      default: 'full',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    redirectTo: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: '',
    },
  })

  const typeClass = computed(() => {
    switch (props.type) {
      case 'primary':
        return 'bg-[#150F3E] text-white hover:opacity-85 focus:ring-2 focus:ring-blue-300'
      case 'outline':
        return 'bg-transparent text-[#150F3E] border border-[#150F3E] hover:bg-[#150F3E] hover:text-white focus:ring-2 focus:ring-blue-300 disabled:border-gray-900 disabled:bg-gray-900 disabled:text-white'
      case 'secondary':
        return 'bg-gray-700 text-white hover:bg-gray-600 focus:ring-2 focus:ring-gray-300'
      case 'info':
        return 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-2 focus:ring-blue-300'
      case 'success':
        return 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300'
      case 'danger':
        return 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300'
      default:
        return 'bg-gray-200 text-black hover:bg-gray-300 focus:ring-2 focus:ring-gray-100'
    }
  })

  const sizeClass = computed(() => {
    switch (props.width) {
      case 'full':
        return 'w-full'
      case 'lg':
        return 'btn-lg text-lg'
      case 'md':
        return 'btn-md text-md'
      case 'sm':
        return 'btn-sm text-sm'
      default:
        return ''
    }
  })

  const handleClick = () => {
    if (props.redirectTo) {
      router.push(props.redirectTo)
    }
  }
</script>

<style scoped>
  .loader {
    border: 2px solid transparent;
    border-top-color: white;
    border-radius: 50%;
    width: 1rem;
    height: 1rem;
  }
</style>
