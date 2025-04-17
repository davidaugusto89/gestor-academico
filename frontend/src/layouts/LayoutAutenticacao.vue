<template>
  <div class="flex min-h-screen">
    <!-- Seção à Esquerda -->
    <div
      class="hidden md:flex flex-col justify-center items-center w-1/2 bg-gradient-to-br from-[#150F3E] to-[#322E50] text-white p-8"
    >
      <div class="text-center">
        <img src="/logotipo_white.png" alt="Logo" class="w-[400px]" />
      </div>
      <div class="mt-8"></div>
    </div>

    <!-- Seção de Autenticação -->
    <div
      class="flex flex-col justify-center items-center w-full md:w-1/2 bg-white p-8"
    >
      <div class="auth-container w-full sm:max-w-md">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
  .auth-container {
    animation: fadeIn 0.5s ease-in-out;
  }

  /* Animação de fade-in */
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
