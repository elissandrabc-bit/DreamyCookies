/** Resposta de api/dashboard.php */
export interface DashboardResponse {
  sucesso: boolean;
  indicadores: IndicadoresDashboard;
  vendas_por_categoria: VendaCategoria[];
  ranking_cookies: RankingCookie[];
}

export interface IndicadoresDashboard {
  total_pedidos: number;
  faturamento_total: number;
  unidades_vendidas: number;
  produto_destaque: string;
  qtd_produto_destaque: number;
}

export interface VendaCategoria {
  categoria_id: number;
  categoria_nome: string;
  quantidade_vendida: number;
  faturamento_categoria: number;
}

export interface RankingCookie {
  cookie_id: number;
  cookie_nome: string;
  quantidade_vendida: number;
  faturamento: number;
}

/** Resposta de api/pedidos.php */
export interface PedidosResponse {
  sucesso: boolean;
  pedidos: PedidoResumo[];
  paginacao: PaginacaoPedidos;
}

export interface PedidoResumo {
  id: number;
  data_pedido: string;
  status: string;
  total: number;
  cliente_nome: string;
  cliente_email: string | null;
  qtd_itens: number;
}

export interface PaginacaoPedidos {
  pagina: number;
  por_pagina: number;
  total_registros: number;
  total_paginas: number;
}

export interface ApiErroResponse {
  sucesso: false;
  erro: string;
}

/** Métricas calculadas no front (reduce / filter / map) */
export interface MetricasGlobais {
  faturamentoTotal: number;
  unidadesVendidas: number;
  ticketMedio: number;
}

export interface CategoriaFormatada {
  id: number;
  nome: string;
  quantidade: number;
  faturamentoTexto: string;
  faturamento: number;
}

export interface CookieRankingFormatado {
  id: number;
  nome: string;
  quantidade: number;
  faturamentoTexto: string;
  faturamento: number;
}

export interface PedidoFormatado {
  id: number;
  dataTexto: string;
  status: string;
  cliente: string;
  totalTexto: string;
  qtdItens: number;
}
