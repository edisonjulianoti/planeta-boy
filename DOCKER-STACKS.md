# Stacks Docker - Planeta Boy

Estrutura Docker isolada para ambientes de produção e desenvolvimento.

## 📁 Estrutura de Diretórios

```
planeta-boy/
├── docker/
│   ├── prod/              # Stack de Produção
│   │   ├── docker-compose.yml
│   │   ├── .env
│   │   ├── storage/
│   │   └── start.sh
│   └── dev/               # Stack de Desenvolvimento
│       ├── docker-compose.yml
│       ├── .env
│       ├── storage/
│       └── start.sh
├── docker/nginx/          # Configurações compartilhadas
│   ├── ssl/
│   └── mysql/
│       └── my.cnf
├── nginx.conf             # Configuração Nginx
├── Dockerfile             # Imagem de produção
├── Dockerfile.dev         # Imagem de desenvolvimento
└── start-all.sh           # Inicia ambas stacks
```

## 🚀 Iniciar Stacks

### Iniciar Apenas Produção

```bash
cd docker/prod
./start.sh
```

Acesso: http://localhost

### Iniciar Apenas Desenvolvimento

```bash
cd docker/dev
./start.sh
```

Acesso: http://localhost:8080
Vite: http://localhost:5173

### Iniciar Ambas Simultaneamente

```bash
./start-all.sh
```

Isolamento completo - ambas stacks podem rodar ao mesmo tempo sem conflitos.

## 🔧 Configuração

### Produção (docker/prod/.env)

Edite `docker/prod/.env` para configurar:
- `APP_URL=http://localhost`
- `APP_DEBUG=false`
- `APP_ENV=production`
- `DB_HOST=mysql`
- `REDIS_HOST=redis`

### Desenvolvimento (docker/dev/.env)

Edite `docker/dev/.env` para configurar:
- `APP_URL=http://localhost:8080`
- `APP_DEBUG=true`
- `APP_ENV=local`
- `DB_HOST=mysql`
- `REDIS_HOST=redis`

## 📊 Serviços

### Produção

| Serviço | Descrição | Portas |
|---------|-----------|--------|
| app | Laravel (PHP-FPM) | - |
| nginx | Proxy reverso | 80, 443 |
| mysql | Banco de dados | 3307 |
| redis | Cache e filas | 6379 |
| queue | Worker de filas | - |
| scheduler | Agendador de tarefas | - |

### Desenvolvimento

| Serviço | Descrição | Portas |
|---------|-----------|--------|
| app | Laravel (PHP-FPM) | 9000 |
| nginx | Proxy reverso | 8080 |
| mysql | Banco de dados | 3308 |
| redis | Cache e filas | 6380 |
| vite | Hot reload assets | 5173 |

## 🔍 Logs

### Produção

```bash
cd docker/prod
docker-compose logs -f
```

### Desenvolvimento

```bash
cd docker/dev
docker-compose logs -f
```

## 🛑 Parar Stacks

### Produção

```bash
cd docker/prod
docker-compose down
```

### Desenvolvimento

```bash
cd docker/dev
docker-compose down
```

### Ambas

```bash
cd docker/prod && docker-compose down
cd ../dev && docker-compose down
```

## 🔒 Isolamento

As stacks são completamente isoladas:

- **Redes**: `planeta-boy-network` (prod) vs `planeta-boy-dev-network` (dev)
- **Volumes**: `mysql_data`/`redis_data` (prod) vs `mysql_dev_data`/`redis_dev_data` (dev)
- **Storage**: `docker/prod/storage` vs `docker/dev/storage`
- **Containers**: `planeta-boy-*` (prod) vs `planeta-boy-*-dev` (dev)
- **Portas**: 80/443/3307/6379 (prod) vs 8080/3308/6380/5173 (dev)
- **Arquivos .env**: `docker/prod/.env` vs `docker/dev/.env`

## 🔧 SSL/HTTPS

Para habilitar HTTPS em produção:

1. Coloque certificados em `docker/nginx/ssl/`:
   - `cert.pem`
   - `key.pem`

2. Siga instruções em `docker/nginx/ssl/README.md`

3. Descomente seção HTTPS em `nginx.conf`

4. Reinicie produção:
```bash
cd docker/prod
docker-compose down
docker-compose up -d
```

## 📝 Comandos Úteis

### Executar migrations

**Produção:**
```bash
cd docker/prod
docker-compose exec app php artisan migrate
```

**Desenvolvimento:**
```bash
cd docker/dev
docker-compose exec app php artisan migrate
```

### Limpar cache

**Produção:**
```bash
cd docker/prod
docker-compose exec app php artisan cache:clear
```

**Desenvolvimento:**
```bash
cd docker/dev
docker-compose exec app php artisan cache:clear
```

### Executar testes

```bash
cd docker/dev
docker-compose exec app php artisan test
```

## 🐛 Troubleshooting

### Container não inicia

```bash
cd docker/prod  # ou docker/dev
docker-compose logs app
docker-compose ps
```

### Permissões de arquivos

```bash
cd docker/prod  # ou docker/dev
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Reconstruir imagens

```bash
cd docker/prod  # ou docker/dev
docker-compose build --no-cache
docker-compose up -d
```
