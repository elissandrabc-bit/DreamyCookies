<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Dreamy Cookies') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Roboto+Slab:wght@400;600;700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=6">
</head>
<body>
    <!-- Bootstrap: Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark dreamy-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand dreamy-brand" href="index.php">
                <img src="assets/img/logo.png" alt="Dreamy Cookies" class="logo-nav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                    aria-controls="menuPrincipal" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Cardápio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="historia.php">Nossa História</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link carrinho-nav-link" href="carrinho.php">
                            Carrinho
                            <?php if (quantidadeItensCarrinho() > 0): ?>
                                <span class="badge bg-dreamy-accent carrinho-badge"><?= quantidadeItensCarrinho() ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (clienteLogado()): ?>
                        <li class="nav-item">
                            <span class="nav-link text-warning">Olá, <?= htmlspecialchars(nomeClienteLogado()) ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dreamy btn-sm" href="cadastro.php">Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="wave-top"></div>

    <main class="container py-4">
        <?php if (!empty($mensagem_sucesso)): ?>
            <!-- Bootstrap: Alert -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem_sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensagem_erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem_erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php require __DIR__ . '/' . $conteudo_template . '.php'; ?>
    </main>

    <footer class="dreamy-footer">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-md-7">
                    <p class="footer-info mb-1">
                        <span class="footer-icon">🕕</span> A partir das 18h30
                    </p>
                    <p class="footer-info mb-1">
                        <span class="footer-icon">📍</span> Goioerê - PR
                    </p>
                    <p class="footer-info mb-3">
                        <span class="footer-icon">📱</span>
                        <a href="<?= linkWhatsAppLoja() ?>" target="_blank" rel="noopener" class="footer-link">
                            Peça agora: (44) 9 9746-4801
                        </a>
                    </p>
                    <span class="badge badge-desde">DESDE 2025</span>
                </div>
                <div class="col-md-5 text-md-end">
                    <img src="assets/img/logo.png" alt="Logo Dreamy Cookies" class="footer-logo">
                </div>
            </div>
            <hr class="footer-divider">
            <p class="text-center mb-0 small footer-copy">&copy; <?= date('Y') ?> Dreamy Cookies. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap: Botão flutuante WhatsApp -->
    <a href="<?= linkWhatsAppLoja() ?>" class="whatsapp-float" target="_blank" rel="noopener"
       title="Pedir pelo WhatsApp" aria-label="Pedir pelo WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.068-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.062-.133.033-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.378-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
        </svg>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
