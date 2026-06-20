# Configuração do XAMPP - Porta 8080

Este guia configura o Apache do XAMPP para rodar na **porta 8080**, conforme exigido pela rubrica.

## Passo 1: Alterar porta do Apache

1. Abra o **XAMPP Control Panel**
2. Clique em **Config** ao lado de Apache → **httpd.conf**
3. Localize a linha:
   ```
   Listen 80
   ```
   Altere para:
   ```
   Listen 8080
   ```
4. Localize:
   ```
   ServerName localhost:80
   ```
   Altere para:
   ```
   ServerName localhost:8080
   ```

## Passo 2: Copiar o projeto

Copie a pasta `DreamyCookies` para:

```
C:\xampp\htdocs\DreamyCookies
```

## Passo 3: Virtual Host (opcional, recomendado)

Edite `C:\xampp\apache\conf\extra\httpd-vhosts.conf` e adicione:

```apache
<VirtualHost *:8080>
    DocumentRoot "C:/xampp/htdocs/DreamyCookies"
    ServerName dreamycookies.local
    ServerAlias www.dreamycookies.local

    <Directory "C:/xampp/htdocs/DreamyCookies">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/dreamycookies-error.log"
    CustomLog "logs/dreamycookies-access.log" common
</VirtualHost>
```

No `httpd.conf`, certifique-se de que esta linha não está comentada:

```apache
Include conf/extra/httpd-vhosts.conf
```

## Passo 4: Reiniciar Apache

No XAMPP Control Panel, pare e inicie o Apache novamente.

## Passo 5: Testar

Acesse no navegador:

- `http://localhost:8080/DreamyCookies/` (sem DNS)
- `http://dreamycookies.local:8080` (com DNS configurado)

## Segurança - Listagem de diretórios

O arquivo `.htaccess` na raiz do projeto já contém:

```apache
Options -Indexes
```

Isso impede que visitantes vejam a lista de arquivos de uma pasta.

## Arquitetura de rede

```
[Máquina Host - XAMPP :8080]  ----->  [VM - MySQL :3306]
     Aplicação PHP                         Banco de Dados
```

A aplicação e o banco devem estar em **máquinas separadas**, conforme a rubrica.
