import { buscarDashboard, buscarPedidos } from './api/client.js';
import { calcularMetricasGlobais, filtrarCategoriasPorNome, mapearCategorias, mapearPedidos, mapearRankingCookies, obterProdutoDestaque, } from './services/processamento.js';
import { limparDashboard, renderizarErro, renderizarEstadoCarregando, renderizarIndicadores, renderizarTabelaCategorias, renderizarTabelaPedidos, renderizarTabelaRanking, } from './ui/render.js';
async function carregarDashboard() {
    renderizarEstadoCarregando();
    limparDashboard();
    try {
        const [dadosDashboard, dadosPedidos] = await Promise.all([
            buscarDashboard(),
            buscarPedidos(1),
        ]);
        const filtroInput = document.getElementById('filtro-categoria');
        const termoFiltro = filtroInput instanceof HTMLInputElement ? filtroInput.value : '';
        const categoriasFiltradas = filtrarCategoriasPorNome(dadosDashboard.vendas_por_categoria, termoFiltro);
        const metricas = calcularMetricasGlobais(dadosDashboard.ranking_cookies);
        const destaque = obterProdutoDestaque(dadosDashboard.ranking_cookies);
        renderizarIndicadores(dadosDashboard.indicadores, metricas, destaque);
        renderizarTabelaCategorias(mapearCategorias(categoriasFiltradas));
        renderizarTabelaRanking(mapearRankingCookies(dadosDashboard.ranking_cookies));
        renderizarTabelaPedidos(mapearPedidos(dadosPedidos.pedidos));
    }
    catch (erro) {
        const mensagem = erro instanceof Error
            ? erro.message
            : 'Erro inesperado ao carregar a dashboard.';
        renderizarErro(mensagem);
    }
}
function configurarEventos() {
    const btnAtualizar = document.getElementById('btn-atualizar');
    if (btnAtualizar !== null) {
        btnAtualizar.addEventListener('click', () => {
            void carregarDashboard();
        });
    }
    const filtroCategoria = document.getElementById('filtro-categoria');
    if (filtroCategoria instanceof HTMLInputElement) {
        filtroCategoria.addEventListener('input', () => {
            void carregarDashboard();
        });
    }
}
document.addEventListener('DOMContentLoaded', () => {
    configurarEventos();
    void carregarDashboard();
});
//# sourceMappingURL=dashboard.js.map