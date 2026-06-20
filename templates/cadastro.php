<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="form-title text-center mb-4">Cadastro de Cliente</h1>

                <form method="post" action="cadastro.php" novalidate>
                    <div class="mb-3">
                        <label for="nome" class="form-label label-dreamy" style="color: #fff !important;">Nome completo</label>
                        <input type="text" class="form-control dreamy-input" id="nome" name="nome"
                               value="<?= htmlspecialchars($dadosForm['nome'] ?? '') ?>" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label label-dreamy" style="color: #fff !important;">E-mail</label>
                        <input type="email" class="form-control dreamy-input" id="email" name="email"
                               value="<?= htmlspecialchars($dadosForm['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label label-dreamy" style="color: #fff !important;">Telefone (WhatsApp)</label>
                        <input type="tel" class="form-control dreamy-input" id="telefone" name="telefone"
                               placeholder="(44) 99999-9999"
                               value="<?= htmlspecialchars($dadosForm['telefone'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label label-dreamy" style="color: #fff !important;">Senha</label>
                        <input type="password" class="form-control dreamy-input" id="senha" name="senha"
                               minlength="6" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirmar_senha" class="form-label label-dreamy" style="color: #fff !important;">Confirmar senha</label>
                        <input type="password" class="form-control dreamy-input" id="confirmar_senha"
                               name="confirmar_senha" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-dreamy w-100 mb-3">Cadastrar</button>
                    <p class="text-center mb-0 small texto-creme" style="color: #fff !important;">
                        Já tem conta? <a href="login.php" class="link-dreamy">Faça login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
