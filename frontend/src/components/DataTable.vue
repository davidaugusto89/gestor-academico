<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <!-- Loading -->
    <div
      v-if="props.dataLink ? isLoadingInternal : props.isLoading"
      class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50 z-10"
    >
      <div class="loader">Carregando...</div>
    </div>

    <!-- Filtros Avançados -->
    <div
      v-if="showAdvancedFilters"
      class="flex flex-wrap gap-3 bg-gray-50 p-4 rounded-lg items-end"
    >
      <strong class="text-gray-700 w-full">Filtros:</strong>
      <div
        v-for="filter in filters"
        :key="filter.key"
        class="flex-1 min-w-[200px]"
      >
        <Input
          type="text"
          :id="filter.key"
          v-model="filter.value"
          :placeholder="filter.placeholder"
          width="full"
          :label="filter.label"
        />
      </div>

      <div class="flex mb-4">
        <Button type="outline" @click="clearFilters">
          Limpar Filtros
        </Button>
      </div>

      <div class="flex mb-4">
        <Button type="primary" @click="applyFilters">
          Aplicar Filtros
        </Button>
      </div>
    </div>

    <hr v-if="showAdvancedFilters" class="mx-4 mt-0 mb-4 border-gray-300" />

    <!-- Tabela responsiva -->
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 min-w-full">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th v-for="column in columns" :key="column.key" class="px-5 py-3 whitespace-nowrap">
              {{ column.label }}
            </th>
            <th class="px-6 py-3 whitespace-nowrap" v-if="showActions">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in localData"
            :key="item.id"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 even:bg-gray-50 hover:bg-gray-100 dark:even:bg-gray-700 dark:hover:bg-gray-600 transition-colors"
          >
            <td v-for="column in columns" :key="column.key" class="px-5 py-4 whitespace-nowrap">
              <span v-if="!column?.mask">{{ item[column.key] }}</span>
              <span v-else>{{ applyMask(item[column.key], column?.mask) }}</span>
            </td>
            <td class="px-6 py-4 gap-2 flex flex-wrap" v-if="showActions">
              <Button
                type="info"
                width="small"
                icon="visibility"
                :redirect-to="showLink + item.id"
                v-if="showLink"
              >
                Visualizar
              </Button>
              <Button
                type="success"
                width="small"
                :redirect-to="editLink + item.id"
                icon="edit"
                v-if="editLink"
              >
                Editar
              </Button>
              <Button
                type="danger"
                width="small"
                icon="delete"
                v-if="deleteLink"
                @click="deleteItem(item.id)"
                :is-loading="isLoadingDelete"
              >
                Excluir
              </Button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <div class="flex flex-col md:flex-row justify-between items-center px-4 py-3 bg-white dark:bg-gray-900 gap-4">
      <Select v-model="itemsPerPage" :options="itemsPerPageOptions" width="full" />

      <span class="text-sm text-gray-700 dark:text-gray-400">
        Mostrando {{ currentPage * itemsPerPage }} de {{ totalItems }} itens
      </span>

      <nav class="inline-flex flex-wrap gap-2">
        <!-- Primeira página -->
        <Button @click="changePage(1)" :disabled="currentPage === 1" width="sm">
          <i class="material-icons">first_page</i>
        </Button>

        <!-- Página anterior -->
        <Button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" width="sm">
          <i class="material-icons">chevron_left</i>
        </Button>

        <!-- Páginas visíveis -->
        <span v-for="page in visiblePages" :key="page">
          <Button
            @click="changePage(page)"
            type="outline"
            :disabled="page === currentPage"
            width="sm"
          >
            {{ page }}
          </Button>
        </span>

        <!-- Próxima página -->
        <Button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" width="sm">
          <i class="material-icons">chevron_right</i>
        </Button>

        <!-- Última página -->
        <Button @click="changePage(totalPages)" :disabled="currentPage === totalPages" width="sm">
          <i class="material-icons">last_page</i>
        </Button>
      </nav>
    </div>
  </div>
