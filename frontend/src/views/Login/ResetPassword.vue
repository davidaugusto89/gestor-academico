<template>
  <div class="c-reset-password-page">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Redefinir Senha
    </h2>
    <p class="text-sm text-center text-gray-600 mb-8">
      Crie uma nova senha para sua conta.
    </p>
    <form @submit.prevent="handleResetPassword" class="space-y-6">
      <!-- Campo Senha -->
      <Row>
        <Input
          type="password"
          id="senha"
          v-model="senha"
          required
          placeholder="Informe sua nova senha"
          label="Nova Senha"
          :error-message="errors?.senha"
        />
      </Row>

      <!-- Campo Repita Senha -->
      <Row>
        <Input
          type="password"
          id="repitaSenha"
          v-model="repitaSenha"
          required
          placeholder="Repita sua nova senha"
          label="Repita a Nova Senha"
          :error-message="errors?.repitaSenha"
        />
      </Row>

      <!-- Botão Redefinir -->
      <Row>
        <Button
          type="primary"
          @click="handleResetPassword"
          :is-loading="isLoading"
          class="w-full"
        >
          Redefinir
        </Button>
      </Row>
    </form>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useValidation } from '@/composables/useValidation'
  import { useRouter, useRoute } from 'vue-router'
  import Swal from 'sweetalert2'
  import request from '@/services/request'

  // Referências reativas para os campos
  const senha = ref('')
  const repitaSenha = ref('')
  const isLoading = ref(false)
  const { errors, validate, hasErrors } = useValidation()
  const route = useRoute()
  const router = useRouter()

  const handleResetPassword = async () => {
    isLoading.value = true
    errors.value = {}

    // Validações
    validate('senha', senha.value, { required: true, minLength: 6 })
    validate('repitaSenha', repitaSenha.value, { required: true, minLength: 6 })
    if (senha.value !== repitaSenha.value) {
      errors.value.repitaSenha = 'As senhas devem ser iguais'
    }

    if (hasErrors.value) {
      isLoading.value = false
      return
    }

    // Captura o token da query string
    const token = route.query.token

    try {
      // Envia a requisição para redefinir a senha
      const response = await request.post('/auth/reset-password', {
        novaSenha: senha.value,
        token,
      })

      // Exibe mensagem de sucesso com SweetAlert
      await Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: response.data.message || 'Senha redefinida com sucesso!',
        confirmButtonColor: '#3085d6',
      })

      // Redireciona para a página de login
      router.push('/login')
    } catch (error) {
      // Exibe erro com SweetAlert
      await Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text:
          error.response?.data?.message ||
          'Ocorreu um erro ao redefinir a senha.',
        confirmButtonColor: '#d33',
      })
    } finally {
      isLoading.value = false
    }
  }
</script>
