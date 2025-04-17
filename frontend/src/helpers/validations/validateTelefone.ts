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

export const validateTelefone = (fone: string): boolean => {
  fone = fone.replace(/[^\d]+/g, '') // Remove caracteres não numéricos
  return fone.length >= 10 && fone.length <= 11
}
