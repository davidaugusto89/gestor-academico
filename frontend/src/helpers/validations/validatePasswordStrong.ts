/**
 * Valida se uma senha é forte conforme os critérios definidos:
 * - Mínimo de 8 caracteres
 * - Pelo menos uma letra maiúscula
 * - Pelo menos uma letra minúscula
 * - Pelo menos um número
 * - Pelo menos um símbolo
 *
 * @param senha - A senha a ser validada.
 * @returns `true` se a senha for considerada forte, caso contrário `false`.
 */
export const validatePasswordStrong = (senha: string): boolean => {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/
  return regex.test(senha)
}
