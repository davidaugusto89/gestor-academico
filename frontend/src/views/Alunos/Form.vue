<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header :title="title" :title-previous="'Alunos'" :route-back="'/alunos'" />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Linha 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
            type="text"
            id="nascimento"
            v-model="nascimento"
            required
            format-type="data"
            placeholder="Nascimento"
            label="Data de Nascimento"
            :error-message="errors?.nascimento"
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Input
            type="text"
            id="cpf"
            v-model="cpf"
            required
            placeholder="CPF"
            label="CPF"
            :error-message="errors?.cpf"
            :max-length="18"
            format-type="cpf"
          />

          <Input
            type="text"
            id="email"
            v-model="email"
            required
            placeholder="E-mail"
            label="E-mail"
            :error-message="errors?.email"
          />

          <Input
            type="password"
            id="senha"
            v-model="senha"
            :required="!alunoId"
            placeholder="Senha"
            label="Senha"
            :error-message="errors?.senha"
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
  import { formatDate } from '@/helpers/formatters'

  const { errors, validate, hasErrors } = useValidation()

  const route = useRoute()
  const router = useRouter()
  const authStore = useAuthStore()

  const alunoId = computed(() => route.params.id as string | null)

  const title = computed(() => {
    return alunoId.value ? 'Editar Aluno' : 'Novo Aluno'
  })

  const isLoading = ref(false)
  const isToastActive = ref(false) // Adicionado para controlar toasts duplicados

  const idUsuario = authStore.usuario?.id ?? ''
  const nome = ref('')
  const nascimento = ref('')
  const cpf = ref('')
  const email = ref('')
  const senha = ref('')

  const handleFormSubmit = async () => {
    if (isLoading.value) return

    isLoading.value = true

    // Limpa os erros
    errors.value = {}

    // Validações finais
    validate('nome', nome.value, { required: true, min: 3 })
    validate('nascimento', nascimento.value, { required: true })
    validate('cpf', cpf.value, { required: true, type: 'cpf' })
    validate('email', email.value, { required: true, type: 'email' })

    if (!alunoId.value && senha.value) {
      validate('senha', senha.value, { required: true, type: 'passwordStrong' })
    }

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
      nascimento: nascimento.value,
      cpf: cpf.value,
      email: email.value,
      senha: senha.value,
    }

    try {
      const response = alunoId.value
        ? await request.put(`/alunos/${alunoId.value}`, data)
        : await request.post('/alunos', data)

      if (response.status === 200 || response.status === 201) {
        const message = `Aluno ${
          alunoId.value ? 'editado' : 'adicionado'
        } com sucesso!`

        if (!isToastActive.value) {
          isToastActive.value = true
          toast.success(message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 2000,
            onClose: () => {
              isToastActive.value = false
              isLoading.value = false
              router.push('/alunos')
            },
          })
        }
      }
    } catch (error) {
      toast.error(
        `Erro ao ${
          alunoId.value ? 'editar' : 'adicionar'
        } o aluno: ${error.message}`,
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
      const response = await request.get(`/alunos/${alunoId.value}`)

      if (response.status === 200) {
        const data = response.data
        nome.value = data.nome
        nascimento.value = formatDate(data.nascimento)
        cpf.value = data.cpf
        email.value = data.email
        senha.value = ''
      }
    } catch (error) {
      console.error('Erro ao carregar os alunos:', error)
      router.push('/alunos')
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    if (alunoId.value) {
      loadInfo()
    }
  })
</script>

<style scoped></style>
