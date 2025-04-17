# 🎓 Gestor Acadêmico com PHP e VueJS

Sistema para gerenciamento de alunos, turmas e matrículas, com autenticação e painel administrativo. O backend é desenvolvido em **PHP puro** com arquitetura modular, e o frontend utiliza **VueJS** com **Vite**. Toda a infraestrutura é orquestrada com **Docker**.

---

## 🛠 Tecnologias Utilizadas

### Backend
- **PHP 8.3+**
- **Arquitetura Modular (Domain, Service, Repository, Controller, Core)**
- **Autenticação JWT**
- **Validação de CPF e Senhas Fortes**
- **PDO (MySQL)**
- **Testes Unitários com PHPUnit**
- **Dockerized CLI + Apache**

### Frontend
- **VueJS 3** com **Vite**
- **Pinia** (gerenciamento de estado)
- **Vue Router** (roteamento SPA)
- **TailwindCSS** (estilização rápida)
- **SweetAlert2** (alertas)
- **DataTables** (listas e filtros)

### Infraestrutura
- **Docker + Docker Compose**
- **MySQL**: Banco de dados relacional
- **PhpMyAdmin**: Interface visual para gerenciar o banco
- **Mailhog**: SMTP local para testes de email
- **Nginx**: Proxy reverso e roteamento para backend e frontend

---