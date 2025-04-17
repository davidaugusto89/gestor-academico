<template>
  <div class="flex items-center justify-between pb-4">
    <!-- Título alinhado à esquerda -->
    <h2 class="text-2xl font-bold text-gray-900 flex items-center space-x-2">
      <Link :to="routeBack" class="hover:underline" v-if="showPrevious">
        {{ titlePrevious }}
      </Link>
      <i class="material-icons" v-if="showPrevious">keyboard_arrow_right</i>
      <span>{{ title }}</span>
    </h2>

    <!-- Botão alinhado à direita -->
    <Button
      type="outline"
      width="small"
      :redirect-to="routeBack"
      v-if="routeBack"
    >
      {{ textButtonBack }}
    </Button>
  </div>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue'
  const props = defineProps({
    title: String,
    titlePrevious: String,
    routeBack: String,
    textButtonBack: {
      type: String,
      default: 'Voltar',
    },
  })

  const showPrevious = computed(() => {
    return props.routeBack && props.titlePrevious
  })
</script>
