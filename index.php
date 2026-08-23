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
    $mensagem_erro = 'Não foi possível conectar ao banco de dados local. Verifique se o MySQL está ligado no XAMPP e se o banco dreamy_cookies foi importado no phpMyAdmin.';
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
