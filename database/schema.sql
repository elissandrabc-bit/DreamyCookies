-- ============================================================
-- Dreamy Cookies - Script de criação do banco de dados
-- Execute no DBeaver conectado à VM do MySQL/MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS dreamy_cookies
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE dreamy_cookies;

-- Tabela 1: Clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela 2: Categorias (tipos de cookies)
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL UNIQUE,
    descricao VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- Tabela 3: Cookies (produtos)
CREATE TABLE IF NOT EXISTS cookies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL CHECK (preco >= 0),
    imagem VARCHAR(255) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- Tabela 4: Relacionamento N:N entre cookies e categorias
CREATE TABLE IF NOT EXISTS cookie_categoria (
    cookie_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (cookie_id, categoria_id),
    CONSTRAINT fk_cc_cookie FOREIGN KEY (cookie_id)
        REFERENCES cookies(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cc_categoria FOREIGN KEY (categoria_id)
        REFERENCES categorias(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela 5: Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NULL,
    data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'confirmado', 'entregue', 'cancelado') NOT NULL DEFAULT 'confirmado',
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    CONSTRAINT fk_pedidos_cliente FOREIGN KEY (cliente_id)
        REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_pedidos_total CHECK (total >= 0)
) ENGINE=InnoDB;

-- Tabela 6: Itens do pedido
CREATE TABLE IF NOT EXISTS pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    cookie_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_pi_pedido FOREIGN KEY (pedido_id)
        REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pi_cookie FOREIGN KEY (cookie_id)
        REFERENCES cookies(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_pi_quantidade CHECK (quantidade > 0),
    CONSTRAINT chk_pi_preco CHECK (preco_unitario >= 0),
    CONSTRAINT chk_pi_subtotal CHECK (subtotal >= 0)
) ENGINE=InnoDB;

-- Objetos avançados (views, procedures, triggers): database/avancado.sql

-- ============================================================
-- Dados iniciais
-- ============================================================

INSERT INTO categorias (nome, descricao) VALUES
    ('Tradicional', 'Cookies com massa tradicional e chocolate'),
    ('Recheado', 'Cookies recheados com cremes especiais'),
    ('Especial', 'Sabores exclusivos e combinações únicas');

INSERT INTO cookies (nome, descricao, preco, imagem) VALUES
    ('Tradicional Preto', 'Massa tradicional com chocolate ao leite', 12.50, 'tradicional-preto.jpg'),
    ('Tradicional Branco', 'Massa tradicional com chocolate branco', 13.50, 'tradicional-branco.jpg'),
    ('Brookie', 'Massa tradicional de cookie + brownie com chocolate meio amargo e Nutella', 18.50, 'brookie.jpg'),
    ('Nutella', 'Massa tradicional com chocolate ao leite, recheada com Nutella', 18.00, 'nutella.jpg'),
    ('Ovomaltine', 'Massa tradicional com chocolate ao leite, recheada com creme de Ovomaltine', 18.50, 'ovomaltine.jpg'),
    ('Pistache', 'Massa tradicional com chocolate branco, recheada com creme de Pistache', 18.00, 'pistache.jpg'),
    ('Kinder', 'Massa tradicional com chocolate branco e preto, recheada com creme de Kinder caseiro', 18.00, 'kinder.jpg'),
    ('Negresco', 'Massa tradicional com cacau black e chocolate branco, recheada com creme de Negresco', 18.50, 'negresco.jpg'),
    ('Red Velvet', 'Massa tradicional com pedaços de chocolate branco, recheada com brigadeiro de cream cheese', 18.00, 'red-velvet.jpg'),
    ('Banoffe', 'Massa tradicional com gotas de chocolate branco, recheio cremoso de doce de leite com banana e finalizado com camada maçaricada de chocolate branco', 18.90, 'banoffe.jpg'),
    ('Pringles c/ Nutella', 'Massa tradicional com gotas de chocolate preto, recheio de Nutella com Pringles, finalizado com Nutella e pedaços de Pringles', 18.00, 'pringles-nutella.jpg');

-- Associações N:N (cookie_id, categoria_id)
INSERT INTO cookie_categoria (cookie_id, categoria_id) VALUES
    (1, 1), (2, 1),
    (3, 2), (3, 3),
    (4, 2), (5, 2), (6, 2), (7, 2), (8, 2), (9, 2), (10, 2), (10, 3),
    (11, 2), (11, 3);
