<?php
/**
 * GET /api/pedidos.php
 * Listagem paginada de pedidos (CALL sp_listar_pedidos).
 *
 * Query params: status, data_inicio, data_fim, busca, pagina, por_pagina
 */

require_once __DIR__ . '/../includes/api_helpers.php';
require_once __DIR__ . '/../includes/api.php';

apiConfigurarCors();
apiExigirGet();

$status = apiParametroGet('status');
$dataInicio = apiParametroGet('data_inicio');
$dataFim = apiParametroGet('data_fim');
$busca = apiParametroGet('busca');
$pagina = apiParametroInteiro('pagina', 1, 1, 9999);
$porPagina = apiParametroInteiro('por_pagina', 10, 1, 50);

if ($status !== null && !in_array($status, ['pendente', 'confirmado', 'entregue', 'cancelado'], true)) {
    apiEnviarErro('Status inválido.', 400);
}

if ($dataInicio !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataInicio)) {
    apiEnviarErro('data_inicio deve estar no formato YYYY-MM-DD.', 400);
}

if ($dataFim !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataFim)) {
    apiEnviarErro('data_fim deve estar no formato YYYY-MM-DD.', 400);
}

try {
    $pdo = obterConexao();
    $resultado = apiListarPedidos($pdo, [
        'status'      => $status,
        'data_inicio' => $dataInicio,
        'data_fim'    => $dataFim,
        'busca'       => $busca,
        'pagina'      => $pagina,
        'por_pagina'  => $porPagina,
    ]);

    apiEnviarJson([
        'sucesso'   => true,
        'pedidos'   => $resultado['pedidos'],
        'paginacao' => $resultado['paginacao'],
    ]);
} catch (Throwable $e) {
    apiEnviarErro('Falha ao listar pedidos.', 500);
}
