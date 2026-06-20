# Configuração de DNS Local

A rubrica exige um **DNS local** para acessar a aplicação por nome de domínio.

## Windows - Arquivo hosts

1. Abra o Bloco de Notas **como Administrador**
2. Abra o arquivo:
   ```
   C:\Windows\System32\drivers\etc\hosts
   ```
3. Adicione no final:
   ```
   127.0.0.1    dreamycookies.local
   127.0.0.1    www.dreamycookies.local
   ```
4. Salve o arquivo

## Testar

Com o Apache rodando na porta 8080:

```
http://dreamycookies.local:8080
```

## Rede com VM (opcional)

Se o site rodar em outra máquina na rede, use o IP dela:

```
192.168.1.50    dreamycookies.local
```

Substitua `192.168.1.50` pelo IP real do servidor de aplicação.

## Linux (VM ou outro PC)

Edite `/etc/hosts`:

```
127.0.0.1    dreamycookies.local
```

## Verificação

No terminal:

```bash
ping dreamycookies.local
```

Deve responder com o IP configurado (127.0.0.1 ou IP da rede).
