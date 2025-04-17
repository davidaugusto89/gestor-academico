<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header
      title="Visualizar Cliente"
      titlePrevious="Clientes"
      routeBack="/clientes"
    />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="flex justify-center items-center h-[300px]" v-if="isLoading">
        <Spinner width="w-[100px]" height="h-[100px]" color="text-[#150F3E]" />
      </div>

      <div v-else>
        <!-- Linha 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Código
            </label>
            <p class="text-gray-900">{{ data.codigo }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <p class="text-gray-900">{{ data.nome }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              CPF/CNPJ
            </label>
            <p class="text-gray-900">{{ formattedCpfCnpj }}</p>
          </div>
        </div>

        <!-- Linha 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">CEP</label>
            <p class="text-gray-900">{{ formattedCep }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Logradouro
            </label>
            <p class="text-gray-900">{{ data.logradouro }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Endereço
            </label>
            <p class="text-gray-900">{{ data.endereco }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Número
            </label>
            <p class="text-gray-900">{{ data.numero }}</p>
          </div>
        </div>

        <!-- Linha 3 -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Bairro
            </label>
            <p class="text-gray-900">{{ data.bairro }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Cidade
            </label>
            <p class="text-gray-900">{{ data.cidade }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Complemento
            </label>
            <p class="text-gray-900">{{ data.complemento }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">UF</label>
            <p class="text-gray-900">{{ data.uf }}</p>
          </div>
        </div>

        <!-- Linha 4 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Telefone
            </label>
            <p class="text-gray-900">{{ formattedPhone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Limite de Crédito
            </label>
            <p class="text-gray-900">{{ formattedCreditLimit }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Validade
            </label>
            <p class="text-gray-900">{{ formattedValidity }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import request from '@/services/request'

  // Funções personalizadas para formatação
  const formatCpfCnpj = (value: string) => {
    if (!value) return ''
    return value.length <= 11
      ? value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4') // CPF
      : value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5') // CNPJ
  }

  const formatCep = (value: string) => {
    if (!value) return ''
    return value.replace(/(\d{5})(\d{3})/, '$1-$2') // CEP
  }

  const formatPhone = (value: string) => {
    if (!value) return ''
    return value.length === 10
      ? value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3') // Telefone fixo
      : value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3') // Celular
  }

  const formatCurrency = (value: number) => {
    if (!value) return ''
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    }).format(value) // Moeda brasileira
  }

  const formatDate = (value: string) => {
    if (!value) return ''
    const date = new Date(value)
    return date.toLocaleDateString('pt-BR') // Formato brasileiro de data
  }

  // Computed para obter o ID do cliente
  const route = useRoute()
  const clienteId = computed(() => route.params.id as string | null)

  const isLoading = ref(false)
  const data = ref<any>({})

  // Computed properties para os dados formatados
  const formattedCpfCnpj = computed(() => formatCpfCnpj(data.value.cpfCnpj))
  const formattedCep = computed(() => formatCep(data.value.cep))
  const formattedPhone = computed(() => formatPhone(data.value.fone))
  const formattedCreditLimit = computed(() =>
    formatCurrency(data.value.limiteCredito)
  )
  const formattedValidity = computed(() => formatDate(data.value.validade))

  // Função para carregar os dados do cliente
  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get(`/clientes/${clienteId.value}`)

      if (response.status === 200) {
        data.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os dados do cliente:', error)
    } finally {
      setTimeout(() => {
        isLoading.value = false
      }, 500)
    }
  }

  // Chamada inicial para carregar os dados do cliente
  onMounted(() => {
    if (clienteId.value) {
      loadInfo()
    }
  })
</script>

<style scoped>
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
</style>
