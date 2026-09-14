<?php
require_once __DIR__ . '/includes/admin_helpers.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Admin — Dashboard';
$paginaAdmin = 'dashboard.php';
$paginaAdminAtiva = 'dashboard';
$mostrarLista = false;
$mensagem_erro = consumirFlash('erro');
$mensagem_sucesso = consumirFlash('sucesso');

$auth = processarAutenticacaoAdmin();
if ($auth['mensagem_erro'] !== '') {
    $mensagem_erro = $auth['mensagem_erro'];
}

if ($auth['logado']) {
    $mostrarLista = true;
}

renderizarPagina('dashboard', compact(
    'titulo',
    'paginaAdmin',
    'paginaAdminAtiva',
    'mensagem_erro',
    'mensagem_sucesso',
    'mostrarLista'
));
