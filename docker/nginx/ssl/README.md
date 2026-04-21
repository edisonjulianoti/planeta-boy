# Certificados SSL para Nginx

Este diretório deve conter os certificados SSL para HTTPS em produção.

## Certificados Auto-assinados (Desenvolvimento)

Para desenvolvimento local, você pode gerar certificados auto-assinados:

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /home/ubuntu/planeta-boy/docker/nginx/ssl/key.pem \
  -out /home/ubuntu/planeta-boy/docker/nginx/ssl/cert.pem \
  -subj "/C=BR/ST=SP/L=Sao Paulo/O=Planeta Boy/OU=Dev/CN=localhost"
```

## Certificados Reais (Produção)

Para produção, use certificados reais de uma autoridade certificada (Let's Encrypt, etc.).

### Let's Encrypt com Certbot

```bash
# Instalar certbot
sudo apt-get install certbot

# Gerar certificado
sudo certbot certonly --standalone -d planeta-boy.test

# Copiar certificados para este diretório
sudo cp /etc/letsencrypt/live/planeta-boy.test/fullchain.pem /home/ubuntu/planeta-boy/docker/nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/planeta-boy.test/privkey.pem /home/ubuntu/planeta-boy/docker/nginx/ssl/key.pem

# Ajustar permissões
sudo chown www-data:www-data /home/ubuntu/planeta-boy/docker/nginx/ssl/*.pem
sudo chmod 644 /home/ubuntu/planeta-boy/docker/nginx/ssl/*.pem
```

## Arquivos Necessários

- `cert.pem` - Certificado SSL
- `key.pem` - Chave privada do certificado

## Ativar HTTPS no Nginx

Descomente a seção HTTPS em `nginx.conf` e reinicie os containers:

```bash
docker-compose down
docker-compose up -d
```

## Renovação Automática (Let's Encrypt)

Configure cron para renovação automática:

```bash
# Adicionar ao crontab
0 0 * * 0 certbot renew --quiet && docker-compose restart nginx
```
