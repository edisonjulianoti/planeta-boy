# Stack Docker - Planeta Boy

Stack Docker completa para Laravel 13 com PHP 8.3, Nginx, MySQL e Redis.

## 📋 Pré-requisitos

- Docker instalado
- Docker Compose instalado
- Acesso SSH (para deploy remoto)

## 🐳 Arquivos Docker

- `Dockerfile` - Imagem multi-stage otimizada
- `docker-compose.yml` - Orquestração de serviços
- `.dockerignore` - Arquivos ignorados no build
- `nginx.conf` - Configuração Nginx
- `php.ini` - Configuração PHP de produção
- `docker-build.sh` - Script de build local
- `docker-deploy.sh` - Script de deploy remoto

## 🚀 Build Local

### Opção 1: Script automatizado
```bash
chmod +x docker-build.sh
./docker-build.sh
```

### Opção 2: Build manual
```bash
docker build -t planeta-boy:latest .
```

### Opção 3: Docker Compose
```bash
docker-compose build
```

## 🌐 Executar Localmente

### Iniciar todos os serviços
```bash
docker-compose up -d
```

### Verificar status
```bash
docker-compose ps
```

### Verificar logs
```bash
docker-compose logs -f app
```

### Parar serviços
```bash
docker-compose down
```

### Parar e remover volumes
```bash
docker-compose down -v
```

## 🔧 Configuração

### Variáveis de ambiente

Edite `docker-compose.yml` para configurar:

- `DB_DATABASE` - Nome do banco de dados
- `DB_USERNAME` - Usuário MySQL
- `DB_PASSWORD` - Senha MySQL
- `REDIS_HOST` - Host Redis
- `APP_URL` - URL da aplicação

### SSL/HTTPS

Descomente a seção HTTPS no `nginx.conf` e configure os certificados:

```nginx
ssl_certificate /etc/nginx/ssl/cert.pem;
ssl_certificate_key /etc/nginx/ssl/key.pem;
```

## 📡 Deploy Remoto

### 1. Configurar script de deploy

Edite `docker-deploy.sh` e altere:

```bash
REMOTE_USER="seu-usuario"
REMOTE_HOST="seu-servidor.com"
REMOTE_PORT="22"
```

### 2. Executar deploy

```bash
chmod +x docker-deploy.sh
./docker-deploy.sh
```

### 3. Deploy manual

```bash
# Build imagem
docker build -t planeta-boy:latest .

# Exportar
docker save planeta-boy:latest | gzip > planeta-boy.tar.gz

# Transferir
scp planeta-boy.tar.gz usuario@servidor:/tmp/

# No servidor remoto
ssh usuario@servidor
docker load -i /tmp/planeta-boy.tar.gz
docker-compose up -d
```

## 📊 Serviços

| Serviço | Descrição | Portas |
|---------|-----------|--------|
| app | Laravel (PHP-FPM) | 9000 |
| nginx | Proxy reverso | 80, 443 |
| mysql | Banco de dados | 3306 |
| redis | Cache e filas | 6379 |
| queue | Worker de filas | - |
| scheduler | Agendador de tarefas | - |

## 🔍 Monitoramento

### Verificar logs de um serviço
```bash
docker-compose logs app
docker-compose logs nginx
docker-compose logs mysql
docker-compose logs redis
```

### Logs em tempo real
```bash
docker-compose logs -f app
```

### Status dos containers
```bash
docker-compose ps
```

### Entrar em um container
```bash
docker-compose exec app sh
```

## 🛠️ Comandos Úteis

### Executar migrations
```bash
docker-compose exec app php artisan migrate
```

### Executar seeders
```bash
docker-compose exec app php artisan db:seed
```

### Limpar cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

### Instalar dependências
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

## 🔒 Segurança

### Boas práticas implementadas
- Multi-stage build para reduzir tamanho
- Imagens Alpine Linux
- Health checks configurados
- Headers de segurança no Nginx
- Variáveis de ambiente isoladas
- Restart automático configurado

### Recomendações adicionais
- Use senhas fortes em produção
- Configure SSL/TLS
- Atualize regularmente as imagens
- Use secrets para dados sensíveis
- Configure firewall no servidor

## 🐛 Troubleshooting

### Container não inicia
```bash
docker-compose logs app
docker-compose ps
```

### Build falha
```bash
docker system prune -a
docker-compose build --no-cache
```

### Permissões de arquivos
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Banco de dados não conecta
```bash
docker-compose exec mysql mysql -u root -p
```

## 📈 Performance

### Métricas esperadas
- Tamanho da imagem: ~300MB
- Tempo de build: 3-5 minutos
- Startup: ~15 segundos
- Uso de memória: ~200MB por container

### Otimizações
- OPcache habilitado
- Gzip no Nginx
- Cache estático configurado
- Multi-stage build
- Alpine Linux

## 🔄 Atualizações

### Atualizar aplicação
```bash
git pull
docker-compose build
docker-compose up -d
```

### Atualizar dependências
```bash
docker-compose exec app composer update
docker-compose exec app npm update
docker-compose build
docker-compose up -d
```

## 📞 Suporte

Para problemas, verifique:
1. Logs: `docker-compose logs`
2. Status: `docker-compose ps`
3. Recursos: `docker system df`
