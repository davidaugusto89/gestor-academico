<template>
  <div>
    <label :for="id" class="block text-sm font-medium text-gray-700 mb-2">
      <span :class="error ? 'text-red-500' : ''">{{ label }}</span>
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="inputClasses"
    >
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>

    <!-- Mensagem de erro -->
    <p v-if="errorMessage" class="text-red-500 text-sm mt-2">
      {{ errorMessage }}
    </p>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    modelValue: {
      type: String,
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    id: {
      type: String,
      required: true,
    },
    required: {
      type: Boolean,
      default: false,
    },
    errorMessage: {
      type: String,
      default: '',
    },
    height: {
      type: String,
      default: '10',
    },
  })

  const error = computed(() => props.errorMessage !== '')

  const inputClasses = computed(
    () =>
      `w-full ${props.height} py-2 px-3 border rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none ${props.errorMessage ? 'border-red-500' : 'border-gray-300'}`
  )
</script>
