/**
 * Valida se o email é válido.
 *
 * @param email - O email a ser validado.
 * @returns `true` se o email for válido, caso contrário `false`.
 */
export const validateEmail = (email: string): boolean => {
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
  return emailRegex.test(email.trim())
}
