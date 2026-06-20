# Dreamy Cookies

Site comercial em PHP para venda de cookies artesanais, desenvolvido para o projeto acadêmico de Engenharia de Software.

## Funcionalidades

- Cardápio com fotos, sabores, descrições e preços
- Filtro por tipo de cookie (Tradicional, Recheado, Especial)
- Busca por nome ou descrição
- Cadastro e login de clientes
- Botão WhatsApp para pedidos: (44) 9 9746-4801
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
├── index.php           # Cardápio principal
├── cadastro.php        # Cadastro de clientes
├── login.php           # Login
├── logout.php          # Logout
├── .htaccess           # Segurança (sem listagem)
├── config/
│   └── database.php    # Conexão com BD na VM
├── includes/
│   ├── functions.php   # Lógica de negócio
│   └── template.php    # Sistema de templates
├── templates/          # Layout e páginas
├── assets/
│   ├── css/style.css
│   └── img/cookies/    # Fotos dos cookies (você adiciona)
└── database/
    ├── schema.sql      # Script do banco
    ├── DER.md          # Diagrama ER
    └── setup_usuario.sql
```

## Instalação rápida

### 1. Banco de dados (VM + DBeaver)

1. Instale MySQL/MariaDB na VM com IP fixo
2. No DBeaver, conecte à VM e execute `database/schema.sql`
3. Execute `database/setup_usuario.sql` para criar o usuário
4. Anote o IP da VM

### 2. Configurar conexão

Edite `config/database.php` e altere `DB_HOST` para o IP da VM:

```php
define('DB_HOST', '192.168.1.100'); // IP da sua VM
```

### 3. XAMPP (servidor de aplicação)

1. Copie a pasta `DreamyCookies` para `C:\xampp\htdocs\`
2. Configure a porta 8080 (veja `docs/CONFIGURACAO_XAMPP.md`)
3. Configure o DNS local (veja `docs/CONFIGURACAO_DNS.md`)
4. Acesse: `http://dreamycookies.local:8080`

### 4. Fotos dos cookies

Coloque suas fotos em `assets/img/cookies/` com estes nomes:

- tradicional-preto.jpg
- tradicional-branco.jpg
- brookie.jpg
- nutella.jpg
- ovomaltine.jpg
- pistache.jpg
- kinder.jpg
- negresco.jpg
- red-velvet.jpg
- banoffe.jpg
- pringles-nutella.jpg

Se a foto não existir, um placeholder será exibido automaticamente.

## Contato WhatsApp

Link: https://wa.me/5544997464801

## Documentação adicional

- [Configuração XAMPP (porta 8080)](docs/CONFIGURACAO_XAMPP.md)
- [Configuração DNS local](docs/CONFIGURACAO_DNS.md)
- [Configuração VM e DBeaver](docs/CONFIGURACAO_VM_DBEAVER.md)
- [DER - Diagrama Entidade-Relacionamento](database/DER.md)
