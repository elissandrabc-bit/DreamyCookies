<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

if (clienteLogado()) {
    header('Location: index.php');
    exit;
}

$titulo = 'Cadastro - Dreamy Cookies';
$dadosForm = [];
$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosForm = [
        'nome'             => $_POST['nome'] ?? '',
        'email'            => $_POST['email'] ?? '',
        'telefone'         => $_POST['telefone'] ?? '',
        'senha'            => $_POST['senha'] ?? '',
        'confirmar_senha'  => $_POST['confirmar_senha'] ?? '',
    ];

    try {
        $pdo = obterConexao();
        $resultado = cadastrarCliente($pdo, $dadosForm);

        if ($resultado['sucesso']) {
            $mensagem_sucesso = $resultado['mensagem'];
            $dadosForm = [];
        } else {
            $mensagem_erro = $resultado['mensagem'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao conectar ao banco de dados. Verifique config/database.php.';
    }
}

renderizarPagina('cadastro', compact('titulo', 'dadosForm', 'mensagem_sucesso', 'mensagem_erro'));
