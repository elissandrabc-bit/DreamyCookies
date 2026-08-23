# Checklist — Nova rubrica (novarubrica.pdf)

Referência: `docs/novarubrica.pdf`

Legenda: ✅ atende | ⚠️ parcial | ❌ falta

---

## Banco de Dados Avançado (até 4 pontos)

| Requisito | Pts | Status | O que fazer |
|-----------|-----|--------|-------------|
| CTEs e Views analíticas no MariaDB | 1,0 | ✅ | `database/avancado.sql` — `vw_vendas_por_categoria`, `vw_dashboard_resumo` |
| Stored Procedures (busca, filtros, paginação dashboard) | 1,0 | ✅ | `sp_dashboard_indicadores`, `sp_listar_pedidos` |
| Triggers BEFORE UPDATE (valores positivos) | 1,0 | ✅ | `trg_cookies_preco_positivo_update`, `trg_pedido_itens_validar_update` |
| Função reutilizável no banco | 0,5 | ✅ | `fn_subtotal_item`, `fn_total_pedido` |
| View consolidando várias tabelas | 0,5 | ✅ | `vw_pedidos_detalhados`, `vw_cookies_completo` |

---

## Desenvolvimento Web Avançado (até 3,7 pontos)

| Requisito | Pts | Status | O que fazer |
|-----------|-----|--------|-------------|
| Interface amigável e usável | 0,30 | ✅ | Site atual já tem cardápio, filtros, carrinho |
| Bootstrap (≥ 3 componentes) | 0,30 | ✅ | Navbar, Cards, Modal, Alert, Forms |
| Template PHP reutilizável | 0,30 | ✅ | `includes/template.php` + `templates/` |
| Estrutura de pastas organizada | 0,30 | ✅ | `config/`, `includes/`, `templates/`, `assets/`, `database/` |
| **3 CRUDs completos** (create, read, update, delete) | 1,30 | ✅ | `admin-clientes.php`, `admin-cookies.php`, `admin-categorias.php` |
| Regras de exclusão com mensagem clara | 0,50 | ✅ | Confirmação JS + flash PHP + bloqueio cookie em pedidos |

**CRUDs sugeridos no Dreamy Cookies:**
1. **Cookies** (admin)
2. **Categorias** (admin)
3. **Clientes** (admin) — completar edit/delete

---

## Lógica Avançada — TypeScript (até 3 pontos)

| Requisito | Pts | Status | O que fazer |
|-----------|-----|--------|-------------|
| Interfaces/types mapeando JSON do PHP (sem `any`) | 0,50 | ✅ | `src/types/api.ts` |
| `.reduce()` — métricas financeiras | 0,50 | ✅ | `calcularMetricasGlobais()` |
| `.filter()` — segmentação por categoria/período | 0,50 | ✅ | `filtrarCategoriasPorNome()` |
| Ranking / produto mais vendido | 0,50 | ✅ | `obterProdutoDestaque()` |
| `.map()` — formatação R$, arrays para gráficos | 0,50 | ✅ | `mapearCategorias()`, `mapearRankingCookies()` |
| Edge cases (banco vazio, NaN) | 0,50 | ✅ | Mensagens em `src/ui/render.ts` |

## Tech Forge (até 3 pontos)

| Requisito | Pts | Status | O que fazer |
|-----------|-----|--------|-------------|
| `fetch` + `async/await` + `try/catch` | 0,50 | ✅ | `src/api/client.ts` |
| XAMPP + compilação TS (.ts → .js) | 0,50 | ✅ | `npm run build` → `assets/js/` |
| DOM seguro (sem `!` abusivo) | 0,50 | ✅ | `src/ui/render.ts` |
| Código modular (SRP) | 0,50 | ✅ | `api/`, `services/`, `ui/` |
| Layout dashboard coerente | 0,50 | ✅ | `dashboard.php` + CSS |
| Apresentação oral | 0,50 | — | Preparar roteiro |

---

## Resumo

| Bloco | Nota máxima | Estimativa atual |
|-------|-------------|------------------|
| Banco Avançado | 4,0 | ~0 |
| Web Avançado | 3,7 | ~1,2 |
| Lógica TypeScript | 3,0 | ~0 |
| Tech Forge | 3,0 | ~0 |
| **Total projeto** | **~13,7** | **~1,2** |

---

## Plano sugerido (continuidade no Dreamy Cookies)

### Fase 1 — Banco ✅
- Tabelas `pedidos` / `pedido_itens` — `database/avancado.sql`
- Views, CTEs, SPs, triggers, funções — mesmo arquivo
- Importar no phpMyAdmin após ligar MySQL no XAMPP

### Fase 2 — API PHP ✅
- `api/dashboard.php` — indicadores + vendas + ranking (JSON)
- `api/pedidos.php` — listagem paginada (`CALL sp_listar_pedidos`)
- `api/cookies.php` — cardápio em JSON
- `api/index.php` — documentação dos endpoints
- `includes/api.php` + `includes/api_helpers.php`

### Fase 3 — Admin CRUD ✅
- Clientes, cookies e categorias — create, read, update, delete
- Senha admin em `config/admin.php`

### Fase 4 — Dashboard TypeScript ✅
- `src/` — types, api, services, ui
- `dashboard.php` + `templates/dashboard.php`
- Compilar: `npm run build`

### Fase 5 — Entrega
- Atualizar README com links GitHub + site
- PDF rubrica em `docs/novarubrica.pdf`
