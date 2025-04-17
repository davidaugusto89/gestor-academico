<template>
  <div class="flex h-screen">
    <!-- Menu Lateral -->
    <aside
      class="w-60 bg-gradient-to-b from-[#150F3E] to-[#322E50] text-white flex flex-col shadow-lg"
    >
      <!-- Logo -->
      <div class="flex items-center justify-center">
        <img src="/logotipo_white.png" alt="Logo" class="w-[150px] my-[2rem]" />
      </div>

      <!-- Navegação -->
      <nav class="flex-1 px-4 py-6">
        <ul class="space-y-4">
          <!-- Página Inicial -->
          <li>
            <router-link
              to="/dashboard"
              class="flex items-center py-2 px-4 rounded-lg hover:bg-[#322E50] transition-colors"
              active-class="bg-[#322E50]"
            >
              <i class="material-icons text-lg">home</i>
              <span class="ml-2">Página Inicial</span>
            </router-link>
          </li>

          <!-- Clientes -->
          <li>
            <router-link
              to="/clientes"
              class="flex items-center py-2 px-4 rounded-lg hover:bg-[#322E50] transition-colors"
              active-class="bg-[#322E50]"
            >
              <i class="material-icons text-lg">groups</i>
              <span class="ml-2">Clientes</span>
            </router-link>
          </li>

          <!-- Usuários -->
          <li>
            <router-link
              to="/usuarios"
              class="flex items-center py-2 px-4 rounded-lg hover:bg-[#322E50] transition-colors"
              active-class="bg-[#322E50]"
            >
              <i class="material-icons text-lg">person</i>
              <span class="ml-2">Usuários</span>
            </router-link>
          </li>

          <!-- Logout -->
          <li>
            <router-link
              to="/logout"
              class="flex items-center py-2 px-4 rounded-lg hover:bg-red-600 transition-colors"
              active-class="bg-red-600"
            >
              <i class="material-icons text-lg">logout</i>
              <span class="ml-2">Sair</span>
            </router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Conteúdo Principal -->
    <div class="flex-1 flex flex-col internal-container">
      <header
        class="h-16 bg-gray-100 border-b border-gray-300 flex items-center px-6"
      >
        <h1 class="text-lg font-semibold text-gray-800">Olá, {{ nome }}!</h1>
      </header>
      <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <router-view></router-view>
      </main>
      <footer
        class="h-16 bg-gray-100 border-t border-gray-300 flex items-center justify-center"
      >
        <p class="text-sm text-gray-600">
          &copy; 2025 - Todos os direitos reservados | Desenvolvido por: David
          Augusto
        </p>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useAuthStore } from '@/stores/authStore'

  const authStore = useAuthStore()
  const nome = authStore.usuario?.nome ?? ''
</script>

<style scoped>
  .internal-container {
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
