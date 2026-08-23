<?php
require_once __DIR__ . '/includes/admin_helpers.php';
require_once __DIR__ . '/includes/crud.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Admin — Cookies';
$paginaAdmin = 'admin-cookies.php';
$paginaAdminAtiva = 'cookies';
$mostrarLista = false;
$cookies = [];
$categorias = [];
$cookieEdicao = null;
$mensagem_erro = '';
$mensagem_sucesso = consumirFlash('sucesso');
$mensagem_erro = consumirFlash('erro');

$auth = processarAutenticacaoAdmin();
if ($auth['mensagem_erro'] !== '') {
    $mensagem_erro = $auth['mensagem_erro'];
}

if ($auth['logado']) {
    $mostrarLista = true;

    try {
        $pdo = obterConexao();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = $_POST['acao'] ?? '';
            $categoriasIds = normalizarIdsCategorias($_POST);

            if ($acao === 'criar') {
                $resultado = criarCookie($pdo, $_POST, $categoriasIds);
                redirecionarComFlash(
                    'admin-cookies.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }

            if ($acao === 'atualizar') {
                $id = (int) ($_POST['id'] ?? 0);
                $resultado = atualizarCookie($pdo, $id, $_POST, $categoriasIds);
                redirecionarComFlash(
                    'admin-cookies.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }

            if ($acao === 'excluir') {
                $id = (int) ($_POST['id'] ?? 0);
                $resultado = excluirCookie($pdo, $id);
                redirecionarComFlash(
                    'admin-cookies.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }
        }

        $cookies = buscarCookiesAdmin($pdo);
        $categorias = buscarCategorias($pdo);

        $editarId = isset($_GET['editar']) ? (int) $_GET['editar'] : 0;
        if ($editarId > 0) {
            $cookieEdicao = buscarCookieAdminPorId($pdo, $editarId);
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Não foi possível carregar os cookies.';
        $mostrarLista = false;
    }
}

renderizarPagina('admin-cookies', compact(
    'titulo',
    'paginaAdmin',
    'paginaAdminAtiva',
    'mensagem_erro',
    'mensagem_sucesso',
    'cookies',
    'categorias',
    'cookieEdicao',
    'mostrarLista'
));
