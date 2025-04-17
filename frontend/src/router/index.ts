import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
//-Layout
import LayoutAutenticacao from '@/layouts/LayoutAutenticacao.vue'
import LayoutInterno from '@/layouts/LayoutInterno.vue'

//--Paginas externas relacionadas a login
import LoginPage from '@/views/Login/Login.vue'
import ResetPassword from '@/views/Login/ResetPassword.vue'
import Register from '@/views/Login/Register.vue'
import ForgotPassword from '@/views/Login/ForgotPassword.vue'

//--Paginas internas
//---Dashboard
import Dashboard from '@/views/Dashboard/Dashboard.vue'

//---Clientes
import ClientesList from '@/views/Clientes/List.vue'
import ClienteForm from '@/views/Clientes/Form.vue'
import ClienteView from '@/views/Clientes/View.vue'

//---Usuarios
import UsuariosList from '@/views/Usuarios/List.vue'

//Página 404
import NotFoundPage from '@/views/NotFoundPage.vue'

const routes = [
  {
    path: '/login',
    component: LayoutAutenticacao,
    children: [
      {
        path: '',
        component: LoginPage,
        name: 'Login',
      },
    ],
    redirect: { name: 'Login' },
  },
  {
    path: '/register',
    component: LayoutAutenticacao,
    children: [
      {
        path: '',
        component: Register,
        name: 'Register',
      },
    ],
  },
  {
    path: '/forgot-password',
    component: LayoutAutenticacao,
    children: [
      {
        path: '',
        component: ForgotPassword,
        name: 'ForgotPassword',
      },
    ],
  },
  {
    path: '/reset-password',
    component: LayoutAutenticacao,
    children: [
      {
        path: '',
        component: ResetPassword,
        name: 'ResetPassword',
      },
    ],
  },

  {
    path: '/logout',
    name: 'Logout',
    beforeEnter(to, from, next) {
      const authStore = useAuthStore()
      authStore.logout()
      next({ name: 'Login' })
    },
  },

  {
    path: '/',
    component: LayoutInterno,
    meta: { requiresAuth: true },
    children: [
      {
        path: '/dashboard',
        component: Dashboard,
        name: 'Dashboard',
        meta: { requiresAuth: true },
      },
      {
        path: 'clientes',
        component: ClientesList,
        name: 'ClientesList',
        meta: { requiresAuth: true },
      },
      {
        path: 'clientes/novo',
        component: ClienteForm,
        name: 'ClientesNovo',
        meta: { requiresAuth: true },
      },
      {
        path: 'clientes/visualizar/:id',
        component: ClienteView,
        name: 'ClientesVisualizar',
        meta: { requiresAuth: true },
      },
      {
        path: 'clientes/editar/:id',
        component: ClienteForm,
        name: 'EditClient',
        meta: { requiresAuth: true },
      },
      {
        path: 'usuarios',
        component: UsuariosList,
        name: 'UsuariosList',
        meta: { requiresAuth: true },
      },
    ],
  },

  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFoundPage,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// Guard para verificar autenticação antes de acessar rotas privadas
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = !!authStore.usuario // Verifica se o usuário está autenticado

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'Login' })
  } else {
    next()
  }
})

export default router
