<?php
/**
 * GET /api/dashboard.php
 * Indicadores consolidados para a dashboard (JSON).
 */

require_once __DIR__ . '/../includes/api_helpers.php';
require_once __DIR__ . '/../includes/api.php';

apiConfigurarCors();
apiExigirGet();
apiExigirAdmin();

try {
    $pdo = obterConexao();

    apiEnviarJson([
        'sucesso'              => true,
        'indicadores'          => apiObterIndicadoresDashboard($pdo),
        'vendas_por_categoria' => apiObterVendasPorCategoria($pdo),
        'ranking_cookies'      => apiObterRankingCookies($pdo),
    ]);
} catch (Throwable $e) {
    apiEnviarErro('Falha ao carregar indicadores da dashboard.', 500);
}
