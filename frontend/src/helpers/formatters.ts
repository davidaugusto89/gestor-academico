export const formatCpf = (value: string): string => {
  if (!value) return ''
  return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
}

export const formatDate = (value: string): string => {
  if (!value) return ''
  if (value.length !== 10) {
    value = value.slice(0, 10)
  }
  const [year, month, day] = value.split('-')
  return `${day}/${month}/${year}`
}

// Funções personalizadas para formatação
export const formatCpfCnpj = (value: string) => {
  if (!value) return ''
  return value.length <= 11
    ? value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4') // CPF
    : value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5') // CNPJ
}

export const formatCep = (value: string) => {
  if (!value) return ''
  return value.replace(/(\d{5})(\d{3})/, '$1-$2') // CEP
}

export const formatPhone = (value: string) => {
  if (!value) return ''
  return value.length === 10
    ? value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3') // Telefone fixo
    : value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3') // Celular
}

export const formatCurrency = (value: number) => {
  if (!value) return ''
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(value) // Moeda brasileira
}
