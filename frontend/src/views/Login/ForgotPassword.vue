<template>
  <div class="c-forgot-password-page">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Esqueceu sua senha?
    </h2>
    <p class="text-sm text-center text-gray-600 mb-8">
      Insira o seu e-mail para receber o link de recuperação.
    </p>
    <form @submit.prevent="handleForgotPassword" class="space-y-6">
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
        <Button
          type="primary"
          @click="handleForgotPassword"
          :is-loading="isLoading"
          class="w-full"
        >
          Enviar
        </Button>
      </Row>
    </form>
    <p class="mt-6 text-sm text-center text-gray-600">
      Lembrou sua senha?
      <router-link to="/login" class="text-blue-500 hover:underline">
        Faça login aqui
      </router-link>
    </p>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useValidation } from '@/composables/useValidation'
  import request from '@/services/request'
  import Swal from 'sweetalert2'

  const email = ref('')
  const isLoading = ref(false)
  const { errors, validate, hasErrors } = useValidation()

  const handleForgotPassword = async () => {
    isLoading.value = true
    errors.value = {}
    validate('email', email.value, { required: true, type: 'email' })

    if (hasErrors.value) {
      isLoading.value = false
      return
    }

    try {
      await request.post('/auth/forgot-password', { email: email.value })

      await Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: 'Um link de redefinição de senha foi enviado para o seu e-mail.',
      })
    } finally {
      isLoading.value = false
    }
  }
</script>
