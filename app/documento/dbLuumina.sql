create database dbLuumina;
use dbLuumina;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('cliente', 'profissional', 'admin') NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profissionais (
    id_profissional INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNIQUE,
    biografia TEXT,
    especialidades VARCHAR(255),
    link_portfolio_externo VARCHAR(255),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    nome_servico VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco_base DECIMAL(10, 2)
);

CREATE TABLE portifolio (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_profissional INT,
    id_servico INT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    caminho_arquivo VARCHAR(255) NOT NULL,
    tipo_midia ENUM('foto', 'video') NOT NULL,
    data_conclusao DATE,
    FOREIGN KEY (id_profissional) REFERENCES profissionais(id_profissional) ON DELETE SET NULL,
    FOREIGN KEY (id_servico) REFERENCES servicos(id_servico) ON DELETE SET NULL
);

CREATE TABLE solicitacoes_orcamento (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    nome_solicitante VARCHAR(255) NOT NULL,
    email_solicitante VARCHAR(255) NOT NULL,
    telefone_solicitante VARCHAR(20),
    tipo_evento VARCHAR(255),
    data_evento DATE,
    mensagem TEXT,
    status_solicitacao ENUM('novo', 'em andamento', 'concluído') NOT NULL DEFAULT 'novo',
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);
ALTER TABLE profissionais ADD COLUMN cpf VARCHAR(14) NOT NULL;
