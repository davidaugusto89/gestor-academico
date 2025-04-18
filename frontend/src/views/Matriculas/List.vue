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
      :advanced-filter="true"
      :data-link="'/matriculas'"
      :delete-link="'/matriculas/'"
      custom-delete="deleteMatricula"
    />
  </div>
</template>

<script setup lang="ts">
  import DataTable from '@/components/DataTable.vue'
  import request from '@/services/request'
  import { onMounted, ref } from 'vue'

  const alunosOptions = ref<{ value: string; label: string }[]>([])
  const turmasOptions = ref<{ value: string; label: string }[]>([])

  const filters = ref([
    { key: 'aluno_id', label: 'Aluno', type: 'select', options: alunosOptions },
    { key: 'turma_id', label: 'Turma', type: 'select', options: turmasOptions },
  ])

  const columns = [
    { key: 'aluno_nome', label: 'Aluno' },
    { key: 'aluno_cpf', label: 'CPF', mask: 'cpf' },
    { key: 'turma_nome', label: 'Turma' },
    { key: 'data_matricula', label: 'Data Matricula', mask: 'date' },
  ]

  const fetchOptions = async () => {
    try {
      const alunosResponse = await request.get('/alunos?itemsPerPage=100000')
      alunosOptions.value =
        alunosResponse.data.data?.map((a: any) => ({
          value: a.id,
          label: `[${a.id}] - ${a.nome} - ${a.cpf}`,
        })) || []

      const turmasResponse = await request.get('/turmas?itemsPerPage=100000')
      turmasOptions.value =
        turmasResponse.data.data?.map((t: any) => ({
          value: t.id,
          label: `[${t.id}] - ${t.nome}`,
        })) || []
    } catch (error) {
      console.error('Erro ao carregar alunos e turmas:', error)
    }
  }

  onMounted(fetchOptions)
</script>
