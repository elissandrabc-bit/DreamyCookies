<section class="hero text-center mb-5">
    <h1 class="hero-title"><?= htmlspecialchars($historia['titulo']) ?></h1>
    <p class="hero-subtitle"><?= htmlspecialchars($historia['subtitulo']) ?></p>
</section>

<section class="historia-card">
    <div class="row g-4 align-items-start">
        <div class="col-md-4 col-lg-3 text-center">
            <div class="historia-foto-wrap">
                <img src="<?= htmlspecialchars(urlImagemCriadora($historia['criadora_foto'])) ?>"
                     alt="<?= htmlspecialchars($historia['criadora_nome']) ?>"
                     class="historia-foto">
            </div>
            <p class="historia-criadora-nome mt-3 mb-0">
                <?= htmlspecialchars($historia['criadora_nome']) ?>
            </p>
            <p class="historia-criadora-cargo">Fundadora</p>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="historia-texto">
                <?php foreach ($historia['paragrafos'] as $paragrafo): ?>
                    <p><?= nl2br(htmlspecialchars($paragrafo)) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="index.php" class="btn btn-dreamy">Ver cardápio</a>
                <a href="<?= linkWhatsAppLoja() ?>" class="btn btn-outline-light" target="_blank" rel="noopener">
                    Fazer um pedido
                </a>
            </div>
        </div>
    </div>
</section>
