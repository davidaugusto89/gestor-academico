<template>
  <router-link :to="to" class="text-blue-500 hover:underline">
    <slot></slot>
  </router-link>
</template>

<script setup lang="ts">
  import { defineProps } from 'vue'

  const props = defineProps({
    to: {
      type: String,
      required: true,
    },
  })

  const { to } = props
</script>
