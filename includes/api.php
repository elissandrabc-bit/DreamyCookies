<?php
/**
 * Funções de negócio da API — dashboard e pedidos.
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Indicadores gerais via stored procedure.
 *
 * @param PDO $pdo
 * @return array<string, mixed>
 */
function apiObterIndicadoresDashboard(PDO $pdo): array
{
    $stmt = $pdo->query('CALL sp_dashboard_indicadores()');
    $linha = $stmt->fetch();

    if (!$linha) {
        return [
            'total_pedidos'        => 0,
            'faturamento_total'    => 0.0,
            'unidades_vendidas'    => 0,
            'produto_destaque'     => 'Nenhum',
            'qtd_produto_destaque' => 0,
        ];
    }

    return [
        'total_pedidos'        => (int) $linha['total_pedidos'],
        'faturamento_total'    => (float) $linha['faturamento_total'],
        'unidades_vendidas'    => (int) $linha['unidades_vendidas'],
        'produto_destaque'     => (string) $linha['produto_destaque'],
        'qtd_produto_destaque' => (int) $linha['qtd_produto_destaque'],
    ];
}

/**
 * Vendas agrupadas por categoria (view analítica).
 *
 * @param PDO $pdo
 * @return array<int, array<string, mixed>>
 */
function apiObterVendasPorCategoria(PDO $pdo): array
{
    $stmt = $pdo->query('
        SELECT
            categoria_id,
            categoria_nome,
            quantidade_vendida,
            faturamento_categoria
        FROM vw_vendas_por_categoria
        ORDER BY faturamento_categoria DESC
    ');

    $categorias = [];

    while ($row = $stmt->fetch()) {
        $categorias[] = [
            'categoria_id'          => (int) $row['categoria_id'],
            'categoria_nome'        => (string) $row['categoria_nome'],
            'quantidade_vendida'    => (int) $row['quantidade_vendida'],
            'faturamento_categoria' => (float) $row['faturamento_categoria'],
        ];
    }

    return $categorias;
}

/**
 * Ranking de cookies vendidos (para gráficos e destaques no TS).
 *
 * @param PDO $pdo
 * @return array<int, array<string, mixed>>
 */
function apiObterRankingCookies(PDO $pdo): array
{
    $sql = "
        SELECT
            ck.id AS cookie_id,
            ck.nome AS cookie_nome,
            SUM(pi.quantidade) AS quantidade_vendida,
            SUM(pi.subtotal) AS faturamento
        FROM pedido_itens pi
        INNER JOIN pedidos p ON p.id = pi.pedido_id
        INNER JOIN cookies ck ON ck.id = pi.cookie_id
        WHERE p.status IN ('confirmado', 'entregue')
        GROUP BY ck.id, ck.nome
        ORDER BY quantidade_vendida DESC, ck.nome ASC
    ";

    $stmt = $pdo->query($sql);
    $ranking = [];

    while ($row = $stmt->fetch()) {
        $ranking[] = [
            'cookie_id'          => (int) $row['cookie_id'],
            'cookie_nome'        => (string) $row['cookie_nome'],
            'quantidade_vendida' => (int) $row['quantidade_vendida'],
            'faturamento'        => (float) $row['faturamento'],
        ];
    }

    return $ranking;
}

/**
 * Lista pedidos com filtros via stored procedure (2 result sets).
 *
 * @param PDO $pdo
 * @param array<string, mixed> $filtros
 * @return array{pedidos: array<int, array<string, mixed>>, paginacao: array<string, int|float>}
 */
function apiListarPedidos(PDO $pdo, array $filtros): array
{
    $pagina = max(1, (int) ($filtros['pagina'] ?? 1));
    $porPagina = max(1, min(50, (int) ($filtros['por_pagina'] ?? 10)));
    $offset = ($pagina - 1) * $porPagina;

    $stmt = $pdo->prepare('CALL sp_listar_pedidos(?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $filtros['status'] ?? null,
        $filtros['data_inicio'] ?? null,
        $filtros['data_fim'] ?? null,
        $filtros['busca'] ?? null,
        $offset,
        $porPagina,
    ]);

    $pedidos = [];
    while ($row = $stmt->fetch()) {
        $pedidos[] = [
            'id'             => (int) $row['id'],
            'data_pedido'    => (string) $row['data_pedido'],
            'status'         => (string) $row['status'],
            'total'          => (float) $row['total'],
            'cliente_nome'   => (string) $row['cliente_nome'],
            'cliente_email'  => $row['cliente_email'] !== null ? (string) $row['cliente_email'] : null,
            'qtd_itens'      => (int) $row['qtd_itens'],
        ];
    }

    $totalRegistros = 0;
    if ($stmt->nextRowset()) {
        $contagem = $stmt->fetch();
        $totalRegistros = (int) ($contagem['total_registros'] ?? 0);
    }

    $totalPaginas = $porPagina > 0 ? (int) ceil($totalRegistros / $porPagina) : 0;

    return [
        'pedidos' => $pedidos,
        'paginacao' => [
            'pagina'          => $pagina,
            'por_pagina'      => $porPagina,
            'total_registros' => $totalRegistros,
            'total_paginas'   => $totalPaginas,
        ],
    ];
}

/**
 * Cookies ativos para consumo externo (cardápio/dashboard).
 *
 * @param PDO $pdo
 * @return array<int, array<string, mixed>>
 */
function apiObterCookies(PDO $pdo): array
{
    require_once __DIR__ . '/functions.php';

    $cookies = buscarCookies($pdo);

    return array_map(static function (array $cookie): array {
        return [
            'id'               => $cookie['id'],
            'nome'             => $cookie['nome'],
            'descricao'        => $cookie['descricao'],
            'preco'            => $cookie['preco'],
            'imagem'           => $cookie['imagem'],
            'categorias_ids'   => $cookie['categorias_ids'],
            'categorias_nomes' => $cookie['categorias_nomes'],
        ];
    }, $cookies);
}

/**
 * Todas as categorias cadastradas.
 *
 * @param PDO $pdo
 * @return array<int, array<string, mixed>>
 */
function apiObterCategorias(PDO $pdo): array
{
    require_once __DIR__ . '/functions.php';

    $categorias = buscarCategorias($pdo);

    return array_map(static function (array $categoria): array {
        return [
            'id'        => (int) $categoria['id'],
            'nome'      => (string) $categoria['nome'],
            'descricao' => $categoria['descricao'] !== null ? (string) $categoria['descricao'] : null,
        ];
    }, $categorias);
}
