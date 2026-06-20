-- Execute na VM (phpMyAdmin → aba SQL)
-- Libera o site no PC principal a conectar ao MySQL da VM

CREATE USER IF NOT EXISTS 'dreamy_user'@'%' IDENTIFIED BY 'dreamy123';
GRANT SELECT, INSERT, UPDATE ON dreamy_cookies.* TO 'dreamy_user'@'%';
FLUSH PRIVILEGES;

-- Conferir (deve listar dreamy_user com Host = %)
SELECT user, host FROM mysql.user WHERE user = 'dreamy_user';
