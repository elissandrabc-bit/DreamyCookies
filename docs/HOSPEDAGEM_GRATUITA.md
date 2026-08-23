# Hospedagem gratuita — Dreamy Cookies

Guia rápido para publicar o site na internet (professor acessar de qualquer lugar).

**Recomendado:** [InfinityFree](https://www.infinityfree.com/) — PHP + MySQL grátis, sem cartão.

> A VM local (`192.168.56.101`) **não funciona** na internet. Na hospedagem, o banco fica no **MySQL do provedor** (mesmo servidor ou hostname deles).

---

## Passo 1 — Criar conta (15 min)

1. Acesse https://www.infinityfree.com/
2. **Sign Up** → crie conta com e-mail
3. Confirme o e-mail
4. No painel https://dash.infinityfree.com/ clique em **Create Account**
5. Escolha um subdomínio, ex.: `dreamycookies.infinityfreeapp.com`
6. Anote a URL pública — é essa que você envia ao professor

---

## Passo 2 — Criar banco MySQL

No painel da hospedagem:

1. **MySQL Databases** → **Create Database**
2. Anote estes 4 dados:
   - **MySQL Host** (ex.: `sql123.infinityfree.com`)
   - **Database Name** (ex.: `if0_12345678_dreamy`)
   - **Username**
   - **Password**
3. Abra **phpMyAdmin** pelo painel
4. Selecione seu banco → aba **Importar**
5. Envie o arquivo `database/dreamy_cookies_export.sql` do projeto
6. Clique em **Executar**

---

## Passo 3 — Enviar os arquivos do site

### Opção A — Gerenciador de arquivos (mais fácil)

1. Painel → **Online File Manager**
2. Entre na pasta **`htdocs`** (raiz do site)
3. **Apague** o `index.php` ou `index.html` que vier por padrão
4. Envie **todos** os arquivos do projeto DreamyCookies para **dentro de `htdocs`**
   - `index.php`, `404.php`, `cadastro.php`, pastas `assets/`, `config/`, etc.
   - **Não** envie a pasta `.git`

### Opção B — FTP (FileZilla)

- Host: informado no painel (ex. `ftpupload.net`)
- Usuário e senha: da conta FTP no painel
- Pasta remota: `htdocs`

---

## Passo 4 — Configurar conexão com o banco

No **File Manager**, edite `config/database.php` (crie a partir do example se não existir):

```php
define('DB_HOST', 'sql123.infinityfree.com');  // MySQL Host do painel
define('DB_PORT', '3306');
define('DB_NAME', 'if0_12345678_dreamy');      // nome do banco
define('DB_USER', 'if0_12345678');             // usuário
define('DB_PASS', 'SUA_SENHA_AQUI');           // senha
define('DB_CHARSET', 'utf8mb4');
```

Copie de `config/database.hosting.example.php` e preencha com os dados reais.

Crie também `config/admin.php` (copie de `config/admin.example.php`) com sua senha de admin.

---

## Passo 5 — Ajustar `.htaccess` para hospedagem

Na hospedagem os arquivos ficam na **raiz** de `htdocs`, não em `/DreamyCookies/`.

No `.htaccess`, altere a linha 404 para:

```apache
ErrorDocument 404 /404.php
```

(remova `/DreamyCookies/` do caminho)

---

## Passo 6 — Testar

Abra no navegador:

```
https://SEU-SUBDOMINIO.infinityfreeapp.com/
```

Checklist:

- [ ] Cardápio com cookies
- [ ] Cadastro funciona
- [ ] Carrinho
- [ ] Página 404: `https://seu-site/xyz-teste`
- [ ] Admin: `https://seu-site/admin-clientes.php`

Envie ao professor: **a URL pública** + **GitHub** + **ZIP** (se pedir).

---

## O que falar na apresentação

> *“Para acesso público, publiquei em hospedagem gratuita com PHP e MySQL. A versão acadêmica com banco em VM separada (IP fixo) está documentada no README e no GitHub; na nuvem o MySQL fica no provedor porque a VM local não é acessível pela internet.”*

---

## Problemas comuns

| Problema | Solução |
|----------|---------|
| Página em branco | Erro PHP — veja logs no painel; confira `database.php` |
| Erro de banco | Host/usuário/senha errados no painel MySQL |
| Imagens não aparecem | Pasta `assets/img/cookies/` enviada? |
| 404 errado | `.htaccess` com `/404.php` (sem DreamyCookies) |
| Site lento / offline | InfinityFree pode hibernar — acesse e aguarde ~30s |

---

## Alternativas gratuitas

| Serviço | URL |
|---------|-----|
| InfinityFree | https://www.infinityfree.com/ |
| AwardSpace | https://www.awardspace.com/ |
| Freehostia | https://www.freehostia.com/ |

Todos seguem a mesma lógica: PHP + MySQL + upload + importar SQL.
