<?php require __DIR__ . '/partials/admin-login.php'; ?>

<?php if ($mostrarLista): ?>
    <section class="hero text-center mb-4">
        <h1 class="hero-title" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">CRUD — Clientes</h1>
        <p class="hero-subtitle">Inclusão, edição, listagem e exclusão</p>
    </section>

    <div class="admin-form-card mb-4">
        <h2 class="admin-form-titulo"><?= $clienteEdicao ? 'Editar cliente #' . (int) $clienteEdicao['id'] : 'Novo cliente' ?></h2>
        <form method="post" action="admin-clientes.php" class="row g-3">
            <input type="hidden" name="acao" value="<?= $clienteEdicao ? 'atualizar' : 'criar' ?>">
            <?php if ($clienteEdicao): ?>
                <input type="hidden" name="id" value="<?= (int) $clienteEdicao['id'] ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Nome</label>
                <input type="text" name="nome" class="form-control dreamy-input" required
                       value="<?= htmlspecialchars($clienteEdicao['nome'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">E-mail</label>
                <input type="email" name="email" class="form-control dreamy-input" required
                       value="<?= htmlspecialchars($clienteEdicao['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Telefone</label>
                <input type="text" name="telefone" class="form-control dreamy-input" required
                       value="<?= htmlspecialchars($clienteEdicao['telefone'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">
                    Senha<?= $clienteEdicao ? ' (deixe vazio para manter)' : '' ?>
                </label>
                <input type="password" name="senha" class="form-control dreamy-input"
                       <?= $clienteEdicao ? '' : 'required' ?>>
            </div>
            <?php if (!$clienteEdicao): ?>
            <div class="col-md-6">
                <label class="form-label label-dreamy" style="color:#fff!important;">Confirmar senha</label>
                <input type="password" name="confirmar_senha" class="form-control dreamy-input" required>
            </div>
            <?php endif; ?>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-dreamy">
                    <?= $clienteEdicao ? 'Salvar alterações' : 'Cadastrar cliente' ?>
                </button>
                <?php if ($clienteEdicao): ?>
                    <a href="admin-clientes.php" class="btn btn-outline-light">Cancelar edição</a>
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
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cadastro</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum cliente cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= $cliente['id'] ?></td>
                            <td><?= htmlspecialchars($cliente['nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['email']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                            <td><?= htmlspecialchars(formatarDataCadastro($cliente['data_cadastro'])) ?></td>
                            <td class="text-end admin-acoes">
                                <a href="admin-clientes.php?editar=<?= $cliente['id'] ?>" class="btn btn-sm btn-dreamy">Editar</a>
                                <form method="post" action="admin-clientes.php" class="d-inline"
                                      onsubmit="return confirm('Excluir o cliente <?= htmlspecialchars(addslashes($cliente['nome'])) ?>? Pedidos vinculados perderão o vínculo.');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
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
