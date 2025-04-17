<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">Alunos</h2>

      <!-- Botão alinhado à direita -->
      <Button type="primary" width="small" redirect-to="/alunos/novo">
        + Novo Aluno
      </Button>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :data="alunos ?? []"
      :advanced-filter="true"
      :is-loading="isLoading"
      :data-link="'/alunos'"
      :show-link="'/alunos/visualizar/'"
      :edit-link="'/alunos/editar/'"
      :delete-link="'/alunos/'"
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
    { key: 'id', label: 'ID', size: '75px' },
    { key: 'nome', label: 'Nome' },
    { key: 'cpf', label: 'CPF', size: '200px', mask: 'cpf' },
    { key: 'email', label: 'Email' },
  ]

  const alunos = ref([])
  const isLoading = ref(true)
</script>
