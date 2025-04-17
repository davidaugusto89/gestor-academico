import { ref, computed } from 'vue'
import {
  validateCPF,
  validateCNPJ,
  validateEmail,
  validateTelefone,
} from '@/helpers/validations'

export function useValidation() {
  const errors = ref<Record<string, string>>({})

  // Mapeia os tipos para os respectivos helpers
  const validationHelpers: Record<string, (value: string) => boolean> = {
    cpf: validateCPF,
    cnpj: validateCNPJ,
    email: validateEmail,
    Telefone: validateTelefone,
    'CPF/CNPJ': (value: string) => validateCPF(value) || validateCNPJ(value),
  }

  const validate = (
    field: string,
    value: string | number,
    rules: {
      required?: boolean
      minLength?: number
      maxLength?: number
      length?: number
      min?: number
      max?: number
      type?: string
      custom?: (value: string | number) => boolean
    }
  ) => {
    const newErrors = { ...errors.value }

    if (rules.required && !value) {
      newErrors[field] = 'Campo é obrigatório.'
    } else if (typeof value === 'string') {
      if (rules.minLength && value.length < rules.minLength) {
        newErrors[field] =
          `O campo deve ter no mínimo ${rules.minLength} caracteres.`
      } else if (rules.maxLength && value.length > rules.maxLength) {
        newErrors[field] =
          `O campo deve ter no máximo ${rules.maxLength} caracteres.`
      } else if (rules.length && value.length !== rules.length) {
        newErrors[field] =
          `O campo deve ter exatamente ${rules.length} caracteres.`
      }
    } else if (typeof value === 'number') {
      if (rules.min !== undefined && value < rules.min) {
        newErrors[field] = `O valor deve ser no mínimo ${rules.min}.`
      } else if (rules.max !== undefined && value > rules.max) {
        newErrors[field] = `O valor deve ser no máximo ${rules.max}.`
      }
    }

    if (rules.type && validationHelpers[rules.type]) {
      const isValid = validationHelpers[rules.type](String(value))
      if (!isValid) {
        newErrors[field] = `O campo deve ser um ${rules.type} válido.`
      }
    }

    if (rules.custom && !rules.custom(value)) {
      newErrors[field] = 'O campo não atende aos critérios de validação.'
    }

    if (!newErrors[field]) {
      delete newErrors[field]
    }

    errors.value = newErrors
  }

  const hasErrors = computed(() =>
    Object.values(errors.value).some((error) => error !== '')
  )

  return {
    errors,
    validate,
    hasErrors,
  }
}
