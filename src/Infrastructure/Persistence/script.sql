CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    hash_senha VARCHAR(255) NOT NULL,
    permissao VARCHAR(32) NOT NULL,
    data_criacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE remessas (
    id CHAR(36) PRIMARY KEY,
    numero_remessa INT NOT NULL AUTO_INCREMENT,
    data_recebimento DATETIME NOT NULL,
    data_entrega DATETIME NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'RECEBIDO',
    quantidade_protocolos INT UNSIGNED NULL,
    id_administrador INT NOT NULL,
    observacoes TEXT NULL,
    UNIQUE KEY (numero_remessa),
    FOREIGN KEY (id_administrador) REFERENCES usuarios(id)
);

CREATE TABLE protocolos (
    id CHAR(36) PRIMARY KEY,
    id_remessa CHAR(36) NOT NULL,
    numero_protocolo CHAR(6) NOT NULL UNIQUE,
    data_preparacao DATETIME NULL,
    id_preparador INT NULL,
    data_digitalizacao DATETIME NULL,
    id_digitalizador INT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'RECEBIDO',
    quantidade_paginas INT UNSIGNED NULL,
    observacoes TEXT NULL,
    FOREIGN KEY (id_remessa) REFERENCES remessas(id),
    FOREIGN KEY (id_preparador) REFERENCES usuarios(id),
    FOREIGN KEY (id_digitalizador) REFERENCES usuarios(id)
);