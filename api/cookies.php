<?php
/**
 * GET /api/cookies.php
 * Cardápio em JSON (cookies ativos + categorias).
 */

require_once __DIR__ . '/../includes/api_helpers.php';
require_once __DIR__ . '/../includes/api.php';

apiConfigurarCors();
apiExigirGet();

try {
    $pdo = obterConexao();

    apiEnviarJson([
        'sucesso' => true,
        'cookies' => apiObterCookies($pdo),
    ]);
} catch (Throwable $e) {
    apiEnviarErro('Falha ao carregar cookies.', 500);
}
