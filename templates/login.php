<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="form-title text-center mb-4">Entrar</h1>

                <form method="post" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label label-dreamy" style="color: #fff !important;">E-mail</label>
                        <input type="email" class="form-control dreamy-input" id="email" name="email"
                               value="<?= htmlspecialchars($emailForm ?? '') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="senha" class="form-label label-dreamy" style="color: #fff !important;">Senha</label>
                        <input type="password" class="form-control dreamy-input" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-dreamy w-100 mb-3">Entrar</button>
                    <p class="text-center mb-0 small texto-creme" style="color: #fff !important;">
                        Não tem conta? <a href="cadastro.php" class="link-dreamy">Cadastre-se</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
