import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    usuario: JSON.parse(localStorage.getItem('usuario') || 'null') as {
      id: number
      nome: string
      role: string
    } | null,
    token: localStorage.getItem('token') || '',
  }),
  actions: {
    login(usuario: { id: number; nome: string; role: string }, token: string) {
      this.usuario = usuario
      this.token = token

      // Salva usuario e token no localStorage
      localStorage.setItem('usuario', JSON.stringify(usuario))
      localStorage.setItem('token', token)
    },

    logout() {
      this.usuario = null
      this.token = ''

      // Remove usuario e token do localStorage
      localStorage.removeItem('usuario')
      localStorage.removeItem('token')
    },

    checkAuth() {
      const token = localStorage.getItem('token')
      if (token) {
        return true
      }
      return false
    },
  },
})
