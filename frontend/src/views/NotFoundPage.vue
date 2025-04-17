<template>
  <div
    class="flex items-center justify-center min-h-screen bg-gradient-to-r from-gray-700 to-gray-900 px-4"
  >
    <div
      class="auth-container bg-white p-8 rounded-lg shadow-lg text-center w-full sm:max-w-md md:max-w-lg"
    >
      <!-- Ícone centralizado -->
      <i class="material-icons text-gray-500 text-[8rem] md:text-[10rem]">
        sentiment_dissatisfied
      </i>

      <!-- Título 404 -->
      <h2 class="text-6xl md:text-[8rem] font-bold text-gray-900 mb-4">404</h2>

      <!-- Mensagem de erro -->
      <p class="text-lg text-gray-700 mb-2">Oops! Página não encontrada.</p>
      <p class="text-gray-600 mb-6">
        A página que você está procurando não existe ou foi removida.
      </p>

      <!-- Botão para voltar -->
      <Button type="primary" @click="goToDashboard">Voltar ao Dashboard</Button>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useRouter } from 'vue-router'

  const router = useRouter()

  const goToDashboard = () => {
    router.push('/dashboard') // Redireciona para o dashboard
  }
</script>

<style scoped>
  .auth-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
</style>
