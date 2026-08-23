-- ============================================================
-- Dreamy Cookies - Banco avançado (nova rubrica)
-- Execute no phpMyAdmin ou DBeaver com o banco dreamy_cookies
-- Requer MariaDB 10.2+ (CTEs)
-- ============================================================

USE dreamy_cookies;

-- ============================================================
-- 1. Tabelas de pedidos
-- ============================================================

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

-- ============================================================
-- 2. Funções reutilizáveis
-- ============================================================

DROP FUNCTION IF EXISTS fn_subtotal_item;
DELIMITER $$
CREATE FUNCTION fn_subtotal_item(
    p_quantidade INT,
    p_preco_unitario DECIMAL(10, 2)
) RETURNS DECIMAL(10, 2)
DETERMINISTIC
BEGIN
    IF p_quantidade IS NULL OR p_quantidade <= 0 THEN
        RETURN 0.00;
    END IF;

    IF p_preco_unitario IS NULL OR p_preco_unitario < 0 THEN
        RETURN 0.00;
    END IF;

    RETURN ROUND(p_quantidade * p_preco_unitario, 2);
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS fn_total_pedido;
DELIMITER $$
CREATE FUNCTION fn_total_pedido(p_pedido_id INT)
RETURNS DECIMAL(10, 2)
READS SQL DATA
BEGIN
    DECLARE v_total DECIMAL(10, 2);

    SELECT COALESCE(SUM(subtotal), 0.00)
    INTO v_total
    FROM pedido_itens
    WHERE pedido_id = p_pedido_id;

    RETURN v_total;
END$$
DELIMITER ;

-- ============================================================
-- 3. Triggers — valores positivos e totais consistentes
-- ============================================================

DROP TRIGGER IF EXISTS trg_cookies_preco_positivo_update;
DELIMITER $$
CREATE TRIGGER trg_cookies_preco_positivo_update
BEFORE UPDATE ON cookies
FOR EACH ROW
BEGIN
    IF NEW.preco IS NULL OR NEW.preco < 0 THEN
        SET NEW.preco = OLD.preco;
    END IF;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS trg_pedido_itens_validar_insert;
DELIMITER $$
CREATE TRIGGER trg_pedido_itens_validar_insert
BEFORE INSERT ON pedido_itens
FOR EACH ROW
BEGIN
    IF NEW.quantidade IS NULL OR NEW.quantidade <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Quantidade deve ser maior que zero.';
    END IF;

    IF NEW.preco_unitario IS NULL OR NEW.preco_unitario < 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Preço unitário deve ser zero ou positivo.';
    END IF;

    SET NEW.subtotal = fn_subtotal_item(NEW.quantidade, NEW.preco_unitario);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS trg_pedido_itens_validar_update;
DELIMITER $$
CREATE TRIGGER trg_pedido_itens_validar_update
BEFORE UPDATE ON pedido_itens
FOR EACH ROW
BEGIN
    IF NEW.quantidade IS NULL OR NEW.quantidade <= 0 THEN
        SET NEW.quantidade = OLD.quantidade;
    END IF;

    IF NEW.preco_unitario IS NULL OR NEW.preco_unitario < 0 THEN
        SET NEW.preco_unitario = OLD.preco_unitario;
    END IF;

    SET NEW.subtotal = fn_subtotal_item(NEW.quantidade, NEW.preco_unitario);
END$$
DELIMITER ;

-- Total do pedido: recalculado via fn_total_pedido() no PHP ou:
-- UPDATE pedidos SET total = fn_total_pedido(id) WHERE id = ?;

-- ============================================================
-- 4. Views analíticas (consolidação multi-tabela)
-- ============================================================

DROP VIEW IF EXISTS vw_cookies_completo;
CREATE VIEW vw_cookies_completo AS
SELECT
    c.id AS cookie_id,
    c.nome AS cookie_nome,
    c.descricao,
    c.preco,
    c.imagem,
    c.ativo,
    GROUP_CONCAT(DISTINCT cat.nome ORDER BY cat.nome SEPARATOR ', ') AS categorias,
    GROUP_CONCAT(DISTINCT cat.id ORDER BY cat.id SEPARATOR ',') AS categorias_ids
