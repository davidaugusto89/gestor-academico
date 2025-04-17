<template>
  <div class="c-login-page">
    <!-- Título -->
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Bem-vindo de volta!
    </h2>
    <p class="text-sm text-center text-gray-600 mb-8">
      Preencha as informações abaixo para acessar o sistema.
    </p>

    <!-- Formulário de Login -->
    <form @submit.prevent="handleLogin" class="space-y-6">
      <!-- Campo de Email -->
      <Row>
        <Input
          type="text"
          id="email"
          v-model="email"
          required
          placeholder="Informe seu e-mail"
          label="Email"
          :error-message="errors?.email"
        />
      </Row>

      <!-- Campo de Senha -->
      <Row>
        <Input
          type="password"
          id="password"
          v-model="senha"
          required
          placeholder="Informe sua senha"
          label="Senha"
          :error-message="errors?.senha"
        />
      </Row>

      <!-- Botão Entrar -->
      <Row>
        <Button
          type="primary"
          @click="handleLogin"
          :is-loading="isLoading"
          class="w-full"
        >
          Entrar
        </Button>
      </Row>
    </form>

    <!-- Links de Registro e Esqueci a Senha -->
    <div class="mt-6 text-center">
      <p class="text-sm text-gray-600">
        Não tem uma conta?
        <router-link to="/register" class="text-blue-500 hover:underline">
          Crie uma aqui
        </router-link>
      </p>
      <p class="text-sm text-gray-600 mt-2">
        <router-link
          to="/forgot-password"
          class="text-blue-500 hover:underline"
        >
          Esqueceu a senha?
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useValidation } from '@/composables/useValidation'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '@/stores/authStore'
  import request from '@/services/request'

  const { errors, validate, hasErrors } = useValidation()
  const email = ref('')
  const senha = ref('')
  const router = useRouter()
  const isLoading = ref(false)

  // Função de login
  const handleLogin = async () => {
    isLoading.value = true

    // Limpa os erros
    errors.value = {}

    // Validações finais antes do envio
    validate('email', email.value, {
      required: true,
      minLength: 6,
      type: 'email',
    })
    validate('senha', senha.value, { required: true, minLength: 6 })

    // Se não houver erros, processa os dados
    if (hasErrors.value) {
      isLoading.value = false
      return
    }

    const data = {
      email: email.value,
      senha: senha.value,
    }

    try {
      const response = await request.post('/auth/login', data)

      if (response.status === 200) {
        const authStore = useAuthStore()
        const usuario = {
          id: response.data?.id || 0,
          nome: response.data?.nome || '',
          email: response.data?.email || '',
          role: response.data?.role || '',
        }

        authStore.login(usuario, response.data.access_token)
        router.push('/dashboard')
      }
    } finally {
      isLoading.value = false
    }
  }
</script>

<style scoped>
  .auth-container {
    animation: fadeIn 0.5s ease-in-out;
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }

    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
