-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS gestor_academico DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestor_academico;

-- Tabela de usuários (login)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    papel ENUM('admin', 'user') DEFAULT 'user',
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de alunos (sem vínculo com usuários)
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nascimento DATE NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de turmas
CREATE TABLE turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de matrículas
CREATE TABLE matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    data_matricula DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    CONSTRAINT fk_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    CONSTRAINT uc_matricula UNIQUE (aluno_id, turma_id)
);

-- Usuários iniciais
-- Senha: "SenhaForte123!" (bcrypt hash gerado com password_hash)
INSERT INTO usuarios (nome, email, senha, papel)
VALUES
  ('Admin FIAP', 'admin@fiap.com.br', '$2y$10$VRurf7gFLziv0/0Xmsb3SeUnKUfy9EveLBRPnY3R2A61hr9K/lao2', 'admin'),
  ('User FIAP', 'user@fiap.com.br', '$2y$10$VRurf7gFLziv0/0Xmsb3SeUnKUfy9EveLBRPnY3R2A61hr9K/lao2', 'user');

-- Alunos iniciais
INSERT INTO alunos (nome, email, senha, nascimento, cpf)
VALUES
  ('João da Silva', 'joao@example.com', '$2y$10$VRurf7gFLziv0/0Xmsb3SeUnKUfy9EveLBRPnY3R2A61hr9K/lao2', '2000-04-20', '18060567047'),
  ('Maria da Silva', 'maria@example.com', '$2y$10$VRurf7gFLziv0/0Xmsb3SeUnKUfy9EveLBRPnY3R2A61hr9K/lao2', '2000-04-20', '15263980019'),
  ('Pedro da Silva', 'pedro@example.com', '$2y$10$VRurf7gFLziv0/0Xmsb3SeUnKUfy9EveLBRPnY3R2A61hr9K/lao2', '2000-04-20', '99749812042');

-- Turmas iniciais
INSERT INTO turmas (nome, descricao)
VALUES
  ('Engenharia de Software', 'Turma superior de engenharia'),
  ('Técnico em Informática', 'Curso técnico');

-- Matricular o aluno em uma turma
INSERT INTO matriculas (aluno_id, turma_id)
VALUES (1, 1),
       (2, 1),
       (3, 2);