FROM cookies c
LEFT JOIN cookie_categoria cc ON cc.cookie_id = c.id
LEFT JOIN categorias cat ON cat.id = cc.categoria_id
GROUP BY c.id, c.nome, c.descricao, c.preco, c.imagem, c.ativo;

DROP VIEW IF EXISTS vw_pedidos_detalhados;
CREATE VIEW vw_pedidos_detalhados AS
SELECT
    p.id AS pedido_id,
    p.data_pedido,
    p.status,
    p.total AS total_pedido,
    cl.id AS cliente_id,
    COALESCE(cl.nome, 'Cliente não identificado') AS cliente_nome,
    cl.email AS cliente_email,
    pi.id AS item_id,
    ck.id AS cookie_id,
    ck.nome AS cookie_nome,
    pi.quantidade,
    pi.preco_unitario,
    pi.subtotal AS subtotal_item
FROM pedidos p
LEFT JOIN clientes cl ON cl.id = p.cliente_id
INNER JOIN pedido_itens pi ON pi.pedido_id = p.id
INNER JOIN cookies ck ON ck.id = pi.cookie_id;

DROP VIEW IF EXISTS vw_vendas_por_categoria;
CREATE VIEW vw_vendas_por_categoria AS
WITH vendas_categoria AS (
    SELECT
        cat.id AS categoria_id,
        cat.nome AS categoria_nome,
        SUM(pi.quantidade) AS quantidade_vendida,
        SUM(pi.subtotal) AS faturamento_categoria
    FROM pedido_itens pi
    INNER JOIN pedidos p ON p.id = pi.pedido_id
    INNER JOIN cookies ck ON ck.id = pi.cookie_id
    INNER JOIN cookie_categoria cc ON cc.cookie_id = ck.id
    INNER JOIN categorias cat ON cat.id = cc.categoria_id
    WHERE p.status IN ('confirmado', 'entregue')
    GROUP BY cat.id, cat.nome
)
SELECT
    categoria_id,
    categoria_nome,
    quantidade_vendida,
    ROUND(faturamento_categoria, 2) AS faturamento_categoria
FROM vendas_categoria;

DROP VIEW IF EXISTS vw_dashboard_resumo;
CREATE VIEW vw_dashboard_resumo AS
WITH metricas AS (
    SELECT
        (SELECT COUNT(*)
         FROM pedidos
         WHERE status IN ('confirmado', 'entregue')) AS total_pedidos,
        (SELECT COALESCE(SUM(total), 0)
         FROM pedidos
         WHERE status IN ('confirmado', 'entregue')) AS faturamento_total,
        (SELECT COALESCE(SUM(pi.quantidade), 0)
         FROM pedido_itens pi
         INNER JOIN pedidos p ON p.id = pi.pedido_id
         WHERE p.status IN ('confirmado', 'entregue')) AS cookies_vendidos
),
ranking AS (
    SELECT
        ck.nome AS cookie_mais_vendido,
        SUM(pi.quantidade) AS qtd
    FROM pedido_itens pi
    INNER JOIN pedidos p ON p.id = pi.pedido_id
    INNER JOIN cookies ck ON ck.id = pi.cookie_id
    WHERE p.status IN ('confirmado', 'entregue')
    GROUP BY ck.id, ck.nome
    ORDER BY qtd DESC
    LIMIT 1
)
SELECT
    m.total_pedidos,
    ROUND(m.faturamento_total, 2) AS faturamento_total,
    m.cookies_vendidos,
    COALESCE(r.cookie_mais_vendido, 'Nenhum') AS cookie_mais_vendido,
    COALESCE(r.qtd, 0) AS qtd_cookie_mais_vendido
