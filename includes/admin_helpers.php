<?php
/**
 * Autenticação compartilhada — área administrativa.
 */

require_once __DIR__ . '/functions.php';

/**
 * @return array{logado: bool, mensagem_erro: string}
 */
function processarAutenticacaoAdmin(): array
{
    $mensagemErro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'sair') {
        sairAdmin();
        header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'admin-clientes.php'));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'entrar') {
        $senha = $_POST['senha'] ?? '';

        if ($senha === '') {
            $mensagemErro = 'Informe a senha de administrador.';
        } elseif (!autenticarAdmin($senha)) {
            $mensagemErro = 'Senha incorreta.';
        }
    }

    return [
        'logado'         => adminLogado(),
        'mensagem_erro'  => $mensagemErro,
    ];
}

function exigirAdminLogado(): void
{
    if (!adminLogado()) {
        return;
    }
}

function definirFlash(string $tipo, string $mensagem): void
{
    iniciarSessao();
    $_SESSION['flash_' . $tipo] = $mensagem;
}

function redirecionarComFlash(string $url, string $tipo, string $mensagem): never
{
    definirFlash($tipo, $mensagem);
    header('Location: ' . $url);
    exit;
}
