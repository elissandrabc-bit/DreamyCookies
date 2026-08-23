import type {
  CategoriaFormatada,
  CookieRankingFormatado,
  IndicadoresDashboard,
  MetricasGlobais,
  PedidoFormatado,
} from '../types/api.js';
import { formatarMoeda } from '../services/processamento.js';

function definirTexto(id: string, texto: string): void {
  const el = document.getElementById(id);
  if (el !== null) {
    el.textContent = texto;
  }
}

function limparContainer(id: string): void {
  const el = document.getElementById(id);
  if (el !== null) {
    el.innerHTML = '';
  }
}

export function renderizarEstadoCarregando(): void {
  definirTexto('dashboard-status', 'Carregando indicadores...');
}

export function renderizarErro(mensagem: string): void {
  definirTexto('dashboard-status', mensagem);

  const alerta = document.getElementById('dashboard-alerta');
  if (alerta !== null) {
    alerta.className = 'alert alert-danger';
    alerta.textContent = mensagem;
    alerta.classList.remove('d-none');
  }
}

export function renderizarIndicadores(
  indicadores: IndicadoresDashboard,
  metricas: MetricasGlobais,
  produtoDestaque: { nome: string; quantidade: number } | null
): void {
  definirTexto('dashboard-status', 'Dados atualizados.');
  definirTexto('kpi-pedidos', String(indicadores.total_pedidos));
  definirTexto('kpi-faturamento', formatarMoeda(indicadores.faturamento_total));
  definirTexto('kpi-unidades', String(indicadores.unidades_vendidas));
  definirTexto('kpi-ticket', formatarMoeda(metricas.ticketMedio));

  const nomeDestaque = produtoDestaque?.nome ?? indicadores.produto_destaque;
  const qtdDestaque =
    produtoDestaque?.quantidade ?? indicadores.qtd_produto_destaque;

  if (nomeDestaque === 'Nenhum' || qtdDestaque === 0) {
    definirTexto('kpi-destaque', 'Nenhum dado registrado');
  } else {
    definirTexto('kpi-destaque', `${nomeDestaque} (${qtdDestaque} un.)`);
  }

  const alerta = document.getElementById('dashboard-alerta');
  if (alerta !== null) {
    alerta.classList.add('d-none');
  }
}

export function renderizarTabelaCategorias(categorias: CategoriaFormatada[]): void {
  const tbody = document.getElementById('tbody-categorias');
  if (tbody === null) {
    return;
  }

  tbody.innerHTML = '';

  if (categorias.length === 0) {
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 3;
    td.className = 'text-center dashboard-vazio';
    td.textContent = 'Nenhum dado registrado para as categorias filtradas.';
    tr.appendChild(td);
    tbody.appendChild(tr);
    return;
  }

  categorias.forEach((cat) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${cat.nome}</td>
      <td class="text-end">${cat.quantidade}</td>
      <td class="text-end">${cat.faturamentoTexto}</td>
    `;
    tbody.appendChild(tr);
  });
}

export function renderizarTabelaRanking(ranking: CookieRankingFormatado[]): void {
  const tbody = document.getElementById('tbody-ranking');
  if (tbody === null) {
    return;
  }

  tbody.innerHTML = '';

  if (ranking.length === 0) {
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 3;
    td.className = 'text-center dashboard-vazio';
    td.textContent = 'Nenhum dado registrado no ranking de cookies.';
    tr.appendChild(td);
    tbody.appendChild(tr);
    return;
  }

  ranking.forEach((item, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><span class="badge bg-dreamy-accent me-1">${index + 1}º</span> ${item.nome}</td>
      <td class="text-end">${item.quantidade}</td>
      <td class="text-end">${item.faturamentoTexto}</td>
    `;
    tbody.appendChild(tr);
  });
}

export function renderizarTabelaPedidos(pedidos: PedidoFormatado[]): void {
  const tbody = document.getElementById('tbody-pedidos');
  if (tbody === null) {
    return;
  }

  tbody.innerHTML = '';

  if (pedidos.length === 0) {
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 5;
    td.className = 'text-center dashboard-vazio';
    td.textContent = 'Nenhum pedido registrado.';
    tr.appendChild(td);
    tbody.appendChild(tr);
    return;
  }

  pedidos.forEach((pedido) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>#${pedido.id}</td>
      <td>${pedido.dataTexto}</td>
      <td>${pedido.cliente}</td>
      <td><span class="badge dashboard-badge-status">${pedido.status}</span></td>
      <td class="text-end">${pedido.totalTexto}</td>
    `;
    tbody.appendChild(tr);
  });
}

export function limparDashboard(): void {
  limparContainer('tbody-categorias');
  limparContainer('tbody-ranking');
  limparContainer('tbody-pedidos');
}
