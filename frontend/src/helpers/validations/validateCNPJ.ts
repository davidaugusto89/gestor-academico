/**
 * Validates a Brazilian CNPJ (Cadastro Nacional da Pessoa Jurídica).
 *
 * @param cnpj - The CNPJ number to be validated, as a string.
 * @returns `true` if the CNPJ is valid, otherwise `false`.
 *
 * The function first removes any non-numeric characters from the input.
 * It checks for common invalid patterns such as repeated digits.
 * Then, it calculates check digits using an algorithm based on multiplication
 * and modulo operations, comparing them to the digits provided in the input.
 */

export const validateCNPJ = (cnpj: string): boolean => {
  cnpj = cnpj.replace(/[^\d]+/g, '') // Remove caracteres não numéricos
  if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false

  const calc = (x: number) => {
    const slice = cnpj.slice(0, x)
    let soma = 0
    let pos = x - 7

    for (let i = x; i >= 1; i--) {
      soma += parseInt(slice[x - i]) * pos--
      if (pos < 2) pos = 9
    }

    const resto = soma % 11
    return resto < 2 ? 0 : 11 - resto
  }

  const digito1 = calc(12)
  const digito2 = calc(13)

  return (
    digito1 === parseInt(cnpj.charAt(12)) &&
    digito2 === parseInt(cnpj.charAt(13))
  )
}