FROM metricas m
LEFT JOIN ranking r ON 1 = 1;

-- ============================================================
-- 5. Stored Procedures — dashboard e listagem paginada
-- ============================================================

DROP PROCEDURE IF EXISTS sp_dashboard_indicadores;
DELIMITER $$
CREATE PROCEDURE sp_dashboard_indicadores()
BEGIN
    WITH itens_vendidos AS (
        SELECT
            p.id AS pedido_id,
            pi.quantidade,
            pi.subtotal,
            ck.id AS cookie_id,
            ck.nome AS cookie_nome
        FROM pedidos p
        INNER JOIN pedido_itens pi ON pi.pedido_id = p.id
        INNER JOIN cookies ck ON ck.id = pi.cookie_id
        WHERE p.status IN ('confirmado', 'entregue')
    ),
    resumo AS (
        SELECT
            COUNT(DISTINCT pedido_id) AS total_pedidos,
            COALESCE(SUM(subtotal), 0) AS faturamento_total,
            COALESCE(SUM(quantidade), 0) AS unidades_vendidas
        FROM itens_vendidos
    ),
    top_cookie AS (
        SELECT cookie_nome, SUM(quantidade) AS qtd
        FROM itens_vendidos
        GROUP BY cookie_id, cookie_nome
        ORDER BY qtd DESC
        LIMIT 1
    )
    SELECT
        r.total_pedidos,
        ROUND(r.faturamento_total, 2) AS faturamento_total,
        r.unidades_vendidas,
        COALESCE(t.cookie_nome, 'Nenhum') AS produto_destaque,
        COALESCE(t.qtd, 0) AS qtd_produto_destaque
    FROM resumo r
    LEFT JOIN top_cookie t ON 1 = 1;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_listar_pedidos;
DELIMITER $$
CREATE PROCEDURE sp_listar_pedidos(
    IN p_status VARCHAR(20),
    IN p_data_inicio DATE,
    IN p_data_fim DATE,
    IN p_busca VARCHAR(120),
    IN p_offset INT,
    IN p_limit INT
)
BEGIN
    SELECT
        p.id,
        p.data_pedido,
        p.status,
        p.total,
        COALESCE(cl.nome, 'Cliente não identificado') AS cliente_nome,
        cl.email AS cliente_email,
        COUNT(pi.id) AS qtd_itens
    FROM pedidos p
    LEFT JOIN clientes cl ON cl.id = p.cliente_id
    LEFT JOIN pedido_itens pi ON pi.pedido_id = p.id
    WHERE (p_status IS NULL OR p_status = '' OR p.status = p_status)
      AND (p_data_inicio IS NULL OR DATE(p.data_pedido) >= p_data_inicio)
      AND (p_data_fim IS NULL OR DATE(p.data_pedido) <= p_data_fim)
      AND (
          p_busca IS NULL OR p_busca = ''
          OR cl.nome LIKE CONCAT('%', p_busca, '%')
          OR cl.email LIKE CONCAT('%', p_busca, '%')
          OR CAST(p.id AS CHAR) = p_busca
      )
    GROUP BY p.id, p.data_pedido, p.status, p.total, cl.nome, cl.email
    ORDER BY p.data_pedido DESC
    LIMIT p_offset, p_limit;

    SELECT COUNT(DISTINCT p.id) AS total_registros
    FROM pedidos p
    LEFT JOIN clientes cl ON cl.id = p.cliente_id
    WHERE (p_status IS NULL OR p_status = '' OR p.status = p_status)
      AND (p_data_inicio IS NULL OR DATE(p.data_pedido) >= p_data_inicio)
      AND (p_data_fim IS NULL OR DATE(p.data_pedido) <= p_data_fim)
      AND (
          p_busca IS NULL OR p_busca = ''
          OR cl.nome LIKE CONCAT('%', p_busca, '%')
          OR cl.email LIKE CONCAT('%', p_busca, '%')
          OR CAST(p.id AS CHAR) = p_busca
      );
