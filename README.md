# Dreamy Cookies

Site comercial em PHP para venda de cookies artesanais, desenvolvido para o projeto acadêmico de Engenharia de Software.

## Funcionalidades

- Cardápio com fotos, sabores, descrições e preços
- Filtro por tipo de cookie (Tradicional, Recheado, Especial)
- Busca por nome ou descrição
- Carrinho de compras com total do pedido
- Cadastro e login de clientes
- Página Nossa História
- Área administrativa de clientes cadastrados
- Finalização do pedido via WhatsApp: (44) 9 9746-4801
- Layout responsivo com Bootstrap e identidade visual da marca

## Requisitos atendidos (Rubrica)

| Disciplina | Requisito |
|------------|-----------|
| Modelagem e BD | DER, 4 tabelas, PK/FK, relação N:N |
| SO e Redes | Porta 8080, DNS local, listagem desabilitada, BD em VM separada |
| Web Moderno | PHP, templates, Bootstrap (Navbar, Cards, Modal, Alert), foreach/while/if |
| Tech Forge | Arrays estruturados, funções modulares, filtros, validações |

## Estrutura do projeto

```
DreamyCookies/
├── index.php              # Cardápio principal
├── carrinho.php           # Carrinho de compras
├── cadastro.php           # Cadastro de clientes
├── login.php              # Login
├── logout.php             # Logout
├── historia.php           # Nossa História
├── admin-clientes.php     # Lista de clientes (admin)
├── config/
│   ├── database.example.php
│   └── admin.example.php
├── includes/
├── templates/
├── assets/
└── database/
```

## Instalação rápida

### 1. Configuração local

```bash
copy config\database.example.php config\database.php
copy config\admin.example.php config\admin.php
```

Ajuste o IP da VM em `config/database.php`.

### 2. Banco de dados (VM)

1. Importe `database/dreamy_cookies_export.sql` no phpMyAdmin da VM
2. Execute `database/setup_usuario.sql` e `database/setup_vm_remoto.sql`

### 3. XAMPP

1. Copie a pasta para `C:\xampp\htdocs\`
2. Apache na porta **8080** — veja `docs/CONFIGURACAO_XAMPP.md`
3. DNS local — veja `docs/CONFIGURACAO_DNS.md`
4. Acesse: `http://localhost:8080/DreamyCookies/`

## Documentação

- [Checklist da rubrica](docs/CHECKLIST_RUBRICA.md)
- [Configuração XAMPP](docs/CONFIGURACAO_XAMPP.md)
- [Configuração DNS](docs/CONFIGURACAO_DNS.md)
- [Configuração VM e DBeaver](docs/CONFIGURACAO_VM_DBEAVER.md)
- [DER](database/DER.md)

## Repositório

https://github.com/elissandrabc-bit/DreamyCookies
