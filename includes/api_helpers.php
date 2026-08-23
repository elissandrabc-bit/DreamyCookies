<?php
/**
 * Helpers HTTP/JSON para a API REST do Dreamy Cookies.
 */

/**
 * Envia resposta JSON e encerra a execução.
 *
 * @param array $dados
 * @param int $statusCode
 * @return never
 */
function apiEnviarJson(array $dados, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');

    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Envia erro JSON padronizado.
 *
 * @param string $mensagem
 * @param int $statusCode
 * @return never
 */
function apiEnviarErro(string $mensagem, int $statusCode = 500): never
{
    apiEnviarJson([
        'sucesso' => false,
        'erro'    => $mensagem,
    ], $statusCode);
}

/**
 * Cabeçalhos CORS para consumo via fetch (dashboard TypeScript).
 */
function apiConfigurarCors(): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

/**
 * Garante que o endpoint aceita apenas GET.
 */
function apiExigirGet(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        apiEnviarErro('Método não permitido. Use GET.', 405);
    }
}

/**
 * Lê parâmetro GET como string ou null.
 */
function apiParametroGet(string $nome): ?string
{
    if (!isset($_GET[$nome])) {
        return null;
    }

    $valor = trim((string) $_GET[$nome]);

    return $valor === '' ? null : $valor;
}

/**
 * Lê parâmetro GET inteiro positivo com valor padrão.
 */
function apiParametroInteiro(string $nome, int $padrao, int $minimo = 1, int $maximo = 100): int
{
    if (!isset($_GET[$nome]) || !is_numeric($_GET[$nome])) {
        return $padrao;
    }

    $valor = (int) $_GET[$nome];

    return max($minimo, min($maximo, $valor));
}
