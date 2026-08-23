<?php require __DIR__ . '/partials/admin-login.php'; ?>

<?php if ($mostrarLista): ?>
    <section class="hero text-center mb-4">
        <h1 class="hero-title" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">CRUD — Categorias</h1>
        <p class="hero-subtitle">Tipos de cookies do cardápio</p>
    </section>

    <div class="admin-form-card mb-4">
        <h2 class="admin-form-titulo"><?= $categoriaEdicao ? 'Editar categoria #' . (int) $categoriaEdicao['id'] : 'Nova categoria' ?></h2>
        <form method="post" action="admin-categorias.php" class="row g-3">
            <input type="hidden" name="acao" value="<?= $categoriaEdicao ? 'atualizar' : 'criar' ?>">
            <?php if ($categoriaEdicao): ?>
                <input type="hidden" name="id" value="<?= (int) $categoriaEdicao['id'] ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Nome</label>
                <input type="text" name="nome" class="form-control dreamy-input" required
                       value="<?= htmlspecialchars($categoriaEdicao['nome'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Descrição</label>
                <input type="text" name="descricao" class="form-control dreamy-input"
                       value="<?= htmlspecialchars($categoriaEdicao['descricao'] ?? '') ?>">
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-dreamy">
                    <?= $categoriaEdicao ? 'Salvar alterações' : 'Cadastrar categoria' ?>
                </button>
                <?php if ($categoriaEdicao): ?>
                    <a href="admin-categorias.php" class="btn btn-outline-light">Cancelar edição</a>
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
                    <th>Descrição</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categorias)): ?>
                    <tr><td colspan="4" class="text-center">Nenhuma categoria cadastrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><?= htmlspecialchars($cat['nome']) ?></td>
                            <td><?= htmlspecialchars($cat['descricao'] ?? '—') ?></td>
                            <td class="text-end admin-acoes">
                                <a href="admin-categorias.php?editar=<?= $cat['id'] ?>" class="btn btn-sm btn-dreamy">Editar</a>
                                <form method="post" action="admin-categorias.php" class="d-inline"
                                      onsubmit="return confirm('Excluir a categoria <?= htmlspecialchars(addslashes($cat['nome'])) ?>? Cookies serão desvinculados desta categoria.');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
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
