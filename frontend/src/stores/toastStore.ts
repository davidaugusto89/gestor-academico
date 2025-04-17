import { defineStore } from 'pinia'

export const useToastStore = defineStore('toast', {
  state: () => ({
    toasts: [] as {
      message: string
      type: 'success' | 'error' | 'info' | 'warning'
    }[],
    nextToast: null as { message: string; type: 'success' | 'error' } | null, // Toast após redirecionamento
  }),
  actions: {
    addToast(message: string, type: 'success' | 'error') {
      this.toasts.push({ message, type })
    },
    setNextToast(message: string, type: 'success' | 'error') {
      this.nextToast = { message, type }
    },
    showNextToast() {
      if (this.nextToast) {
        this.addToast(this.nextToast.message, this.nextToast.type)
        this.nextToast = null
      }
    },
    removeToast(index: number) {
      this.toasts.splice(index, 1)
    },
    clearAll() {
      this.toasts = []
    },
  },
})
