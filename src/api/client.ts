import type {
  ApiErroResponse,
  DashboardResponse,
  PedidosResponse,
} from '../types/api.js';

const API_BASE = 'api';

async function parseJsonResponse<T>(response: Response): Promise<T> {
  const dados: unknown = await response.json();

  if (!response.ok) {
    const erro = dados as ApiErroResponse;
    const mensagem =
      typeof erro === 'object' &&
      erro !== null &&
      'erro' in erro &&
      typeof erro.erro === 'string'
        ? erro.erro
        : `Erro HTTP ${response.status}`;
    throw new Error(mensagem);
  }

  return dados as T;
}

export async function buscarDashboard(): Promise<DashboardResponse> {
  try {
    const response = await fetch(`${API_BASE}/dashboard.php`);

    const dados = await parseJsonResponse<DashboardResponse>(response);

    if (!dados.sucesso) {
      throw new Error('A API retornou sucesso=false.');
    }

    return dados;
  } catch (erro) {
    if (erro instanceof Error) {
      throw new Error(`Falha ao carregar dashboard: ${erro.message}`);
    }
    throw new Error('Falha ao carregar dashboard: erro desconhecido.');
  }
}

export async function buscarPedidos(pagina: number = 1): Promise<PedidosResponse> {
  try {
    const url = `${API_BASE}/pedidos.php?pagina=${pagina}&por_pagina=10`;
    const response = await fetch(url);
    const dados = await parseJsonResponse<PedidosResponse>(response);

    if (!dados.sucesso) {
      throw new Error('A API de pedidos retornou sucesso=false.');
    }

    return dados;
  } catch (erro) {
    if (erro instanceof Error) {
      throw new Error(`Falha ao carregar pedidos: ${erro.message}`);
    }
    throw new Error('Falha ao carregar pedidos: erro desconhecido.');
  }
}
