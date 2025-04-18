<template>
  <div class="flex h-screen">
    <!-- Botão hambúrguer (mobile) -->
    <button
      class="md:hidden absolute top-2 right-4 z-50 bg-[#2C5364] text-white p-2 rounded-md shadow-lg"
      @click="toggleMenu"
    >
      <i class="material-icons">menu</i>
    </button>

    <!-- Menu Lateral -->
    <aside
      :class="[
        'bg-[#2C5364] text-white flex flex-col shadow-lg h-full w-60 z-40 transition-transform duration-300 md:relative',
        isOpen ? 'translate-x-0 fixed' : 'translate-x-[-100%] fixed',
        'md:translate-x-0 md:block',
      ]"
    >
      <!-- Logo -->
      <div class="flex items-center justify-center">
        <img src="/logotipo_white.png" alt="Logo" class="w-[75px] my-[2rem]" />
      </div>

      <!-- Navegação -->
      <nav class="flex-1 px-4 py-6">
        <ul class="space-y-2">
          <!-- Página Inicial -->
          <li>
            <router-link
              to="/dashboard"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-white/10 hover:pl-5"
              :class="
                $route.name === 'Dashboard'
                  ? 'bg-white/10 border-l-4 border-white pl-5'
                  : ''
              "
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                home
              </i>
              <span class="ml-2 font-medium">Página Inicial</span>
            </router-link>
          </li>

          <!-- Alunos -->
          <li>
            <router-link
              to="/alunos"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-white/10 hover:pl-5"
              :class="
                $route.name?.startsWith('Alunos')
                  ? 'bg-white/10 border-l-4 border-white pl-5'
                  : ''
              "
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                school
              </i>
              <span class="ml-2 font-medium">Alunos</span>
            </router-link>
          </li>

          <!-- Turmas -->
          <li>
            <router-link
              to="/turmas"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-white/10 hover:pl-5"
              :class="
                $route.name?.startsWith('Turmas')
                  ? 'bg-white/10 border-l-4 border-white pl-5'
                  : ''
              "
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                class
              </i>
              <span class="ml-2 font-medium">Turmas</span>
            </router-link>
          </li>

          <!-- Matrículas -->
          <li>
            <router-link
              to="/matriculas"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-white/10 hover:pl-5"
              :class="
                $route.name?.startsWith('Matrículas')
                  ? 'bg-white/10 border-l-4 border-white pl-5'
                  : ''
              "
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                how_to_reg
              </i>
              <span class="ml-2 font-medium">Matrículas</span>
            </router-link>
          </li>

          <!-- Usuários -->
          <li>
            <router-link
              to="/usuarios"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-white/10 hover:pl-5"
              :class="
                $route.name?.startsWith('Usuarios')
                  ? 'bg-white/10 border-l-4 border-white pl-5'
                  : ''
              "
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                manage_accounts
              </i>
              <span class="ml-2 font-medium">Usuários</span>
            </router-link>
          </li>

          <!-- Logout -->
          <li>
            <router-link
              to="/logout"
              class="group flex items-center py-2 px-4 rounded-lg transition-all duration-300 hover:bg-red-600"
              active-class="bg-red-600"
            >
              <i
                class="material-icons text-lg group-hover:scale-110 transition-transform"
              >
                logout
              </i>
              <span class="ml-2 font-medium">Sair</span>
            </router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Overlay (mobile) -->
    <div
      class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
      v-if="isOpen"
      @click="toggleMenu"
    />

    <!-- Conteúdo Principal -->
    <div class="flex-1 flex flex-col internal-container">
      <header
        class="h-16 bg-gray-100 border-b flex items-center px-6 justify-between"
      >
        <h1 class="text-lg font-semibold text-gray-800">Olá, {{ nome }}!</h1>
      </header>

      <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <router-view />
      </main>

      <footer
        class="h-16 bg-gray-100 border-t flex items-center justify-center"
      >
        <p class="text-sm text-gray-600">
          © 2025 - Desenvolvido por David Augusto
        </p>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useAuthStore } from '@/stores/authStore'

  const authStore = useAuthStore()
  const nome = authStore.usuario?.nome ?? ''
  const isOpen = ref(false)

  const toggleMenu = () => {
    isOpen.value = !isOpen.value
  }
</script>
