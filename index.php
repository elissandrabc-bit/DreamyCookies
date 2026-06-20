<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Dreamy Cookies - Cardápio';
$categoriaSelecionada = isset($_GET['categoria']) && $_GET['categoria'] !== ''
    ? (int) $_GET['categoria']
    : null;
$termoBusca = $_GET['busca'] ?? '';
$mensagem_sucesso = consumirFlash('sucesso');
$mensagem_erro = consumirFlash('erro');

try {
    $pdo = obterConexao();
    $cookies = buscarCookies($pdo);
    $categorias = buscarCategorias($pdo);

    $cookiesFiltrados = filtrarCookiesPorCategoria($cookies, $categoriaSelecionada);
    $cookiesExibidos = filtrarCookiesPorBusca($cookiesFiltrados, $termoBusca);
} catch (PDOException $e) {
    $cookiesExibidos = [];
    $categorias = [];
    $mensagem_erro = 'Não foi possível conectar ao banco de dados na VM (192.168.56.101). Verifique se o MySQL está ligado e se o usuário dreamy_user foi criado no phpMyAdmin da VM.';
}

renderizarPagina('home', compact(
    'titulo',
    'cookiesExibidos',
    'categorias',
    'categoriaSelecionada',
    'termoBusca',
    'mensagem_sucesso',
    'mensagem_erro'
));
