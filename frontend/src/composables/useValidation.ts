import { ref, computed } from 'vue'
import {
  validateCPF,
  validateCNPJ,
  validateEmail,
  validateTelefone,
  validatePasswordStrong,
} from '@/helpers/validations'

interface ValidationRules {
  required?: boolean
  minLength?: number
  maxLength?: number
  length?: number
  min?: number
  max?: number
  type?: keyof typeof validationHelpers
  custom?: (value: string | number) => boolean
}

const validationHelpers = {
  cpf: validateCPF,
  cnpj: validateCNPJ,
  email: validateEmail,
  Telefone: validateTelefone,
  passwordStrong: validatePasswordStrong,
  'CPF/CNPJ': (value: string) => validateCPF(value) || validateCNPJ(value),
}

export function useValidation() {
  const errors = ref<Record<string, string>>({})

  const validate = (
    field: string,
    value: string | number,
    rules: ValidationRules
  ) => {
    const newErrors = { ...errors.value }
    const stringValue = String(value).trim()

    if (rules.required && !stringValue) {
      newErrors[field] = 'Campo é obrigatório.'
    } else if (typeof value === 'string') {
      if (rules.minLength && stringValue.length < rules.minLength) {
        newErrors[field] = `Mínimo de ${rules.minLength} caracteres.`
      } else if (rules.maxLength && stringValue.length > rules.maxLength) {
        newErrors[field] = `Máximo de ${rules.maxLength} caracteres.`
      } else if (rules.length && stringValue.length !== rules.length) {
        newErrors[field] = `Deve ter exatamente ${rules.length} caracteres.`
      }
    } else if (typeof value === 'number') {
      if (rules.min !== undefined && value < rules.min) {
        newErrors[field] = `Mínimo permitido é ${rules.min}.`
      } else if (rules.max !== undefined && value > rules.max) {
        newErrors[field] = `Máximo permitido é ${rules.max}.`
      }
    }

    if (rules.type && validationHelpers[rules.type]) {
      const isValid = validationHelpers[rules.type](stringValue)
      if (!isValid) {
        newErrors[field] =
          rules.type === 'passwordStrong'
            ? 'Senha fraca. Use letras maiúsculas, minúsculas, número e símbolo.'
            : `O campo deve ser um ${rules.type} válido.`
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
    Object.values(errors.value).some((msg) => msg !== '')
  )

  return {
    errors,
    validate,
    hasErrors,
  }
}
