<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header
      :title="`Visualizar Turma #${turmaId}`"
      titlePrevious="Turmas"
      routeBack="/turmas"
    />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="flex justify-center items-center h-[300px]" v-if="isLoading">
        <Spinner width="w-[100px]" height="h-[100px]" color="text-[#150F3E]" />
      </div>

      <div v-else>
        <!-- Linha 1 -->
        <div class="grid grid-cols-1 gap-6 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <p class="text-gray-900">{{ data.nome }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              Descrição
            </label>
            <p class="text-gray-900">{{ data.descricao }}</p>
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


  const formatDate = (value: string) => {
    if (!value) return ''
    const date = new Date(value)
    return date.toLocaleDateString('pt-BR') // Formato brasileiro de data
  }

  // Computed para obter o ID do turma
  const route = useRoute()
  const turmaId = computed(() => route.params.id as string | null)

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

  // Função para carregar os dados do turma
  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get(`/turmas/${turmaId.value}`)

      if (response.status === 200) {
        data.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os dados do turma:', error)
    } finally {
      setTimeout(() => {
        isLoading.value = false
      }, 500)
    }
  }

  // Chamada inicial para carregar os dados do turma
  onMounted(() => {
    if (turmaId.value) {
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
