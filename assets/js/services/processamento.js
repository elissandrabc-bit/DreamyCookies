export function formatarMoeda(valor) {
    if (!Number.isFinite(valor)) {
        return 'R$ 0,00';
    }
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
}
export function formatarData(dataIso) {
    const data = new Date(dataIso.replace(' ', 'T'));
    if (Number.isNaN(data.getTime())) {
        return dataIso;
    }
    return data.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
/** Reduce: faturamento e unidades a partir do ranking bruto */
export function calcularMetricasGlobais(ranking) {
    if (ranking.length === 0) {
        return {
            faturamentoTotal: 0,
            unidadesVendidas: 0,
            ticketMedio: 0,
        };
    }
    const acumulado = ranking.reduce((acc, item) => {
        acc.faturamentoTotal += item.faturamento;
        acc.unidadesVendidas += item.quantidade_vendida;
        return acc;
    }, { faturamentoTotal: 0, unidadesVendidas: 0 });
    const ticketMedio = acumulado.unidadesVendidas > 0
        ? acumulado.faturamentoTotal / acumulado.unidadesVendidas
        : 0;
    return {
        faturamentoTotal: acumulado.faturamentoTotal,
        unidadesVendidas: acumulado.unidadesVendidas,
        ticketMedio,
    };
}
/** Filter: segmentação por nome de categoria */
export function filtrarCategoriasPorNome(categorias, termo) {
    const busca = termo.trim().toLowerCase();
    if (busca === '') {
        return categorias;
    }
    return categorias.filter((cat) => cat.categoria_nome.toLowerCase().includes(busca));
}
/** Map: categorias prontas para a tabela */
export function mapearCategorias(categorias) {
    return categorias.map((cat) => ({
        id: cat.categoria_id,
        nome: cat.categoria_nome,
        quantidade: cat.quantidade_vendida,
        faturamento: cat.faturamento_categoria,
        faturamentoTexto: formatarMoeda(cat.faturamento_categoria),
    }));
}
/** Map: ranking formatado para exibição */
export function mapearRankingCookies(ranking) {
    return ranking.map((item) => ({
        id: item.cookie_id,
        nome: item.cookie_nome,
        quantidade: item.quantidade_vendida,
        faturamento: item.faturamento,
        faturamentoTexto: formatarMoeda(item.faturamento),
    }));
}
/** Map: pedidos formatados */
export function mapearPedidos(pedidos) {
    return pedidos.map((pedido) => ({
        id: pedido.id,
        dataTexto: formatarData(pedido.data_pedido),
        status: pedido.status,
        cliente: pedido.cliente_nome,
        totalTexto: formatarMoeda(pedido.total),
        qtdItens: pedido.qtd_itens,
    }));
}
/** Ranking dinâmico: cookie com maior quantidade vendida */
export function obterProdutoDestaque(ranking) {
    if (ranking.length === 0) {
        return null;
    }
    const contagem = ranking.reduce((acc, item) => {
        const atual = acc[item.cookie_id];
        const qtd = (atual?.qtd ?? 0) + item.quantidade_vendida;
        acc[item.cookie_id] = {
            nome: item.cookie_nome,
            qtd,
        };
        return acc;
    }, {});
    const valores = Object.values(contagem);
    if (valores.length === 0) {
        return null;
    }
    const destaque = valores.reduce((maior, atual) => atual.qtd > maior.qtd ? atual : maior);
    return {
        nome: destaque.nome,
        quantidade: destaque.qtd,
    };
}
//# sourceMappingURL=processamento.js.map