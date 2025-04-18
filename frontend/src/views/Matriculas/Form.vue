<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header
      :title="title"
      :title-previous="'Matrículas'"
      :route-back="'/matriculas'"
    />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
          <!-- Aluno -->
          <Select
            id="aluno"
            label="Aluno"
            :options="alunos"
            v-model="form.aluno_id"
            :error-message="errors?.aluno_id"
            :required="true"
          />

          <!-- Turma -->
          <Select
            id="turma"
            label="Turma"
            :options="turmas"
            v-model="form.turma_id"
            :error-message="errors?.turma_id"
            :required="true"
          />
        </div>

        <div>
          <Button
            type="primary"
            @click="handleFormSubmit"
            :is-loading="isLoading"
            class="w-full"
          >
            Salvar
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useValidation } from '@/composables/useValidation'
  import request from '@/services/request'
  import { toast } from 'vue3-toastify'
  import 'vue3-toastify/dist/index.css'

  const title = ref('Nova Matrícula')
  const route = useRoute()
  const router = useRouter()
  const isLoading = ref(false)
  const isToastActive = ref(false)

  const alunos = ref<{ value: string; label: string }[]>([])
  const turmas = ref<{ value: string; label: string }[]>([])

  const form = ref({
    aluno_id: '',
    turma_id: '',
  })

  const { errors, validate, hasErrors } = useValidation()

  const handleFormSubmit = async () => {
    if (isLoading.value) return

    errors.value = {}

    validate('aluno_id', form.value.aluno_id, { required: true })
    validate('turma_id', form.value.turma_id, { required: true })

    if (hasErrors.value) {
      if (!isToastActive.value) {
        isToastActive.value = true
        toast.error('Preencha todos os campos corretamente.', {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
          onClose: () => (isToastActive.value = false),
        })
      }
      return
    }

    isLoading.value = true

    try {
      const response = await request.post('/matriculas', {
        aluno_id: form.value.aluno_id,
        turma_id: form.value.turma_id,
      })

      if ([200, 201].includes(response.status)) {
        toast.success('Matrícula criada com sucesso!', {
          autoClose: 2000,
          onClose: () => {
            isToastActive.value = false
            router.push('/matriculas')
          },
        })
      }
    } catch (error) {
      toast.error('Erro ao criar a matrícula. Tente novamente.', {
        autoClose: 3000,
      })
      isLoading.value = false
    }
  }

  const fetchOptions = async () => {
    try {
      const alunosResponse = await request.get('/alunos?itemsPerPage=100000')
      alunos.value =
        alunosResponse.data.data?.map((a: any) => ({
          value: a.id,
          label: `[${a.id}] - ${a.nome} - ${a.cpf}`,
        })) || []

      const turmasResponse = await request.get('/turmas?itemsPerPage=100000')
      turmas.value =
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
