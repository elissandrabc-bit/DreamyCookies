<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Clientes cadastrados - Dreamy Cookies';
$mensagem_erro = '';
$clientes = [];
$mostrarLista = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'sair') {
    sairAdmin();
    header('Location: admin-clientes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'entrar') {
    $senha = $_POST['senha'] ?? '';

    if ($senha === '') {
        $mensagem_erro = 'Informe a senha de administrador.';
    } elseif (!autenticarAdmin($senha)) {
        $mensagem_erro = 'Senha incorreta.';
    }
}

if (adminLogado()) {
    try {
        $pdo = obterConexao();
        $clientes = buscarClientes($pdo);
        $mostrarLista = true;
    } catch (PDOException $e) {
        $mensagem_erro = 'Não foi possível carregar os clientes. Verifique a conexão com o banco.';
    }
}

renderizarPagina('admin-clientes', compact(
    'titulo',
    'mensagem_erro',
    'clientes',
    'mostrarLista'
));
