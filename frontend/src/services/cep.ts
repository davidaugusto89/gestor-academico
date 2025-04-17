import axios from 'axios'

// Lista de tipos de logradouros comuns no Brasil
const tiposLogradouros = [
  'Rua',
  'Avenida',
  'Travessa',
  'Alameda',
  'Viela',
  'Praça',
  'Rodovia',
  'Estrada',
  'Largo',
  'Beco',
  'Passagem',
  'Caminho',
  'Servidão',
  'Via',
  'Boulevard',
  'Setor',
  'Quadra',
]

/**
 * Busca informações de um CEP no serviço da ViaCEP e separa o logradouro.
 *
 * @throws {Error} Se o CEP for inválido ou não encontrado.
 * @param {string} cep - CEP a ser buscado.
 * @returns {Promise<Object>} - Informações do CEP, como logradouro, bairro,
 * cidade, uf e complemento, com o logradouro e endereço separados.
 */
export async function getCepInfo(cep: string) {
  // Remove caracteres não numéricos do CEP
  const sanitizedCep = cep.replace(/\D/g, '')

  // Verifica se o CEP possui 8 dígitos
  if (sanitizedCep.length !== 8) {
    throw new Error('CEP inválido. Deve conter 8 dígitos.')
  }

  try {
    const response = await axios.get(
      `https://viacep.com.br/ws/${sanitizedCep}/json/`
    )
    if (response.data.erro) {
      throw new Error('CEP não encontrado.')
    }

    // Separar logradouro em tipo e nome
    const logradouroCompleto = response.data.logradouro || ''
    const palavrasLogradouro = logradouroCompleto.split(' ')

    let tipoLogradouro = ''
    let endereco = logradouroCompleto

    if (palavrasLogradouro.length > 1) {
      // Verifica se o primeiro termo está na lista de tipos de logradouro
      if (tiposLogradouros.includes(palavrasLogradouro[0])) {
        tipoLogradouro = palavrasLogradouro[0]
        endereco = palavrasLogradouro.slice(1).join(' ')
      } else {
        // Fallback para o split caso o tipo não esteja na lista
        tipoLogradouro = palavrasLogradouro[0]
        endereco = palavrasLogradouro.slice(1).join(' ')
      }
    }

    return {
      ...response.data,
      logradouro: tipoLogradouro,
      endereco,
    }
  } catch (error: any) {
    throw new Error(error.response?.data?.message || 'Erro ao buscar o CEP.')
  }
}
