import api from './axios'
import Swal from 'sweetalert2'

const request = {
  /**
   * Envia uma requisi o GET para a URL informada.
   *
   * @param {string} url - URL da requisi o.
   * @param {object} [params] - Par metros da requisi o.
   *
   * @returns {Promise<AxiosResponse<any>>} - Resposta da requisi o.
   */
  get: async (url: string, params?: object) => {
    return api.get(url, { params })
  },

  /**
   * Envia uma requisição POST para a URL informada.
   *
   * @param {string} url - URL da requisição.
   * @param {object} [data] - Dados a serem enviados no corpo da requisição.
   *
   * @returns {Promise<AxiosResponse<any>>} - Resposta da requisição.
   * @throws {any} - Repropaga o erro lançado pela requisição.
   */
  post: async (url: string, data?: object) => {
    try {
      const response = await api.post(url, data)

      // Verifica o status da resposta
      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      // Trata o erro de requisição, como falha de rede ou erro de resposta
      handleRequestError(error)
      throw error // Repropaga o erro para o `handleLogin` lidar
    }
  },
  /**
   * Envia uma requisi o PUT para a URL informada.
   *
   * @param {string} url - URL da requisi o.
   * @param {object} [data] - Dados a serem enviados no corpo da requisi o.
   *
   * @returns {Promise<AxiosResponse<any>>} - Resposta da requisi o.
   * @throws {any} - Repropaga o erro lan ado pela requisi o.
   */
  put: async (url: string, data?: object) => {
    try {
      const response = await api.put(url, data)

      // Verifica o status da resposta
      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      // Trata o erro de requisição, como falha de rede ou erro de resposta
      handleRequestError(error)
      throw error // Repropaga o erro para o `handleLogin` lidar
    }
  },
  /**
   * Envia uma requisi o PATCH para a URL informada.
   *
   * @param {string} url - URL da requisi o.
   * @param {object} [data] - Dados a serem enviados no corpo da requisi o.
   *
   * @returns {Promise<AxiosResponse<any>>} - Resposta da requisi o.
   * @throws {any} - Repropaga o erro lan ado pela requisi o.
   */
  patch: async (url: string, data?: object) => {
    try {
      const response = await api.patch(url, data)

      // Verifica o status da resposta
      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      // Trata o erro de requisição, como falha de rede ou erro de resposta
      handleRequestError(error)
      throw error // Repropaga o erro para o `handleLogin` lidar
    }
  },
  /**
   * Envia uma requisi o DELETE para a URL informada.
   *
   * @param {string} url - URL da requisi o.
   *
   * @returns {Promise<AxiosResponse<any>>} - Resposta da requisi o.
   * @throws {any} - Repropaga o erro lan ado pela requisi o.
   */
  delete: async (url: string) => {
    return api.delete(url)
  },
}

/**
 * Verifica o status da resposta da requisição.
 *
 * @param {number} statusCode - Código de status da resposta HTTP.
 * @param {string} [message] - Mensagem de erro opcional associada ao status.
 *
 * @throws {Error} - Lança um erro se o status for 401 e uma mensagem for fornecida.
 */
const checkStatus = async (statusCode: number, message?: string) => {
  if (statusCode === 401 && message) {
    alertError(message) // Mostra o alerta
    throw new Error(message) // Lança um erro para interromper o fluxo
  }
}

const alertError = (message: string) => {
  const formattedMessage = Array.isArray(message)
    ? message.join('<br />') // Concatena com quebras de linha
    : message

  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    html: formattedMessage,
  })
}

const handleRequestError = (error: any) => {
  if (error.response) {
    // Erros de resposta do servidor
    alertError(error.response.data.message || 'Erro inesperado no servidor.')
  } else if (error.request) {
    // Sem resposta do servidor
    alertError('Erro de conexão. Verifique sua internet.')
  } else {
    // Outros erros
    alertError(error.message || 'Ocorreu um erro.')
  }
}

export default request
