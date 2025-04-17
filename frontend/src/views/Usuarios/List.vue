<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">Usuários</h2>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :data="usuarios"
      :advanced-filter="true"
      :data-link="'/usuarios'"
      :is-loading="isLoading"
    />
  </div>
</template>

<script setup lang="ts">
  import DataTable from '@/components/DataTable.vue'
  import { ref } from 'vue'

  const filters = ref([
    { key: 'id', label: 'ID', placeholder: 'Filtrar por ID' },
    { key: 'nome', label: 'Nome', placeholder: 'Filtrar por Nome' },
  ])

  const columns = [
    { key: 'id', label: 'ID' },
    { key: 'nome', label: 'Nome' },
    { key: 'email', label: 'E-mail' },
    { key: 'papel', label: 'Papel' },
  ]

  const usuarios = ref([])
  const isLoading = ref(true)
</script>
