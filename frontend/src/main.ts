import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import VMoney from 'v-money3'

const app = createApp(App)

app.use(VMoney, {
  precision: 2,
  prefix: 'R$ ',
  thousands: '.',
  decimal: ',',
  locale: 'pt-BR',
})

// Habilitar warnings no console
app.config.errorHandler = (err) => {
  console.error('Vue Error:', err)
}

app.config.warnHandler = (msg, vm, trace) => {
  console.warn('Vue Warning:', msg, trace)
}

// Registrar todos os componentes globalmente com tipagem correta
const globalComponents = import.meta.glob('./components/global/*.vue', {
  eager: true,
}) as Record<string, { default: object }>

Object.entries(globalComponents).forEach(([path, definition]) => {
  const componentName = path
    .split('/')
    .pop()
    ?.replace(/\.\w+$/, '') as string
  app.component(componentName, definition.default)
})

app.use(createPinia())
app.use(router)

app.mount('#app')
