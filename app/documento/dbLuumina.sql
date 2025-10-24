-- Garante que o banco de dados seja recriado do zero para um ambiente limpo
DROP DATABASE IF EXISTS dbLuumina;
CREATE DATABASE dbLuumina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbLuumina;

-- =================================================================
-- TABELAS PRINCIPAIS
-- =================================================================

-- Tabela 01: Usuários (base para todos os tipos de conta)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('cliente', 'profissional', 'admin') NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ;

CREATE TABLE profissionais (
    id_profissional INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNIQUE NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    biografia TEXT,
    localizacao VARCHAR(100) NULL COMMENT 'Localização do profissional, ex: Cidade, UF',
    foto_perfil VARCHAR(255) NULL COMMENT 'Caminho para o arquivo da foto de perfil',
    link_portfolio_externo VARCHAR(255) NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) COMMENT 'Dados complementares dos usuários que são profissionais';

-- Tabela 03: Especialidades (catálogo de todas as especialidades)
CREATE TABLE especialidades (
    id_especialidade INT AUTO_INCREMENT PRIMARY KEY,
    nome_especialidade VARCHAR(100) NOT NULL UNIQUE
) COMMENT 'Catálogo de todas as especialidades de fotografia';

-- Tabela 04: profissional_especialidades (tabela de junção)
CREATE TABLE profissional_especialidades (
    id_profissional INT NOT NULL,
    id_especialidade INT NOT NULL,
    PRIMARY KEY (id_profissional, id_especialidade),
    FOREIGN KEY (id_profissional) REFERENCES profissionais(id_profissional) ON DELETE CASCADE,
    FOREIGN KEY (id_especialidade) REFERENCES especialidades(id_especialidade) ON DELETE CASCADE
) COMMENT 'Relaciona os profissionais às suas múltiplas especialidades';

-- Tabela 05: Serviços (catálogo de serviços oferecidos)
CREATE TABLE servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    nome_servico VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco_base DECIMAL(10, 2)
) COMMENT 'Catálogo de serviços que podem ser associados a um portfólio';

-- Tabela 06: Portfólio (itens do portfólio de cada profissional)
CREATE TABLE portifolio (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_profissional INT,
    id_servico INT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    caminho_arquivo VARCHAR(255) NOT NULL COMMENT 'Caminho para a foto ou vídeo',
    tipo_midia ENUM('foto', 'video') NOT NULL DEFAULT 'foto',
    data_conclusao DATE,
    FOREIGN KEY (id_profissional) REFERENCES profissionais(id_profissional) ON DELETE SET NULL,
    FOREIGN KEY (id_servico) REFERENCES servicos(id_servico) ON DELETE SET NULL
) COMMENT 'Armazena os trabalhos (fotos/vídeos) dos profissionais';

-- Tabela 07: Solicitações de Orçamento
CREATE TABLE solicitacoes_orcamento (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY,
    id_profissional INT, -- <-- CAMPO ADICIONADO
    id_cliente INT,
    nome_solicitante VARCHAR(255) NOT NULL,
    email_solicitante VARCHAR(255) NOT NULL,
    telefone_solicitante VARCHAR(20),
    tipo_evento VARCHAR(255),
    data_evento DATE,
    mensagem TEXT,
    status_solicitacao ENUM('novo', 'em andamento', 'concluído') NOT NULL DEFAULT 'novo',
    FOREIGN KEY (id_profissional) REFERENCES profissionais(id_profissional) ON DELETE CASCADE, -- <-- CHAVE ESTRANGEIRA ADICIONADA
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) COMMENT 'Registros de pedidos de orçamento feitos por clientes';

-- =================================================================
-- INSERÇÃO DE DADOS DE EXEMPLO (PARA TESTES)
-- =================================================================

-- 1. Adicionar especialidades ao catálogo
INSERT INTO especialidades (nome_especialidade) VALUES
('Aniversários'), ('Arquitetura'), ('Boudoir'), ('Casamentos'), ('Chás de Bebê'),
('Drone'), ('Ensaios'), ('Esportes'), ('Eventos Corporativos'), ('Eventos Religiosos'),
('Formaturas'), ('Gastronomia'), ('Institucional'), ('Moda'), ('Pet'),
('Produtos'), ('Shows'), ('Viagem');


-- 2. Adicionar usuários de exemplo
-- Senha para ambos é 'senha123' (hash gerado para exemplo)
INSERT INTO usuarios (nome, email, senha_hash, tipo_usuario) VALUES
('Ana Clara', 'ana.cliente@email.com', '$2y$10$E.A4g.p0R5.XV2j8dI7/uefDB9pUbv5lS9b8h2A.u1eL/yLz.OKs.', 'cliente'),
('Carlos Rocha', 'carlos.fotografo@email.com', '$2y$10$E.A4g.p0R5.XV2j8dI7/uefDB9pUbv5lS9b8h2A.u1eL/yLz.OKs.', 'profissional'),
('adm', 'adimin@email.com', '$2y$10$Tyakee3rvFWjX9EUEuAheu5kEUxqpezMVULxeK5CT1KCsdYi4o6ly', 'admin');

-- 3. Adicionar dados do profissional (Carlos Rocha, id_usuario = 2)
INSERT INTO profissionais (id_usuario, cpf, biografia, localizacao, foto_perfil) VALUES
(2, '123.456.789-00', 'Fotógrafo apaixonado por contar histórias. Meu objetivo é capturar a essência de cada momento de forma autêntica.', 'Caieiras, SP', '../view/img/profile-placeholder.jpg');

-- 4. Vincular o profissional às suas especialidades
-- Carlos
select * from usuarios




INSERT INTO servicos (id_servico, nome_servico) VALUES
(1, 'Aniversários'),
(2, 'Arquitetura'),
(3, 'Boudoir'),
(4, 'Casamentos'),
(5, 'Chás de Bebê'),
(6, 'Drone'),
(7, 'Ensaios'),
(8, 'Esportes'),
(9, 'Eventos Corporativos'),
(10, 'Eventos Religiosos'),
(11, 'Formaturas'),
(12, 'Gastronomia'),
(13, 'Institucional'),
(14, 'Moda'),
(15, 'Pet'),
(16, 'Produtos'),
(17, 'Shows'),
(18, 'Viagem');

CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token_hash VARCHAR(64) NOT NULL UNIQUE COMMENT 'Hash SHA-256 do token',
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (email) REFERENCES usuarios(email) ON DELETE CASCADE
) COMMENT 'Armazena tokens para recuperação de senha';

-- Adicionar ao seu arquivo dbLuumina.sql
CREATE TABLE mensagens_conversa (
    id_mensagem INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitacao INT NOT NULL,
    id_remetente INT NOT NULL COMMENT 'ID do usuário que enviou (da tabela usuarios)',
    id_destinatario INT NOT NULL COMMENT 'ID do usuário que recebeu (da tabela usuarios)',
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_solicitacao) REFERENCES solicitacoes_orcamento(id_solicitacao) ON DELETE CASCADE,
    FOREIGN KEY (id_remetente) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_destinatario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) COMMENT 'Armazena as mensagens trocadas dentro de uma solicitação';

-- Exemplo de como alterar a coluna existente
ALTER TABLE solicitacoes_orcamento
MODIFY COLUMN status_solicitacao 
ENUM('novo', 'respondido', 'em_negociacao', 'finalizado', 'arquivado') 
NOT NULL DEFAULT 'novo';

ALTER TABLE solicitacoes_orcamento
ADD COLUMN data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP;