<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

if (clienteLogado()) {
    header('Location: index.php');
    exit;
}

$titulo = 'Login - Dreamy Cookies';
$emailForm = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailForm = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($emailForm === '' || $senha === '') {
        $mensagem_erro = 'Preencha e-mail e senha.';
    } else {
        try {
            $pdo = obterConexao();
            $cliente = autenticarCliente($pdo, $emailForm, $senha);

            if ($cliente) {
                $_SESSION['cliente_id'] = $cliente['id'];
                $_SESSION['cliente_nome'] = $cliente['nome'];
                header('Location: index.php');
                exit;
            }

            $mensagem_erro = 'E-mail ou senha incorretos.';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao conectar ao banco de dados. Verifique config/database.php.';
        }
    }
}

renderizarPagina('login', compact('titulo', 'emailForm', 'mensagem_erro'));
