<section class="hero text-center mb-4">
    <h1 class="hero-title" style="font-size: clamp(1.75rem, 5vw, 2.75rem);">Meu Carrinho</h1>
    <p class="hero-subtitle">Revise seus cookies e veja o total do pedido</p>
</section>

<?php if (empty($itensCarrinho)): ?>
    <div class="alert alert-warning text-center">
        Seu carrinho está vazio. Escolha seus cookies favoritos no cardápio!
    </div>
    <a href="index.php" class="btn btn-dreamy">Ver cardápio</a>
<?php else: ?>
    <div class="carrinho-card mb-4">
        <?php foreach ($itensCarrinho as $item): ?>
            <div class="carrinho-item row g-3 align-items-center">
                <div class="col-auto">
                    <img src="<?= htmlspecialchars(urlImagemCookie($item['imagem'])) ?>"
                         alt="<?= htmlspecialchars($item['nome']) ?>"
                         class="carrinho-item-img">
                </div>
                <div class="col-md-4 col-lg-5">
                    <h2 class="carrinho-item-nome"><?= htmlspecialchars($item['nome']) ?></h2>
                    <p class="carrinho-item-preco-unit"><?= formatarPreco($item['preco']) ?> cada</p>
                </div>
                <div class="col-6 col-md-3">
                    <form method="post" action="carrinho.php" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="acao" value="atualizar">
                        <input type="hidden" name="cookie_id" value="<?= $item['id'] ?>">
                        <label class="form-label label-dreamy mb-0 small" style="color: #fff !important;">Qtd</label>
                        <input type="number" name="quantidade" class="form-control dreamy-input carrinho-qtd-input"
                               value="<?= $item['quantidade'] ?>" min="1" max="20" required>
                        <button type="submit" class="btn btn-dreamy btn-sm">OK</button>
                    </form>
                </div>
                <div class="col-6 col-md-2 text-md-end">
                    <span class="carrinho-item-subtotal"><?= formatarPreco($item['subtotal']) ?></span>
                </div>
                <div class="col-12 col-md-auto ms-md-auto">
                    <form method="post" action="carrinho.php">
                        <input type="hidden" name="acao" value="remover">
                        <input type="hidden" name="cookie_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-outline-light btn-sm">Remover</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carrinho-resumo">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="carrinho-resumo-label mb-1">Total do pedido</p>
                <p class="carrinho-resumo-total mb-0"><?= formatarPreco($totalCarrinho) ?></p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php" class="btn btn-outline-light">Continuar comprando</a>
                <a href="<?= htmlspecialchars($linkWhatsAppPedido) ?>" class="btn btn-dreamy" target="_blank" rel="noopener">
                    Finalizar no WhatsApp
                </a>
            </div>
        </div>
    </div>

    <form method="post" action="carrinho.php" class="mt-3">
        <input type="hidden" name="acao" value="limpar">
        <button type="submit" class="btn btn-link text-danger p-0">Esvaziar carrinho</button>
    </form>
<?php endif; ?>
