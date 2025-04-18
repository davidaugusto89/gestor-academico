<template>
  <div class="p-6 space-y-6">
    <Header title="Dashboard (exemplo)" />

    <!-- Aviso de Dados Mockados -->
    <div class="text-center text-red-500 font-semibold">
      <p>ATENÇÃO: Estes dados são mockados para fins de demonstração.</p>
    </div>

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
              Total de Alunos
            </span>
            <p class="text-3xl font-bold text-gray-900">{{ totalAlunos }}</p>
          </div>
          <span class="material-icons text-blue-500 text-4xl">people</span>
        </div>

        <div
          class="bg-white shadow rounded-lg p-4 flex items-center justify-between"
        >
          <div>
            <span class="block text-sm font-medium text-gray-700">
              Total de Matrículas
            </span>
            <p class="text-3xl font-bold text-gray-900">{{ totalMatriculas }}</p>
          </div>
          <span class="material-icons text-green-500 text-4xl">person_add</span>
        </div>

        <div
          class="bg-white shadow rounded-lg p-4 flex items-center justify-between"
        >
          <div>
            <span class="block text-sm font-medium text-gray-700">
              Total de Turmas
            </span>
            <p class="text-3xl font-bold text-gray-900">{{ totalTurmas }}</p>
          </div>
          <span class="material-icons text-dark-500 text-4xl">school</span>
        </div>
      </template>
    </div>

    <!-- Tabela de Alunos Recentes -->
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold text-gray-800 mb-4">
        Alunos Recentes (últimos 5 registros)
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
              CPF
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Data de Nascimento
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              E-mail
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="aluno in alunosRecentes"
            :key="aluno.id"
            :class="{ 'bg-gray-50': aluno.id % 2 === 0 }"
          >
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ aluno.nome }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatCpfCnpj(aluno.cpf) }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatDate(aluno.dataNascimento) }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ aluno.email }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Tabela de Matrículas Recentes -->
    <div class="bg-white shadow rounded-lg p-4 mt-6">
      <h2 class="text-lg font-bold text-gray-800 mb-4">
        Matrículas Recentes (últimos 5 registros)
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
              Aluno
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Turma
            </th>
            <th
              class="border border-gray-200 px-4 py-2 text-left text-sm text-gray-600"
            >
              Data da Matrícula
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="matricula in matriculasRecentes"
            :key="matricula.id"
            :class="{ 'bg-gray-50': matricula.id % 2 === 0 }"
          >
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ matricula.alunoNome }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ matricula.turmaNome }}
            </td>
            <td class="border border-gray-200 px-4 py-2 text-gray-700">
              {{ formatDate(matricula.dataMatricula) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
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

  // Dados mockados
  const isLoading = ref(true)
  const totalAlunos = ref(0)
  const totalMatriculas = ref(0)
  const totalTurmas = ref(0)
  const alunos = ref([
    {
      id: 1,
      nome: "João da Silva",
      cpf: "123.456.789-01",
      dataNascimento: "2000-01-01",
      email: "joao.silva@email.com",
    },
    // Adicionar mais alunos mockados
  ])
  const turmas = ref([
    { id: 1, nome: "Turma A", descricao: "Turma de Introdução" },
    // Adicionar mais turmas mockadas
  ])
  const matriculas = ref([
    { id: 1, alunoNome: "João da Silva", turmaNome: "Turma A", dataMatricula: "2025-04-01" },
    // Adicionar mais matrículas mockadas
  ])

  // Computed para obter os últimos 5 alunos
  const alunosRecentes = computed(() => {
    return alunos.value
      .slice()
      .sort(
        (a, b) =>
          new Date(b.dataNascimento).getTime() -
          new Date(a.dataNascimento).getTime()
      )
      .slice(0, 5)
  })

  // Computed para obter as últimas 5 matrículas
  const matriculasRecentes = computed(() => {
    return matriculas.value
      .slice()
      .sort(
        (a, b) =>
          new Date(b.dataMatricula).getTime() -
          new Date(a.dataMatricula).getTime()
      )
      .slice(0, 5)
  })

  // Função para carregar os dados mockados
  const loadData = async () => {
    try {
      isLoading.value = true
      totalAlunos.value = alunos.value.length
      totalMatriculas.value = matriculas.value.length
      totalTurmas.value = turmas.value.length
    } catch (error) {
      console.error('Erro ao carregar os dados:', error)
    } finally {
      isLoading.value = false
    }
  }

  // Montar componente
  onMounted(() => {
    loadData()
  })
</script>
