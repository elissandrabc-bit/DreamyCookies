-- Execute na VM (como root ou admin MySQL) antes de usar o site
-- Ajuste a senha e o IP permitido conforme sua rede

CREATE USER IF NOT EXISTS 'dreamy_user'@'%' IDENTIFIED BY 'dreamy123';
GRANT SELECT, INSERT, UPDATE ON dreamy_cookies.* TO 'dreamy_user'@'%';
FLUSH PRIVILEGES;

-- Para restringir apenas ao IP do servidor de aplicação (recomendado):
-- CREATE USER IF NOT EXISTS 'dreamy_user'@'192.168.1.50' IDENTIFIED BY 'dreamy123';
-- GRANT SELECT, INSERT, UPDATE ON dreamy_cookies.* TO 'dreamy_user'@'192.168.1.50';
