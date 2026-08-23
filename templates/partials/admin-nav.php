<nav class="admin-nav mb-4">
    <ul class="nav nav-pills flex-wrap justify-content-center gap-2">
        <li class="nav-item">
            <a class="nav-link admin-nav-link <?= ($paginaAdminAtiva ?? '') === 'clientes' ? 'active' : '' ?>"
               href="admin-clientes.php">Clientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link admin-nav-link <?= ($paginaAdminAtiva ?? '') === 'cookies' ? 'active' : '' ?>"
               href="admin-cookies.php">Cookies</a>
        </li>
        <li class="nav-item">
            <a class="nav-link admin-nav-link <?= ($paginaAdminAtiva ?? '') === 'categorias' ? 'active' : '' ?>"
               href="admin-categorias.php">Categorias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link admin-nav-link" href="dashboard.php">Dashboard</a>
        </li>
    </ul>
    <form method="post" class="text-end mt-2">
        <input type="hidden" name="acao" value="sair">
        <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
    </form>
</nav>

<?php if (!empty($mensagem_sucesso)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensagem_sucesso) ?></div>
<?php endif; ?>

<?php if (!empty($mensagem_erro) && ($mostrarLista ?? true)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($mensagem_erro) ?></div>
<?php endif; ?>
