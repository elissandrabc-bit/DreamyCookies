# Configuração da VM e DBeaver

Guia para configurar o banco de dados MySQL/MariaDB em uma **máquina virtual separada**, conforme a rubrica.

## Arquitetura

| Componente | Onde roda | Porta |
|------------|-----------|-------|
| Site PHP (XAMPP) | Máquina host | 8080 |
| MySQL/MariaDB | Máquina virtual | 3306 |
| DBeaver | Máquina host (cliente) | - |

## 1. Configurar a VM

### Instalar MySQL/MariaDB (Linux - Ubuntu/Debian)

```bash
sudo apt update
sudo apt install mariadb-server -y
sudo systemctl enable mariadb
sudo systemctl start mariadb
sudo mysql_secure_installation
```

### IP fixo na VM

Configure IP estático na VM (exemplo):

- IP: `192.168.1.100`
- Máscara: `255.255.255.0`
- Gateway: `192.168.1.1`

Anote este IP — será usado em `config/database.php`.

### Permitir conexões remotas

Edite `/etc/mysql/mariadb.conf.d/50-server.cnf` (ou equivalente):

```ini
bind-address = 0.0.0.0
```

Reinicie o MySQL:

```bash
sudo systemctl restart mariadb
```

Configure firewall (se ativo):

```bash
sudo ufw allow 3306/tcp
```

## 2. Criar banco no DBeaver

1. Abra o **DBeaver**
2. **Nova Conexão** → **MySQL** (ou MariaDB)
3. Preencha:
   - Host: `192.168.1.100` (IP da VM)
   - Port: `3306`
   - Database: (deixe vazio inicialmente)
   - Username: `root` (ou seu usuário admin)
   - Password: sua senha
4. Teste a conexão e clique em **Finish**

### Executar scripts

1. Abra o arquivo `database/schema.sql` no DBeaver
2. Execute o script completo (Ctrl+Enter ou botão Execute)
3. Execute `database/setup_usuario.sql` para criar o usuário da aplicação

## 3. Configurar o site PHP

Edite `config/database.php` na máquina host:

```php
define('DB_HOST', '192.168.1.100'); // IP fixo da VM
define('DB_USER', 'dreamy_user');
define('DB_PASS', 'dreamy123');
```

## 4. Testar conexão

Na máquina host, teste se a porta 3306 da VM está acessível:

```powershell
Test-NetConnection -ComputerName 192.168.1.100 -Port 3306
```

Se `TcpTestSucceeded` for `True`, a conexão está OK.

## 5. Tabelas criadas

| Tabela | Descrição |
|--------|-----------|
| clientes | Cadastro de clientes |
| categorias | Tipos (Tradicional, Recheado, Especial) |
| cookies | Produtos do cardápio |
| cookie_categoria | Relação N:N |

Veja o DER completo em `database/DER.md`.

## Dicas para apresentação

- Mostre no DBeaver as 4 tabelas e o relacionamento N:N
- Demonstre o IP fixo da VM (`ip addr` ou `ipconfig`)
- Mostre que o site conecta remotamente ao banco
- Explique que app e BD estão em máquinas diferentes
