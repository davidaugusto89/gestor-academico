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
      :data="alunos"
      :advanced-filter="true"
      :is-loading="isLoading"
      :show-link="'/alunos/visualizar/'"
      :edit-link="'/alunos/editar/'"
      :delete-link="'/alunos/'"
    />
  </div>
</template>

<script setup lang="ts">
  import DataTable from '@/components/DataTable.vue'
  import request from '@/services/request'
  import { onMounted, ref } from 'vue'

  const filters = ref([
    { key: 'id', label: 'ID', placeholder: 'Filtrar por ID' },
    { key: 'nome', label: 'Nome', placeholder: 'Filtrar por Nome' },
    //{ key: 'email', label: 'Email', placeholder: 'Filtrar por Email' },
  ])

  const columns = [
    { key: 'id', label: 'ID' },
    { key: 'nome', label: 'Nome' },
    { key: 'cpf', label: 'CPF' },
    { key: 'email', label: 'Email' },
  ]

  const alunos = ref([])
  const isLoading = ref(true)

  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get('/alunos')

      if (response.status === 200) {
        alunos.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os alunos:', error)
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    loadInfo()
  })
</script>
