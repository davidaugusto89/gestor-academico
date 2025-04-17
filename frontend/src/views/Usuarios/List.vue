<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4">
      <!-- Título alinhado à esquerda -->
      <h2 class="text-2xl font-bold text-gray-900">usuarios</h2>

      <!-- Botão alinhado à direita -->
      <Button type="primary" width="small" redirect-to="/usuarios/novo">
        + Novo Cliente
      </Button>
    </div>

    <DataTable
      v-model:filters="filters"
      :columns="columns"
      :data="usuarios"
      :advanced-filter="true"
      :is-loading="isLoading"
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
    { key: 'email', label: 'E-mail', placeholder: 'Filtrar por E-mail' },
    { key: 'role', label: 'Acesso', placeholder: 'Filtrar por Acesso' },
  ])

  const columns = [
    { key: 'id', label: 'ID' },
    { key: 'nome', label: 'Nome' },
    { key: 'email', label: 'E-mail' },
    { key: 'role', label: 'Acesso' },
  ]

  const usuarios = ref([])
  const isLoading = ref(true)

  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get('/usuarios')

      if (response.status === 200) {
        usuarios.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os usuarios:', error)
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    loadInfo()
  })
</script>
