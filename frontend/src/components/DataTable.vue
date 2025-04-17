<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <!-- Loading -->
    <div
      v-if="isLoading"
      class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50"
    >
      <div class="loader">Carregando...</div>
    </div>

    <!-- Busca e Filtros -->
    <div
      class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-white dark:bg-gray-900"
    >
      <!-- Busca Global -->
      <div class="w-full md:w-auto mb-4 md:mb-0">
        <Input
          type="text"
          id="global-search"
          v-model="globalSearch"
          placeholder="Buscar..."
          label=""
          width="full"
        />
      </div>

      <!-- Botão Filtros Avançados -->
      <div v-if="advancedFilter" class="w-full md:w-auto">
        <Button type="outline" @click="toggleAdvancedFilters">
          <i class="material-icons">filter_list</i>
          Filtros Avançados
        </Button>
      </div>
    </div>

    <!-- Filtros Avançados -->
    <div
      v-if="showAdvancedFilters"
      class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg"
    >
      <div v-for="filter in filters" :key="filter.key">
        <Input
          type="text"
          :id="filter.key"
          v-model="filter.value"
          :placeholder="filter.placeholder"
          width="full"
          @input="applyFilter(filter.key, $event.target.value)"
          :label="filter.label"
        />
      </div>
    </div>

    <!-- Tabela -->
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead
        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
      >
        <tr>
          <th v-for="column in columns" :key="column.key" class="px-6 py-3">
            {{ column.label }}
          </th>
          <th class="px-6 py-3" v-if="showActions">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in paginatedData"
          :key="item.id"
          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 even:bg-gray-50 hover:bg-gray-100 dark:even:bg-gray-700 dark:hover:bg-gray-600 transition-colors"
        >
          <td v-for="column in columns" :key="column.key" class="px-6 py-4">
            {{ item[column.key] }}
          </td>
          <td class="px-6 py-4 gap-2 flex w-[150px]" v-if="showActions">
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

    <!-- Paginação -->
    <div
      class="flex justify-between items-center px-4 py-3 bg-white dark:bg-gray-900"
    >
      <!-- Seletor de itens por página -->
      <Select
        v-model="itemsPerPage"
        :options="itemsPerPageOptions"
        width="full"
      />

      <span class="text-sm text-gray-700 dark:text-gray-400">
        Mostrando {{ paginatedData.length }} de {{ filteredData.length }} itens
      </span>
      <nav class="inline-flex -space-x-px gap-2">
        <Button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
        >
          <i class="material-icons">chevron_left</i>
        </Button>
        <span v-for="page in totalPages" :key="page">
          <Button
            @click="changePage(page)"
            type="outline"
            :disabled="page === currentPage"
          >
            {{ page }}
          </Button>
        </span>
        <Button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
        >
          <i class="material-icons">chevron_right</i>
        </Button>
      </nav>
    </div>
  </div>
</template>

<script setup lang="ts">
  import request from '@/services/request'
  import Swal from 'sweetalert2'
  import { ref, computed } from 'vue'

  interface Column {
    key: string
    label: string
    filterable?: boolean
  }

  interface Filter {
    key: string
    label: string
    placeholder?: string
  }

  const props = defineProps<{
    columns: Column[]
    data: any[]
    isLoading: boolean
    advancedFilter?: boolean
    filters: Filter[]
    showLink: string
    editLink: string
    deleteLink: string
  }>()

  const globalSearch = ref('')
  const showAdvancedFilters = ref(false)
  const currentPage = ref(1)
  const itemsPerPage = ref(5)
  const isLoadingDelete = ref(false)

  const showActions = computed(() => {
    return props.showLink || props.editLink || props.deleteLink
  })

  const itemsPerPageOptions = [
    {
      value: 5,
      label: '5',
    },
    {
      value: 10,
      label: '10',
    },
    {
      value: 25,
      label: '25',
    },
    {
      value: 50,
      label: '50',
    },
  ]

  // Estado para filtros ativos
  const activeFilters = ref<Record<string, string>>({})

  // Alternar filtros avançados
  const toggleAdvancedFilters = () => {
    showAdvancedFilters.value = !showAdvancedFilters.value
  }

  // Aplicar filtros sem armazenar localmente
  const applyFilter = (key: string, value: string) => {
    activeFilters.value[key] = value
  }

  // Filtrar os dados com base na busca global e filtros ativos
  const filteredData = computed(() => {
    return props.data.filter((item) => {
      // Busca global
      const matchesGlobalSearch = Object.values(item)
        .join(' ')
        .toLowerCase()
        .includes(globalSearch.value.toLowerCase())

      // Filtros avançados
      const matchesFilters = Object.keys(activeFilters.value).every((key) => {
        return (
          activeFilters.value[key] === undefined ||
          activeFilters.value[key] === '' ||
          item[key]
            ?.toString()
            .toLowerCase()
            .includes(activeFilters.value[key].toLowerCase())
        )
      })

      return matchesGlobalSearch && matchesFilters
    })
  })

  // Paginação
  const totalPages = computed(() =>
    Math.ceil(filteredData.value.length / itemsPerPage.value)
  )
  const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredData.value.slice(start, end)
  })

  // Trocar página
  const changePage = (page: number) => {
    if (page > 0 && page <= totalPages.value) {
      currentPage.value = page
    }
  }

  const deleteItem = async (id: number) => {
    Swal.fire({
      title: 'Tem certeza?',
      text: 'Deseja excluir o item #' + id + '?',
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

        if (result.isConfirmed) {
          Swal.fire('Excluido!', 'Item excluido com sucesso.', 'success')
        }
      }
    })
  }
</script>
