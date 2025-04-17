<template>
  <div class="c-register-page">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Criar Conta
    </h2>
    <p class="text-sm text-center text-gray-600 mb-8">
      Preencha os dados abaixo para se registrar.
    </p>
    <form @submit.prevent="handleRegister" class="space-y-6">
      <Row>
        <Input
          type="text"
          id="nome"
          v-model="nome"
          required
          placeholder="Informe seu nome"
          label="Nome"
          :error-message="errors?.nome"
        />
      </Row>
      <Row>
        <Input
          type="email"
          id="email"
          v-model="email"
          required
          placeholder="Informe seu e-mail"
          label="Email"
          :error-message="errors?.email"
        />
      </Row>
      <Row>
        <Input
          type="password"
          id="senha"
          v-model="senha"
          required
          placeholder="Crie uma senha"
          label="Senha"
          :error-message="errors?.senha"
        />
      </Row>
      <Row>
        <Input
          type="password"
          id="repitaSenha"
          v-model="repitaSenha"
          required
          placeholder="Repita sua senha"
          label="Repita sua senha"
          :error-message="errors?.repitaSenha"
        />
      </Row>
      <Row>
        <Button
          type="primary"
          @click="handleRegister"
          :is-loading="isLoading"
          class="w-full"
        >
          Registrar
        </Button>
      </Row>
    </form>
    <p class="mt-6 text-sm text-center text-gray-600">
      Já possui uma conta?
      <router-link to="/login" class="text-blue-500 hover:underline">
        Faça login aqui
      </router-link>
    </p>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useValidation } from '@/composables/useValidation'
  import { useRouter } from 'vue-router'
  import request from '@/services/request'
  import { useAuthStore } from '@/stores/authStore'
  import Swal from 'sweetalert2'

  const { errors, validate, hasErrors } = useValidation()
  const nome = ref('')
  const email = ref('')
  const senha = ref('')
  const repitaSenha = ref('')
  const isLoading = ref(false)
  const router = useRouter()

  const handleRegister = async () => {
    isLoading.value = true

    errors.value = {}
    validate('nome', nome.value, { required: true, minLength: 3 })
    validate('email', email.value, { required: true, type: 'email' })
    validate('senha', senha.value, { required: true, minLength: 6 })

    if (senha.value !== repitaSenha.value) {
      errors.value.repitaSenha = 'As senhas devem ser iguais'
    }

    if (hasErrors.value) {
      isLoading.value = false
      return
    }

    try {
      const response = await request.post('/auth/register', {
        nome: nome.value,
        email: email.value,
        senha: senha.value,
      })

      if (response.status === 200 || response.status === 201) {
        const authStore = useAuthStore()
        const usuario = {
          id: response.data?.id || 0,
          nome: response.data?.nome || '',
          email: response.data?.email || '',
          role: response.data?.role || '',
        }

        await Swal.fire({
          icon: 'success',
          title: 'Sucesso!',
          text: response.data.message || 'Conta criada com sucesso!',
          confirmButtonColor: '#3085d6',
        })

        authStore.login(usuario, response.data.access_token)
        router.push('/dashboard')
      }
    } finally {
      isLoading.value = false
    }
  }
</script>
