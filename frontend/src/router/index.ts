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

//---Alunos
import AlunoList from '@/views/Alunos/List.vue'
import AlunoForm from '@/views/Alunos/Form.vue'
import AlunoView from '@/views/Alunos/View.vue'

//---Turmas
import TurmaList from '@/views/Turmas/List.vue'
import TurmaForm from '@/views/Turmas/Form.vue'
import TurmaView from '@/views/Turmas/View.vue'

//---Matriculas
import MatriculaList from '@/views/Matriculas/List.vue'
import MatriculaForm from '@/views/Matriculas/Form.vue'

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
        path: 'alunos',
        component: AlunoList,
        name: 'AlunosList',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos/novo',
        component: AlunoForm,
        name: 'AlunosNovo',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos/visualizar/:id',
        component: AlunoView,
        name: 'AlunosVisualizar',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos/editar/:id',
        component: AlunoForm,
        name: 'AlunosEditar',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas',
        component: TurmaList,
        name: 'TurmasList',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas/novo',
        component: TurmaForm,
        name: 'TurmasNovo',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas/visualizar/:id',
        component: TurmaView,
        name: 'TurmasVisualizar',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas/editar/:id',
        component: TurmaForm,
        name: 'TurmasEditar',
        meta: { requiresAuth: true },
      },
      {
        path: 'matriculas',
        component: MatriculaList,
        name: 'MatriculasList',
        meta: { requiresAuth: true },
      },
      {
        path: 'matriculas/nova',
        component: MatriculaForm,
        name: 'MatriculasNova',
        meta: { requiresAuth: true },
      },
      // {
      //   path: 'matriculas/turma/:id',
      //   component: MatriculasPorTurma,
      //   name: 'MatriculasPorTurma',
      //   meta: { requiresAuth: true },
      // },
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
  const isAuthenticated = !!authStore.usuario

  if (to.meta.requiresAuth) {
    if (!isAuthenticated || authStore.isSessionExpired()) {
      authStore.logout()
      next({ name: 'Login' })
      return
    }
  }

  next()
})

export default router