</template>


<script setup lang="ts">
import request from '@/services/request'
import Swal from 'sweetalert2'
import { ref, computed, onMounted, watch } from 'vue'
import { formatCpf, formatDate, formatCpfCnpj, formatCep, formatPhone, formatCurrency } from '@/helpers/formatters'

interface Column {
  key: string
  label: string,
  size?: string,
  mask?: string
}

const props = defineProps<{
  columns: Column[]
  filters?: any[]
  advancedFilter: boolean
  showLink?: string
  editLink?: string
  deleteLink?: string
  dataLink?: string
}>()

const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)
const localData = ref<any[]>([])
const isLoading = ref(false)
const isLoadingDelete = ref(false)
const showAdvancedFilters = ref(props.advancedFilter)

const itemsPerPageOptions = [
  { value: 5, label: '5' },
  { value: 10, label: '10' },
  { value: 25, label: '25' },
  { value: 50, label: '50' },
]

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

const showActions = computed(() => {
  return props.showLink || props.editLink || props.deleteLink
})

const loadInfo = async () => {
  if (!props.dataLink) return
  isLoading.value = true
  localData.value = []
  totalItems.value = 0
  try {
    const optionsFilters = [...props.filters]
    let filters = ''
    if (optionsFilters.length > 0) {
      optionsFilters.forEach((filter) => {
        if (filter.value === null || typeof filter.value === 'undefined') return
        filters += `&${filter.key}=${filter.value}`
      })
    }

    const response = await request.get(`${props.dataLink}?page=${currentPage.value}&itemsPerPage=${itemsPerPage.value}${filters}`)
    if (response.status === 200) {
      localData.value = response.data.data || []
      totalItems.value = response.data.total || 0
    }
  } catch (error) {
    console.error('Erro ao carregar os dados:', error)
  } finally {
    isLoading.value = false
  }
}

const visiblePages = computed(() => {
  const pagesToShow = 5
  const half = Math.floor(pagesToShow / 2)

  let start = currentPage.value - half
  let end = currentPage.value + half

  if (start < 1) {
    start = 1
    end = pagesToShow
  }

  if (end > totalPages.value) {
    end = totalPages.value
    start = totalPages.value - pagesToShow + 1
    if (start < 1) start = 1
  }

  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})

const changePage = (page: number) => {
  if (page > 0 && page <= totalPages.value) {
    currentPage.value = page
    loadInfo()
  }
}

const deleteItem = async (id: number) => {
  Swal.fire({
    title: 'Tem certeza?',
    text: `Deseja excluir o item #${id}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir!',
  }).then(async (result) => {
    if (result.isConfirmed) {
      isLoadingDelete.value = true
      await request.delete(props.deleteLink + id)
      isLoadingDelete.value = false

      //Swal.fire('Sucesso!', 'Item excluído com sucesso.', 'success')
      Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: 'Item excluído com sucesso.',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        timer: 3000,
      })
      loadInfo()
    }
  })
}

const applyMask = (value: string, mask: string) => {
  if (!value) return ''
  switch (mask) {
    case 'cpf':
      return formatCpf(value)
    case 'date':
      return formatDate(value)
    case 'cpfCnpj':
      return formatCpfCnpj(value)
    case 'cep':
      return formatCep(value)
    case 'phone':
      return formatPhone(value)
    case 'currency':
      return formatCurrency(Number(value))
    default:
      return value
  }
}

const toggleAdvancedFilters = () => {
  showAdvancedFilters.value = !showAdvancedFilters.value
}

const clearFilters = () => {
  props.filters.forEach((filter) => {
    filter.value = null
  })
  loadInfo()
}

const applyFilters = () => {
  currentPage.value = 1
  loadInfo()
}

watch(itemsPerPage, () => {
  currentPage.value = 1
  loadInfo()
})

onMounted(() => {
  loadInfo()
})
</script>

<style scoped>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
