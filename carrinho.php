<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Carrinho - Dreamy Cookies';
$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        $pdo = obterConexao();

        if ($acao === 'adicionar') {
            $cookieId = (int) ($_POST['cookie_id'] ?? 0);
            $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));
            $resultado = adicionarAoCarrinho($pdo, $cookieId, $quantidade);
            $retorno = urlRetornoCarrinho($_POST['retorno'] ?? 'index.php');

            if ($resultado['sucesso']) {
                $_SESSION['flash_sucesso'] = $resultado['mensagem'];
            } else {
                $_SESSION['flash_erro'] = $resultado['mensagem'];
            }

            header('Location: ' . $retorno);
            exit;
        } elseif ($acao === 'atualizar') {
            $cookieId = (int) ($_POST['cookie_id'] ?? 0);
            $quantidade = (int) ($_POST['quantidade'] ?? 0);

            if (atualizarQuantidadeCarrinho($cookieId, $quantidade)) {
                $mensagem_sucesso = 'Carrinho atualizado.';
            } else {
                $mensagem_erro = 'Não foi possível atualizar o item.';
            }
        } elseif ($acao === 'remover') {
            $cookieId = (int) ($_POST['cookie_id'] ?? 0);
            removerDoCarrinho($cookieId);
            $mensagem_sucesso = 'Item removido do carrinho.';
        } elseif ($acao === 'limpar') {
            limparCarrinho();
            $mensagem_sucesso = 'Carrinho esvaziado.';
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao processar o carrinho. Verifique a conexão com o banco.';
    }
}

try {
    $pdo = obterConexao();
    $itensCarrinho = montarItensCarrinho($pdo);
    $totalCarrinho = calcularTotalCarrinho($itensCarrinho);
    $linkWhatsAppPedido = !empty($itensCarrinho)
        ? gerarLinkWhatsAppCarrinho($itensCarrinho, $totalCarrinho)
        : linkWhatsAppLoja();
} catch (PDOException $e) {
    $itensCarrinho = [];
    $totalCarrinho = 0.0;
    $linkWhatsAppPedido = linkWhatsAppLoja();
    $mensagem_erro = $mensagem_erro ?: 'Não foi possível carregar o carrinho.';
}

renderizarPagina('carrinho', compact(
    'titulo',
    'mensagem_sucesso',
    'mensagem_erro',
    'itensCarrinho',
    'totalCarrinho',
    'linkWhatsAppPedido'
));
