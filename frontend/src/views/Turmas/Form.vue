<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header
      :title="title"
      :title-previous="'Turmas'"
      :route-back="'/turmas'"
    />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Linha 1 -->
        <div class="grid grid-cols-1 gap-6">
          <Input
            type="text"
            id="nome"
            v-model="nome"
            required
            placeholder="Nome"
            label="Nome"
            :error-message="errors?.nome"
          />

          <Input
            type="textarea"
            id="descricao"
            v-model="descricao"
            required
            placeholder="Descrição"
            label="Descrição"
            :error-message="errors?.descricao"
          />
        </div>

        <!-- Botão -->
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
  import { ref, computed, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useValidation } from '@/composables/useValidation'
  import { useAuthStore } from '@/stores/authStore'
  import request from '@/services/request'
  import { toast } from 'vue3-toastify'
  import 'vue3-toastify/dist/index.css'

  const { errors, validate, hasErrors } = useValidation()

  const route = useRoute()
  const router = useRouter()
  const authStore = useAuthStore()

  const turmaId = computed(() => route.params.id as string | null)

  const title = computed(() => {
    return turmaId.value ? 'Editar Turma' : 'Novo Turma'
  })

  const isLoading = ref(false)
  const isToastActive = ref(false) // Adicionado para controlar toasts duplicados

  const idUsuario = authStore.usuario?.id ?? ''
  const nome = ref('')
  const descricao = ref('')

  const handleFormSubmit = async () => {
    if (isLoading.value) return

    isLoading.value = true

    // Limpa os erros
    errors.value = {}

    // Validações finais
    validate('nome', nome.value, { required: true, min: 3 })
    validate('descricao', descricao.value, { required: true, min: 3 })

    if (hasErrors.value) {
      if (!isToastActive.value) {
        // Verifica se já existe um toast ativo
        isToastActive.value = true
        toast.error('Por favor, preencha todos os campos corretamente.', {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
          onClose: () => (isToastActive.value = false),
        })
      }
      isLoading.value = false
      return
    }

    const data = {
      idUsuario,
      nome: nome.value,
      descricao: descricao.value,
    }

    try {
      const response = turmaId.value
        ? await request.put(`/turmas/${turmaId.value}`, data)
        : await request.post('/turmas', data)

      if (response.status === 200 || response.status === 201) {
        const message = `Turma ${
          turmaId.value ? 'editado' : 'adicionado'
        } com sucesso!`

        if (!isToastActive.value) {
          isToastActive.value = true
          toast.success(message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 2000,
            onClose: () => {
              isToastActive.value = false
              isLoading.value = false
              router.push('/turmas')
            },
          })
        }
      }
    } catch (error) {
      toast.error(
        `Erro ao ${
          turmaId.value ? 'editar' : 'adicionar'
        } a turma: ${error.message}`,
        {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        }
      )
      isLoading.value = false
    }
  }

  const loadInfo = async () => {
    try {
      isLoading.value = true
      const response = await request.get(`/turmas/${turmaId.value}`)

      if (response.status === 200) {
        const data = response.data
        nome.value = data.nome
        descricao.value = data.descricao
      }
    } catch (error) {
      console.error('Erro ao carregar as turmas:', error)
      router.push('/turmas')
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    if (turmaId.value) {
      loadInfo()
    }
  })
</script>

<style scoped></style>
