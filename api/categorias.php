<?php
/**
 * GET /api/categorias.php
 * Lista todas as categorias em JSON.
 */

require_once __DIR__ . '/../includes/api_helpers.php';
require_once __DIR__ . '/../includes/api.php';

apiConfigurarCors();
apiExigirGet();

try {
    $pdo = obterConexao();

    apiEnviarJson([
        'sucesso'    => true,
        'categorias' => apiObterCategorias($pdo),
    ]);
} catch (Throwable $e) {
    apiEnviarErro('Falha ao carregar categorias.', 500);
}
