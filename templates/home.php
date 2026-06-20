<section class="hero text-center mb-5">
    <h1 class="hero-title">DREAMY COOKIES</h1>
    <p class="hero-subtitle">Cookies artesanais feitos com carinho em Goioerê-PR</p>
</section>

<!-- Bootstrap: Form + Input Group para filtros -->
<section class="filtros mb-4">
    <form method="get" action="index.php" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="categoria" class="form-label text-light">Filtrar por tipo</label>
            <select name="categoria" id="categoria" class="form-select dreamy-input">
                <option value="">Todos os cookies</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($categoriaSelecionada ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <label for="busca" class="form-label text-light">Buscar sabor</label>
            <input type="text" name="busca" id="busca" class="form-control dreamy-input"
                   placeholder="Ex: Nutella, Pistache, Tradicional..."
                   value="<?= htmlspecialchars($termoBusca ?? '') ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-dreamy w-100">Filtrar</button>
        </div>
    </form>
</section>

<?php if (empty($cookiesExibidos)): ?>
    <?php if (!empty($mensagem_erro)): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($mensagem_erro) ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Nenhum cookie encontrado com os filtros selecionados.
        </div>
    <?php endif; ?>
<?php else: ?>
    <p class="text-light mb-3">
        <?= count($cookiesExibidos) ?> cookie(s) encontrado(s) —
        adicione ao carrinho e finalize o pedido quando terminar de escolher.
    </p>

    <!-- Bootstrap: Cards em grid -->
    <div class="row g-4">
        <?php foreach ($cookiesExibidos as $cookie): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="card cookie-card h-100">
                    <img src="<?= htmlspecialchars(urlImagemCookie($cookie['imagem'])) ?>"
                         class="card-img-top cookie-img"
                         alt="<?= htmlspecialchars($cookie['nome']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h2 class="cookie-nome"><?= htmlspecialchars(mb_strtoupper($cookie['nome'])) ?></h2>
                        <p class="cookie-descricao flex-grow-1"><?= htmlspecialchars($cookie['descricao']) ?></p>

                        <?php if (!empty($cookie['categorias_nomes'])): ?>
                            <div class="mb-2">
                                <?php foreach ($cookie['categorias_nomes'] as $catNome): ?>
                                    <span class="badge bg-dreamy-accent me-1"><?= htmlspecialchars($catNome) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $retornoCardapio = 'index.php';
                        $paramsRetorno = [];
                        if (!empty($categoriaSelecionada)) {
                            $paramsRetorno['categoria'] = $categoriaSelecionada;
                        }
                        if (!empty($termoBusca)) {
                            $paramsRetorno['busca'] = $termoBusca;
                        }
                        if (!empty($paramsRetorno)) {
                            $retornoCardapio .= '?' . http_build_query($paramsRetorno);
                        }
                        ?>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="cookie-preco"><?= formatarPreco($cookie['preco']) ?></span>
                            <form method="post" action="carrinho.php" class="m-0">
                                <input type="hidden" name="acao" value="adicionar">
                                <input type="hidden" name="cookie_id" value="<?= $cookie['id'] ?>">
                                <input type="hidden" name="quantidade" value="1">
                                <input type="hidden" name="retorno" value="<?= htmlspecialchars($retornoCardapio) ?>">
                                <button type="submit" class="btn btn-dreamy btn-sm">Adicionar ao carrinho</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Bootstrap: Modal com informações da loja -->
<div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dreamy-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoLabel">Sobre a Dreamy Cookies</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Cookies artesanais com massa tradicional, recheios cremosos e sabores exclusivos.</p>
                <p><strong>Horário:</strong> A partir das 18h30</p>
                <p><strong>Local:</strong> Goioerê - PR</p>
                <a href="<?= linkWhatsAppLoja() ?>" class="btn btn-dreamy" target="_blank" rel="noopener">
                    Fazer pedido no WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<a href="historia.php" class="btn btn-outline-light btn-sm mt-4">
    Conheça nossa história
</a>
