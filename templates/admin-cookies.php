<?php require __DIR__ . '/partials/admin-login.php'; ?>

<?php if ($mostrarLista): ?>
    <section class="hero text-center mb-4">
        <h1 class="hero-title" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">CRUD — Cookies</h1>
        <p class="hero-subtitle">Gerencie o cardápio completo</p>
    </section>

    <div class="admin-form-card mb-4">
        <h2 class="admin-form-titulo"><?= $cookieEdicao ? 'Editar cookie #' . (int) $cookieEdicao['id'] : 'Novo cookie' ?></h2>
        <form method="post" action="admin-cookies.php" class="row g-3">
            <input type="hidden" name="acao" value="<?= $cookieEdicao ? 'atualizar' : 'criar' ?>">
            <?php if ($cookieEdicao): ?>
                <input type="hidden" name="id" value="<?= (int) $cookieEdicao['id'] ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Nome</label>
                <input type="text" name="nome" class="form-control dreamy-input" required
                       value="<?= htmlspecialchars($cookieEdicao['nome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label label-dreamy" style="color:#fff!important;">Preço (R$)</label>
                <input type="text" name="preco" class="form-control dreamy-input" required
                       value="<?= isset($cookieEdicao) ? number_format($cookieEdicao['preco'], 2, '.', '') : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label label-dreamy" style="color:#fff!important;">Imagem (arquivo)</label>
                <input type="text" name="imagem" class="form-control dreamy-input" required
                       placeholder="ex.: brookie.jpg"
                       value="<?= htmlspecialchars($cookieEdicao['imagem'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label label-dreamy" style="color:#fff!important;">Descrição</label>
                <textarea name="descricao" class="form-control dreamy-input" rows="2" required><?= htmlspecialchars($cookieEdicao['descricao'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <span class="form-label label-dreamy d-block mb-2" style="color:#fff!important;">Categorias</span>
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($categorias as $cat): ?>
                        <?php
                        $marcado = isset($cookieEdicao) && in_array($cat['id'], $cookieEdicao['categorias_ids'], true);
                        ?>
                        <label class="admin-check-label">
                            <input type="checkbox" name="categorias_ids[]" value="<?= $cat['id'] ?>"
                                <?= $marcado ? 'checked' : '' ?>>
                            <?= htmlspecialchars($cat['nome']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-12">
                <label class="admin-check-label">
                    <input type="checkbox" name="ativo" value="1"
                        <?= !isset($cookieEdicao) || !empty($cookieEdicao['ativo']) ? 'checked' : '' ?>>
                    Ativo no cardápio
                </label>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-dreamy">
                    <?= $cookieEdicao ? 'Salvar alterações' : 'Cadastrar cookie' ?>
                </button>
                <?php if ($cookieEdicao): ?>
                    <a href="admin-cookies.php" class="btn btn-outline-light">Cancelar edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-responsive admin-tabela-wrap">
        <table class="table table-striped table-hover admin-tabela mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categorias</th>
                    <th>Ativo</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cookies)): ?>
                    <tr><td colspan="6" class="text-center">Nenhum cookie cadastrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($cookies as $cookie): ?>
                        <tr>
                            <td><?= $cookie['id'] ?></td>
                            <td><?= htmlspecialchars($cookie['nome']) ?></td>
                            <td><?= formatarPreco($cookie['preco']) ?></td>
                            <td><?= htmlspecialchars($cookie['categorias'] ?: '—') ?></td>
                            <td><?= $cookie['ativo'] ? 'Sim' : 'Não' ?></td>
                            <td class="text-end admin-acoes">
                                <a href="admin-cookies.php?editar=<?= $cookie['id'] ?>" class="btn btn-sm btn-dreamy">Editar</a>
                                <form method="post" action="admin-cookies.php" class="d-inline"
                                      onsubmit="return confirm('Excluir o cookie <?= htmlspecialchars(addslashes($cookie['nome'])) ?>? Esta ação não pode ser desfeita.');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $cookie['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
