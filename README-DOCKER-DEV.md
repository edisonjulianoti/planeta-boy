# Stack Docker de Desenvolvimento - Planeta Boy

Stack Docker otimizada para desenvolvimento com hot reload, debug e volumes montados.

## 📋 Pré-requisitos

- Docker instalado
- Docker Compose instalado

## 🐳 Arquivos Docker de Desenvolvimento

- `docker-compose.dev.yml` - Orquestração para desenvolvimento
- `Dockerfile.dev` - Imagem com Xdebug e dev dependencies
- `.env.dev` - Variáveis de ambiente de desenvolvimento
- `.dockerignore.dev` - Arquivos ignorados no build
- `docker-dev-start.sh` - Script de inicialização automatizado

## 🚀 Iniciar Stack de Desenvolvimento

### Opção 1: Script automatizado
```bash
chmod +x docker-dev-start.sh
./docker-dev-start.sh
```

### Opção 2: Iniciação manual
```bash
# Copiar .env.dev para .env
cp .env.dev .env

# Gerar APP_KEY
docker-compose -f docker-compose.dev.yml exec app php artisan key:generate

# Iniciar containers
docker-compose -f docker-compose.dev.yml up -d

# Executar migrations
docker-compose -f docker-compose.dev.yml exec app php artisan migrate
```

## 🔧 Configuração

### Variáveis de ambiente

Edite `.env.dev` para configurar:
- `APP_DEBUG=true` - Debug habilitado
- `APP_ENV=local` - Ambiente local
- `DB_HOST=mysql` - Host MySQL
- `REDIS_HOST=redis` - Host Redis

### Xdebug

Xdebug está configurado para debug:
- Mode: debug
- Client host: host.docker.internal
- Client port: 9003
- Log: /var/log/xdebug.log

Configure seu IDE para usar Xdebug na porta 9003.

## 📊 Serviços

| Serviço | Descrição | Portas |
|---------|-----------|--------|
| app | Laravel (PHP-FPM) | 9000 |
| nginx | Proxy reverso | 8080 |
| mysql | Banco de dados | 3308 |
| redis | Cache e filas | 6380 |
| vite | Vite dev server | 5173 |

## 🔍 Monitoramento

### Verificar logs
```bash
# Todos os serviços
docker-compose -f docker-compose.dev.yml logs -f

# Serviço específico
docker-compose -f docker-compose.dev.yml logs -f app
docker-compose -f docker-compose.dev.yml logs -f nginx
docker-compose -f docker-compose.dev.yml logs -f mysql
docker-compose -f docker-compose.dev.yml logs -f redis
```

### Status dos containers
```bash
docker-compose -f docker-compose.dev.yml ps
```

### Entrar em um container
```bash
docker-compose -f docker-compose.dev.yml exec app sh
```

## 🛠️ Comandos Úteis

### Executar migrations
```bash
docker-compose -f docker-compose.dev.yml exec app php artisan migrate
docker-compose -f docker-compose.dev.yml exec app php artisan migrate:rollback
```

### Executar seeders
```bash
docker-compose -f docker-compose.dev.yml exec app php artisan db:seed
```

### Limpar cache
```bash
docker-compose -f docker-compose.dev.yml exec app php artisan cache:clear
docker-compose -f docker-compose.dev.yml exec app php artisan config:clear
docker-compose -f docker-compose.dev.yml exec app php artisan route:clear
```

### Instalar dependências
```bash
# PHP
docker-compose -f docker-compose.dev.yml exec app composer install
docker-compose -f docker-compose.dev.yml exec app composer update

# Node
docker-compose -f docker-compose.dev.yml exec app npm install
docker-compose -f docker-compose.dev.yml exec app npm update
```

### Executar testes
```bash
docker-compose -f docker-compose.dev.yml exec app php artisan test
```

### Tinker (console interativo)
```bash
docker-compose -f docker-compose.dev.yml exec app php artisan tinker
```

## 🔄 Hot Reload

A stack de desenvolvimento possui hot reload habilitado:
- Código PHP: Alterações são refletidas imediatamente
- Vite: Hot module replacement habilitado
- Volumes: Código fonte montado em tempo real

## 🐛 Debug

### Configurar Xdebug no VS Code

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            }
        }
    ]
}
```

### Configurar Xdebug no PhpStorm

1. Settings > PHP > Debug
2. Xdebug port: 9003
3. Path mapping: `/var/www/html` → projeto local

## 📈 Portas Diferentes da Produção

Para evitar conflitos, a stack de desenvolvimento usa portas diferentes:
- Site: http://localhost:8080 (produção: 80)
- MySQL: localhost:3308 (produção: 3307)
- Redis: localhost:6380 (produção: 6379)
- Vite: localhost:5173

## 🛑 Parar Stack

```bash
# Parar containers
docker-compose -f docker-compose.dev.yml down

# Parar e remover volumes
docker-compose -f docker-compose.dev.yml down -v
```

## 🔄 Reconstruir Imagens

```bash
docker-compose -f docker-compose.dev.yml build --no-cache
docker-compose -f docker-compose.dev.yml up -d
```

## 🐛 Troubleshooting

### Container não inicia
```bash
docker-compose -f docker-compose.dev.yml logs app
docker-compose -f docker-compose.dev.yml ps
```

### Permissões de arquivos
```bash
docker-compose -f docker-compose.dev.yml exec app chown -R www-data:www-data storage bootstrap/cache
```

### Xdebug não funciona
- Verifique se host.docker.internal está acessível
- Verifique a porta 9003 não está em uso
- Verifique o log do Xdebug: `docker-compose -f docker-compose.dev.yml exec app cat /var/log/xdebug.log`

### Banco de dados não conecta
```bash
docker-compose -f docker-compose.dev.yml exec mysql mysql -u root -p
```

## 📞 Diferenças da Stack de Produção

| Característica | Desenvolvimento | Produção |
|---------------|-----------------|-----------|
| APP_DEBUG | true | false |
| OPcache | desabilitado | habilitado |
| Xdebug | habilitado | desabilitado |
| Volumes | montados | otimizados |
| Composer dev | incluído | excluído |
| Portas | 8080, 3308, 6380 | 80, 3307, 6379 |
| Logs | debug | error |

## 🎯 Acesso Rápido

- **Site**: http://localhost:8080
- **MySQL**: localhost:3308
  - User: planeta_user
  - Password: planeta_password
  - Database: planeta_boy
- **Redis**: localhost:6380
- **Vite**: http://localhost:5173
