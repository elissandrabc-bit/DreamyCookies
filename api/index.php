<?php
/**
 * Índice da API REST — Dreamy Cookies
 */

require_once __DIR__ . '/../includes/api_helpers.php';

apiConfigurarCors();
apiExigirGet();

apiEnviarJson([
    'sucesso'   => true,
    'nome'      => 'Dreamy Cookies API',
    'versao'    => '1.0',
    'endpoints' => [
        [
            'metodo'      => 'GET',
            'url'         => 'api/dashboard.php',
            'descricao'   => 'Indicadores, vendas por categoria e ranking de cookies',
        ],
        [
            'metodo'      => 'GET',
            'url'         => 'api/pedidos.php',
            'descricao'   => 'Pedidos paginados (sp_listar_pedidos)',
            'parametros'  => ['status', 'data_inicio', 'data_fim', 'busca', 'pagina', 'por_pagina'],
        ],
        [
            'metodo'      => 'GET',
            'url'         => 'api/cookies.php',
            'descricao'   => 'Cardápio de cookies ativos',
        ],
        [
            'metodo'      => 'GET',
            'url'         => 'api/categorias.php',
            'descricao'   => 'Lista todas as categorias',
        ],
    ],
]);
