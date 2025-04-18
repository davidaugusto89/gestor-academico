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

        <!-- Alunos Matriculados -->
        <div class="bg-white mt-3">
          <h2 class="text-md font-bold mb-4">
            Alunos Matriculados ({{ dataAlunos.length }})
          </h2>

          <table
            class="min-w-full table-auto border border-gray-200"
            v-if="dataAlunos.length > 0"
          >
            <thead class="bg-gray-100">
              <tr>
                <th class="text-left px-4 py-2 border-b">Nome</th>
                <th class="text-left px-4 py-2 border-b">E-mail</th>
                <th class="text-left px-4 py-2 border-b">CPF</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(aluno, index) in dataAlunos"
                :key="index"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-2 border-b">{{ aluno.nome }}</td>
                <td class="px-4 py-2 border-b">{{ aluno.email }}</td>
                <td class="px-4 py-2 border-b">{{ formatCpf(aluno.cpf) }}</td>
              </tr>
            </tbody>
          </table>

          <p v-if="dataAlunos.length === 0" class="text-gray-500 mt-4">
            Nenhum aluno matriculado nesta turma.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import request from '@/services/request'
  import { formatCpf } from '@/helpers/formatters'

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
  const dataAlunos = ref<any>({})

  // Função para carregar os dados do turma
  const loadInfo = async () => {
    await loadTurma()
    await loadAlunos()
  }

  const loadTurma = async () => {
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

  const loadAlunos = async () => {
    try {
      isLoading.value = true
      const response = await request.get(`/matriculas/turma/${turmaId.value}`)

      if (response.status === 200) {
        dataAlunos.value = response.data
      }
    } catch (error) {
      console.error('Erro ao carregar os alunos:', error)
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
