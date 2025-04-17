
# Sistema de Gestão Acadêmica 📚

Este é um projeto fullstack construído em **PHP puro** (sem frameworks) com **MySQL**, que permite o gerenciamento de alunos, turmas e matrículas, além de autenticação de usuários. O projeto está dockerizado para facilitar a instalação e execução.

---

## ✨ Funcionalidades

- Login seguro de administradores com autenticação via e-mail e senha;
- Cadastro, listagem, edição, exclusão de **alunos** e **turmas**;
- Matrícula de alunos em turmas com validações;
- Busca de alunos por nome;
- Exibição da contagem de alunos em cada turma;
- Documentação da API acessível via Swagger.

---

## 🔒 Regras de Negócio

- RN01: Listagens devem ser ordenadas alfabeticamente;
- RN02: Nome de aluno/turma deve ter no mínimo 3 caracteres;
- RN03: Todos os campos são obrigatórios e validados;
- RN04: Aluno não pode ser matriculado duas vezes na mesma turma;
- RN05: Aluno deve ser único por CPF ou e-mail;
- RN06: Contar quantidade de alunos por turma na listagem;
- RN07: Senhas fortes (mín. 8 caracteres, letras maiúsculas, minúsculas, números e símbolos);
- RN08: Senhas armazenadas criptografadas;
- RN09: Listagem paginada de turmas (10 itens por página);
- RN10: Buscar alunos por nome.

---

## 🧑‍💻 Tecnologias Utilizadas

- **PHP >= 8.1**
- **MySQL 8**
- **HTML + Bootstrap** (interface administrativa)
- **Docker e Docker Compose**
- **Swagger UI (OpenAPI)**
- **PhpMyAdmin** (gerenciador visual de banco de dados)
- **Mailhog** (teste de envio de e-mails)

---

## ⚙️ Como Executar

### Pré-requisitos

- Docker
- Docker Compose
- Bash (Linux/Mac ou Git Bash no Windows)

### Setup Automático

```bash
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Esse script realiza:

- Criação dos arquivos `.env`;
- Subida dos containers com Docker;
- Importação do banco com `dump.sql`;
- Exibição de URLs úteis.

---

## 🔐 Credenciais

| Tipo        | E-mail              | Senha         |
|-------------|---------------------|---------------|
| Admin       | admin@example.com   | 123456        |
| Usuário     | user@example.com    | 123456        |

---

## 🔗 URLs de Acesso

| Serviço        | URL                        |
|----------------|-----------------------------|
| Frontend       | http://localhost            |
| Backend API    | http://localhost/api        |
| Swagger UI     | http://localhost/api/docs   |
| PhpMyAdmin     | http://localhost:8081       |
| Mailhog        | http://localhost:8025       |

---

## 📂 Estrutura de Pastas

```
├── backend
│   ├── src
│   │   ├── Controller
│   │   ├── Domain
│   │   │   ├── Aluno
│   │   │   ├── Turma
│   │   │   ├── Usuario
│   │   │   └── Matricula
│   │   ├── Core
│   │   ├── Support
│   │   └── Utils
│   ├── public
│   │   └── index.php
├── frontend
│   └── (interface HTML, CSS, JS)
├── scripts
│   └── setup.sh
├── sql
│   └── dump.sql
├── docker-compose.yml
└── README.md
```

---

## 📑 Documentação da API

A documentação pode ser acessada via:

> http://localhost/api/docs

A especificação OpenAPI (`swagger.json`) é gerada automaticamente no backend.

---

## 🧑 Autor

Desenvolvido por [David Augusto](https://github.com/davidaugusto89)

Licenciado sob a licença MIT.
