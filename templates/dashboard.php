<?php require __DIR__ . '/partials/admin-login.php'; ?>

<?php if ($mostrarLista): ?>
<section class="dashboard-hero text-center mb-4">
    <h1 class="hero-title">Dashboard</h1>
    <p class="hero-subtitle">Indicadores de vendas — Banco → PHP → JSON → TypeScript → DOM</p>
    <p id="dashboard-status" class="dashboard-status small">Aguardando...</p>
</section>

<div id="dashboard-alerta" class="alert alert-danger d-none" role="alert"></div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg">
        <div class="dashboard-kpi">
            <p class="dashboard-kpi-label">Pedidos</p>
            <p id="kpi-pedidos" class="dashboard-kpi-valor">—</p>
        </div>
    </div>
    <div class="col-md-4 col-lg">
        <div class="dashboard-kpi">
            <p class="dashboard-kpi-label">Faturamento</p>
            <p id="kpi-faturamento" class="dashboard-kpi-valor">—</p>
        </div>
    </div>
    <div class="col-md-4 col-lg">
        <div class="dashboard-kpi">
            <p class="dashboard-kpi-label">Unidades vendidas</p>
            <p id="kpi-unidades" class="dashboard-kpi-valor">—</p>
        </div>
    </div>
    <div class="col-md-6 col-lg">
        <div class="dashboard-kpi">
            <p class="dashboard-kpi-label">Ticket médio (reduce)</p>
            <p id="kpi-ticket" class="dashboard-kpi-valor">—</p>
        </div>
    </div>
    <div class="col-md-6 col-lg">
        <div class="dashboard-kpi">
            <p class="dashboard-kpi-label">Produto destaque</p>
            <p id="kpi-destaque" class="dashboard-kpi-valor dashboard-kpi-valor-sm">—</p>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 align-items-center mb-4">
    <label for="filtro-categoria" class="form-label label-dreamy mb-0" style="color:#fff!important;">
        Filtrar categorias
    </label>
    <input type="text" id="filtro-categoria" class="form-control dreamy-input dashboard-filtro"
           placeholder="Ex.: Recheado">
    <button type="button" id="btn-atualizar" class="btn btn-dreamy">Atualizar dados</button>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="dashboard-panel">
            <h2 class="dashboard-panel-titulo">Vendas por categoria</h2>
            <div class="table-responsive">
                <table class="table table-hover dashboard-tabela mb-0">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th class="text-end">Qtd</th>
                            <th class="text-end">Faturamento</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-categorias"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="dashboard-panel">
            <h2 class="dashboard-panel-titulo">Ranking de cookies</h2>
            <div class="table-responsive">
                <table class="table table-hover dashboard-tabela mb-0">
                    <thead>
                        <tr>
                            <th>Cookie</th>
                            <th class="text-end">Qtd</th>
                            <th class="text-end">Faturamento</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-ranking"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="dashboard-panel">
            <h2 class="dashboard-panel-titulo">Pedidos recentes</h2>
            <div class="table-responsive">
                <table class="table table-hover dashboard-tabela mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-pedidos"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="module" src="assets/js/dashboard.js?v=1"></script>
<?php endif; ?>
