#!/bin/bash

# Script de inicialização da stack Docker de produção

set -e

cd "$(dirname "$0")"

echo "🚀 Iniciando stack Docker de produção..."

# Verifica se .env existe
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado!"
    echo "💡 Criando .env a partir de .env.example..."
    cp ../../.env.example .env
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
    sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=planeta_boy/' .env
    sed -i 's/# DB_USERNAME=root/DB_USERNAME=planeta_user/' .env
    sed -i 's/# DB_PASSWORD=/DB_PASSWORD=planeta_password/' .env
    sed -i 's/CACHE_STORE=database/CACHE_STORE=redis/' .env
    sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=redis/' .env
    sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=redis/' .env
    sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
    sed -i 's/LOG_LEVEL=debug/LOG_LEVEL=error/' .env
    sed -i 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/localhost/' .env
    echo "⚠️  Edite .env com suas configurações de produção antes de continuar!"
    exit 1
fi

# Verifica APP_KEY
if grep -q "^APP_KEY=$" .env; then
    echo "❌ APP_KEY não definida em .env!"
    echo "💡 Gerando APP_KEY..."
    docker-compose run --rm app php artisan key:generate
fi

# Build das imagens
echo "🔨 Buildando imagens Docker..."
docker-compose build

# Inicia os containers
echo "🐳 Iniciando containers..."
docker-compose up -d

# Aguarda serviços estarem prontos
echo "⏳ Aguardando serviços..."
sleep 15

# Executa migrations
echo "📊 Executando migrations..."
docker-compose exec app php artisan migrate --force

# Otimiza aplicação
echo "⚡ Otimizando aplicação..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

echo "✅ Stack de produção iniciada com sucesso!"
echo "📱 Acesse em: http://localhost"
echo "🔒 Configure SSL em ../../docker/nginx/ssl/ para HTTPS"
echo "🔍 Logs: docker-compose logs -f"
