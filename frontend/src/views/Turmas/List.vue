<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">Turmas</h2>

      <!-- Botão alinhado à direita -->
      <Button type="primary" width="small" redirect-to="/turmas/novo">
        + Nova Turma
      </Button>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :data="turmas ?? []"
      :advanced-filter="true"
      :is-loading="isLoading"
      :data-link="'/turmas'"
      :show-link="'/turmas/visualizar/'"
      :edit-link="'/turmas/editar/'"
      :delete-link="'/turmas/'"
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
    { key: 'total_alunos', label: 'Total de alunos' },
  ]

  const turmas = ref([])
  const isLoading = ref(true)
</script>
