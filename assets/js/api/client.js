const API_BASE = 'api';
async function parseJsonResponse(response) {
    const dados = await response.json();
    if (!response.ok) {
        const erro = dados;
        const mensagem = typeof erro === 'object' &&
            erro !== null &&
            'erro' in erro &&
            typeof erro.erro === 'string'
            ? erro.erro
            : `Erro HTTP ${response.status}`;
        throw new Error(mensagem);
    }
    return dados;
}
export async function buscarDashboard() {
    try {
        const response = await fetch(`${API_BASE}/dashboard.php`);
        const dados = await parseJsonResponse(response);
        if (!dados.sucesso) {
            throw new Error('A API retornou sucesso=false.');
        }
        return dados;
    }
    catch (erro) {
        if (erro instanceof Error) {
            throw new Error(`Falha ao carregar dashboard: ${erro.message}`);
        }
        throw new Error('Falha ao carregar dashboard: erro desconhecido.');
    }
}
export async function buscarPedidos(pagina = 1) {
    try {
        const url = `${API_BASE}/pedidos.php?pagina=${pagina}&por_pagina=10`;
        const response = await fetch(url);
        const dados = await parseJsonResponse(response);
        if (!dados.sucesso) {
            throw new Error('A API de pedidos retornou sucesso=false.');
        }
        return dados;
    }
    catch (erro) {
        if (erro instanceof Error) {
            throw new Error(`Falha ao carregar pedidos: ${erro.message}`);
        }
        throw new Error('Falha ao carregar pedidos: erro desconhecido.');
    }
}
//# sourceMappingURL=client.js.map