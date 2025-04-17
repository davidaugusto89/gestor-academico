<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">Clientes</h2>

      <!-- Botão alinhado à direita -->
      <Button type="primary" width="small" redirect-to="/clientes/novo">
        + Novo Cliente
      </Button>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :data="clientes"
      :advanced-filter="true"
      :is-loading="isLoading"
      :show-link="'/clientes/visualizar/'"
      :edit-link="'/clientes/editar/'"
      :delete-link="'/clientes/'"
    />
  </div>
</template>

<script setup lang="ts">
  import DataTable from '@/components/DataTable.vue'
  import request from '@/services/request'
  import { onMounted, ref } from 'vue'

  const filters = ref([
    { key: 'codigo', label: 'Código', placeholder: 'Filtrar por Código' },
    { key: 'nome', label: 'Nome', placeholder: 'Filtrar por Nome' },
    { key: 'cidade', label: 'Cidade', placeholder: 'Filtrar por Cidade' },
    { key: 'cep', label: 'CEP', placeholder: 'Filtrar por CEP' },
  ])

  const columns = [
    { key: 'id', label: 'ID' },
    { key: 'codigo', label: 'Código' },
    { key: 'nome', label: 'Nome' },
    { key: 'cidade', label: 'Cidade' },
    { key: 'cep', label: 'CEP' },
  ]

  const clientes = ref([])
  const isLoading = ref(true)

  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get('/clientes')

      if (response.status === 200) {
        clientes.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os clientes:', error)
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    loadInfo()
  })
</script>
