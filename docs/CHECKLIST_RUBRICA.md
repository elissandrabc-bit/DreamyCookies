# Checklist - Atendimento à Rubrica

Use este documento na apresentação do projeto.

## Modelagem e Banco de Dados

- [x] DER documentado em `database/DER.md` (diagrama Mermaid)
- [x] 4 tabelas: `clientes`, `categorias`, `cookies`, `cookie_categoria`
- [x] Chaves primárias em todas as tabelas
- [x] Chaves estrangeiras em `cookie_categoria`
- [x] Relacionamento N:N entre cookies e categorias
- [x] Script SQL em `database/schema.sql`
- [x] Conexão configurável para VM com IP fixo

## Sistemas Operacionais e Redes

- [x] Listagem de diretórios desabilitada (`.htaccess`: `Options -Indexes`)
- [x] Aplicação na porta 8080 (`docs/CONFIGURACAO_XAMPP.md`)
- [x] DNS local configurável (`docs/CONFIGURACAO_DNS.md`)
- [x] Banco em VM separada (`docs/CONFIGURACAO_VM_DBEAVER.md`)

## Desenvolvimento Web Moderno

- [x] Layout PHP dinâmico e responsivo
- [x] Sistema de templates (`includes/template.php` + `templates/`)
- [x] Bootstrap 5 com componentes:
  - Navbar
  - Cards
  - Modal
  - Alert
  - Form (select, input, button)
- [x] Integração com banco de dados (PDO)
- [x] Estruturas de controle: `if`, `while`, `foreach`

## Tech Forge

- [x] Dados organizados em array único (`buscarCookies()`)
- [x] Funções modulares: `filtrarCookiesPorCategoria()`, `filtrarCookiesPorBusca()`, `calcularTotalPedido()`, `validarCadastroCliente()`
- [x] Parâmetros e retorno (sem variáveis globais de negócio)
- [x] Filtro por tipo e busca por sabor
- [x] Validações com `if/else` antes de processar

## Funcionalidades do Cliente

- [x] Cadastro de clientes
- [x] Login e logout
- [x] Filtro por tipos de cookies
- [x] Busca por sabor
- [x] WhatsApp clicável (botão flutuante + pedido por cookie)
- [x] Exibição de fotos, sabores e preços
- [x] Identidade visual Dreamy Cookies (marrom, terracota, tipografia)
