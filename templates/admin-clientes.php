<?php if (!$mostrarLista): ?>
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card form-card">
                <div class="card-body p-4">
                    <h1 class="form-title text-center mb-4">Área do administrador</h1>
                    <p class="texto-creme text-center mb-4" style="color: #fff !important;">
                        Acesso restrito — lista de clientes cadastrados.
                    </p>

                    <form method="post" action="admin-clientes.php">
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
    <section class="hero text-center mb-4">
        <h1 class="hero-title" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Clientes cadastrados</h1>
        <p class="hero-subtitle"><?= count($clientes) ?> cliente(s) no banco de dados</p>
    </section>

    <div class="d-flex justify-content-end mb-3">
        <form method="post" action="admin-clientes.php">
            <input type="hidden" name="acao" value="sair">
            <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
        </form>
    </div>

    <?php if (empty($clientes)): ?>
        <div class="alert alert-warning text-center">
            Nenhum cliente cadastrado ainda.
        </div>
    <?php else: ?>
        <div class="table-responsive admin-tabela-wrap">
            <table class="table table-striped table-hover admin-tabela mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cadastro em</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= $cliente['id'] ?></td>
                            <td><?= htmlspecialchars($cliente['nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['email']) ?></td>
                            <td>
                                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $cliente['telefone']) ?>"
                                   class="admin-link-wa" target="_blank" rel="noopener">
                                    <?= htmlspecialchars($cliente['telefone']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars(formatarDataCadastro($cliente['data_cadastro'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>
