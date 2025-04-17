<template>
  <div class="p-6 space-y-6">
    <Header title="Dashboard (exemplo)" />

    <!-- Indicadores -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div
        class="bg-white shadow rounded-lg p-4 flex items-center justify-center"
        v-if="isLoading"
      >
        <Spinner width="w-[100px]" height="h-[100px]" color="text-[#150F3E]" />
      </div>
      <template v-else>
        <div
          class="bg-white shadow rounded-lg p-4 flex items-center justify-between"
        >
          <div>
            <span class="block text-sm font-medium text-gray-700">
              Total de Clientes
            </span>
            <p class="text-3xl font-bold text-gray-900">{{ totalClientes }}</p>
          </div>
          <span class="material-icons text-blue-500 text-4xl">people</span>
        </div>

        <div
          class="bg-white shadow rounded-lg p-4 flex items-center justify-between"
        >
          <div>
            <span class="block text-sm font-medium text-gray-700">
              Novos Clientes na Semana
            </span>
            <p class="text-3xl font-bold text-gray-900">{{ novosClientes }}</p>
          </div>
          <span class="material-icons text-green-500 text-4xl">person_add</span>
        </div>

        <div
          class="bg-white shadow rounded-lg p-4 flex items-center justify-between"
        >
          <div>
            <span class="block text-sm font-medium text-gray-700">
              Total de Limite de Crédito
            </span>
            <p class="text-3xl font-bold text-gray-900">
              {{ formattedTotalLimite }}
            </p>
          </div>
          <span class="material-icons text-dark-500 text-4xl">credit_card</span>
        </div>
      </template>
    </div>

    <!-- Tabela de Clientes Recentes -->
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold text-gray-800 mb-4">
        Clientes Recentes (últimos 5 registros)
      </h2>
      <div class="flex justify-center items-center h-[200px]" v-if="isLoading">
        <Spinner width="w-[100px]" height="h-[100px]" color="text-[#150F3E]" />
      </div>

      <table class="w-full table-auto border-collapse border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Nome
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              CPF/CNPJ
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Data de Cadastro
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Validade
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Limite de Crédito
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="cliente in clientesRecentes"
            :key="cliente.id"
            :class="{ 'bg-gray-50': cliente.id % 2 === 0 }"
          >
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ cliente.nome }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatCpfCnpj(cliente.cpfCnpj) }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatDate(cliente.dataHoraCadastro) }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatDate(cliente.validade) }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatCurrency(cliente.limiteCredito) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
  import request from '@/services/request'
  import { ref, computed, onMounted } from 'vue'

  // Funções de formatação
  const formatCpfCnpj = (value: string) => {
    if (!value) return ''
    return value.length <= 11
      ? value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
      : value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
  }

  const formatCurrency = (value: number) =>
    new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    }).format(value)

  const formatDate = (value: string) => {
    if (!value) return ''
    const date = new Date(value)
    return date.toLocaleDateString('pt-BR')
  }

  // Dados
  const isLoading = ref(true)
  const totalClientes = ref(0)
  const novosClientes = ref(0)
  const clientes = ref([])

  // Computed para obter os últimos 5 clientes
  const clientesRecentes = computed(() => {
    // Ordena os clientes pela data de cadastro (mais recentes primeiro)
    return clientes.value
      .slice()
      .sort(
        (a, b) =>
          new Date(b.dataHoraCadastro).getTime() -
          new Date(a.dataHoraCadastro).getTime()
      )
      .slice(0, 5)
  })

  // Computed para formatar créditos vencidos
  const formattedTotalLimite = computed(() =>
    formatCurrency(
      clientes.value
        .filter((cliente) => new Date(cliente.validade) > new Date())
        .reduce((total, cliente) => total + (cliente.limiteCredito || 0), 0)
    )
  )

  // Carregar dados de clientes
  const loadClientes = async () => {
    try {
      isLoading.value = true
      const response = await request.get('/clientes')
      if (response.status === 200 || response.status === 304) {
        clientes.value = response.data || []
        totalClientes.value = clientes.value.length
        novosClientes.value = clientes.value.filter((cliente) => {
          const cadastro = new Date(cliente.dataHoraCadastro)
          const hoje = new Date()
          const umaSemanaAtras = new Date()
          umaSemanaAtras.setDate(hoje.getDate() - 7)
          return cadastro >= umaSemanaAtras
        }).length
      }
    } catch (error) {
      console.error('Erro ao carregar os clientes:', error)
    } finally {
      isLoading.value = false
    }
  }

  // Montar componente
  onMounted(() => {
    loadClientes()
  })
</script>
