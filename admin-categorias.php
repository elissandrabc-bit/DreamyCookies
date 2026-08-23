<?php
require_once __DIR__ . '/includes/admin_helpers.php';
require_once __DIR__ . '/includes/crud.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Admin — Categorias';
$paginaAdmin = 'admin-categorias.php';
$paginaAdminAtiva = 'categorias';
$mostrarLista = false;
$categorias = [];
$categoriaEdicao = null;
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

            if ($acao === 'criar') {
                $resultado = criarCategoria($pdo, $_POST);
                redirecionarComFlash(
                    'admin-categorias.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }

            if ($acao === 'atualizar') {
                $id = (int) ($_POST['id'] ?? 0);
                $resultado = atualizarCategoria($pdo, $id, $_POST);
                redirecionarComFlash(
                    'admin-categorias.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }

            if ($acao === 'excluir') {
                $id = (int) ($_POST['id'] ?? 0);
                $resultado = excluirCategoria($pdo, $id);
                redirecionarComFlash(
                    'admin-categorias.php',
                    $resultado['sucesso'] ? 'sucesso' : 'erro',
                    $resultado['mensagem']
                );
            }
        }

        $categorias = buscarCategorias($pdo);

        $editarId = isset($_GET['editar']) ? (int) $_GET['editar'] : 0;
        if ($editarId > 0) {
            $categoriaEdicao = buscarCategoriaPorId($pdo, $editarId);
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Não foi possível carregar as categorias.';
        $mostrarLista = false;
    }
}

renderizarPagina('admin-categorias', compact(
    'titulo',
    'paginaAdmin',
    'paginaAdminAtiva',
    'mensagem_erro',
    'mensagem_sucesso',
    'categorias',
    'categoriaEdicao',
    'mostrarLista'
));
