CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE municipes (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    possui_filhos BOOLEAN DEFAULT FALSE,
    idades_filhos JSONB,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_municipes_cpf ON municipes(cpf);

CREATE TABLE visitas_doacoes (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    municipe_id UUID NOT NULL,
    data_visita TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    qtd_roupa_cama INT DEFAULT 0,
    qtd_masculino INT DEFAULT 0,
    qtd_feminino INT DEFAULT 0,
    qtd_infantil INT DEFAULT 0,
    qtd_calcados INT DEFAULT 0,
    outros_materiais TEXT,
    autorizado_por VARCHAR(255),
    assinatura_recebedor BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_municipe FOREIGN KEY(municipe_id) REFERENCES municipes(id) ON DELETE CASCADE
);
