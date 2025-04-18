<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header
      :title="`Visualizar Aluno #${alunoId}`"
      titlePrevious="Alunos"
      routeBack="/alunos"
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
              Data de Nascimento
            </label>
            <p class="text-gray-900">{{ formattedDate }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">CPF</label>
            <p class="text-gray-900">{{ formattedCpf }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              E-mail
            </label>
            <p class="text-gray-900">{{ data.email }}</p>
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
  import { formatCpf, formatDate } from '@/helpers/formatters'

  // Computed para obter o ID do aluno
  const route = useRoute()
  const alunoId = computed(() => route.params.id as string | null)

  const isLoading = ref(false)
  const data = ref<any>({})

  // Computed properties para os dados formatados
  const formattedCpf = computed(() => formatCpf(data.value.cpf))

  const formattedDate = computed(() => formatDate(data.value.nascimento))

  // Função para carregar os dados do aluno
  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get(`/alunos/${alunoId.value}`)

      if (response.status === 200) {
        data.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os dados do aluno:', error)
    } finally {
      setTimeout(() => {
        isLoading.value = false
      }, 500)
    }
  }

  // Chamada inicial para carregar os dados do aluno
  onMounted(() => {
    if (alunoId.value) {
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
