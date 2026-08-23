<?php
/**
 * Exemplo para HOSPEDAGEM GRATUITA (InfinityFree, AwardSpace, etc.)
 * Copie para config/database.php e preencha com os dados do painel MySQL.
 *
 * Na hospedagem, DB_HOST costuma ser algo como:
 *   sql123.infinityfree.com
 * (NÃO use o IP da VM — ela não é acessível pela internet)
 */

define('DB_HOST', 'sql000.infinityfree.com');
define('DB_PORT', '3306');
define('DB_NAME', 'if0_00000000_dreamy');
define('DB_USER', 'if0_00000000');
define('DB_PASS', 'coloque_a_senha_do_painel');
define('DB_CHARSET', 'utf8mb4');

function obterConexao(): PDO
{
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, DB_USER, DB_PASS, $opcoes);
}