END$$
DELIMITER ;

-- ============================================================
-- 6. Dados de exemplo (pedidos para dashboard)
--    Só insere se ainda não houver pedidos
-- ============================================================

INSERT INTO clientes (nome, email, telefone, senha_hash)
SELECT 'Ana Silva', 'ana@email.com', '44999990001',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE NOT EXISTS (SELECT 1 FROM clientes WHERE email = 'ana@email.com');

INSERT INTO clientes (nome, email, telefone, senha_hash)
SELECT 'Bruno Costa', 'bruno@email.com', '44999990002',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE NOT EXISTS (SELECT 1 FROM clientes WHERE email = 'bruno@email.com');

INSERT INTO clientes (nome, email, telefone, senha_hash)
SELECT 'Carla Mendes', 'carla@email.com', '44999990003',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE NOT EXISTS (SELECT 1 FROM clientes WHERE email = 'carla@email.com');

INSERT INTO pedidos (cliente_id, data_pedido, status)
SELECT c.id, '2026-08-01 14:30:00', 'entregue'
FROM clientes c
WHERE c.email = 'ana@email.com'
  AND NOT EXISTS (SELECT 1 FROM pedidos);

INSERT INTO pedido_itens (pedido_id, cookie_id, quantidade, preco_unitario, subtotal)
SELECT p.id, 3, 2, 18.50, 37.00
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id AND c.email = 'ana@email.com'
WHERE NOT EXISTS (SELECT 1 FROM pedido_itens pi WHERE pi.pedido_id = p.id);

INSERT INTO pedido_itens (pedido_id, cookie_id, quantidade, preco_unitario, subtotal)
SELECT p.id, 4, 1, 18.00, 18.00
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id AND c.email = 'ana@email.com'
WHERE (SELECT COUNT(*) FROM pedido_itens pi WHERE pi.pedido_id = p.id) = 1;

INSERT INTO pedidos (cliente_id, data_pedido, status)
SELECT c.id, '2026-08-10 18:00:00', 'confirmado'
FROM clientes c
WHERE c.email = 'bruno@email.com'
  AND (SELECT COUNT(*) FROM pedidos) < 2;

INSERT INTO pedido_itens (pedido_id, cookie_id, quantidade, preco_unitario, subtotal)
SELECT p.id, 3, 3, 18.50, 55.50
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id AND c.email = 'bruno@email.com'
WHERE NOT EXISTS (SELECT 1 FROM pedido_itens pi WHERE pi.pedido_id = p.id);

INSERT INTO pedidos (cliente_id, data_pedido, status)
SELECT c.id, '2026-08-15 11:20:00', 'entregue'
FROM clientes c
WHERE c.email = 'carla@email.com'
  AND (SELECT COUNT(*) FROM pedidos) < 3;

INSERT INTO pedido_itens (pedido_id, cookie_id, quantidade, preco_unitario, subtotal)
SELECT p.id, 10, 2, 18.90, 37.80
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id AND c.email = 'carla@email.com'
WHERE NOT EXISTS (SELECT 1 FROM pedido_itens pi WHERE pi.pedido_id = p.id);

INSERT INTO pedido_itens (pedido_id, cookie_id, quantidade, preco_unitario, subtotal)
SELECT p.id, 1, 4, 12.50, 50.00
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id AND c.email = 'carla@email.com'
WHERE (SELECT COUNT(*) FROM pedido_itens pi WHERE pi.pedido_id = p.id) = 1;

UPDATE pedidos p
SET p.total = (
    SELECT COALESCE(SUM(pi.subtotal), 0.00)
    FROM pedido_itens pi
    WHERE pi.pedido_id = p.id
);

-- ============================================================
-- 7. Consultas de verificação (opcional)
-- ============================================================
-- SELECT * FROM vw_dashboard_resumo;
-- CALL sp_dashboard_indicadores();
-- CALL sp_listar_pedidos(NULL, NULL, NULL, NULL, 0, 10);
