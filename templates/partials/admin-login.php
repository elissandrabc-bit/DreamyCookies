<?php if (!$mostrarLista): ?>
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card form-card">
                <div class="card-body p-4">
                    <h1 class="form-title text-center mb-4">Área do administrador</h1>
                    <p class="texto-creme text-center mb-4" style="color: #fff !important;">
                        Acesso restrito — gerenciamento do sistema.
                    </p>

                    <?php if ($mensagem_erro !== ''): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($mensagem_erro) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= htmlspecialchars($paginaAdmin ?? 'admin-clientes.php') ?>">
                        <input type="hidden" name="acao" value="entrar">
                        <div class="mb-4">
                            <label for="senha" class="form-label label-dreamy" style="color: #fff !important;">Senha</label>
                            <input type="password" class="form-control dreamy-input" id="senha" name="senha" required>
                        </div>
                        <button type="submit" class="btn btn-dreamy w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php require __DIR__ . '/admin-nav.php'; ?>
<?php endif; ?>
