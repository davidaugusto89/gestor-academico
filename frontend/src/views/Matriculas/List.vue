<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">Matriculas</h2>

      <!-- Botão alinhado à direita -->
      <Button type="primary" width="small" redirect-to="/matriculas/nova">
        + Nova Matricula
      </Button>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :advanced-filter="false"
      :data-link="'/matriculas'"
      :delete-link="'/matriculas/'"
      custom-delete="deleteMatricula"
    />
  </div>
</template>

<script setup lang="ts">
  import DataTable from '@/components/DataTable.vue'
  import { ref } from 'vue'

  const filters = ref([])

  const columns = [
    { key: 'aluno_nome', label: 'Aluno' },
    { key: 'aluno_cpf', label: 'CPF', mask: 'cpf' },
    { key: 'turma_nome', label: 'Turma' },
    { key: 'data_matricula', label: 'Data Matricula', mask: 'date' },
  ]
</script>
