#!/bin/bash

# Script de inicialização da stack Docker de desenvolvimento

set -e

cd "$(dirname "$0")"

echo "🚀 Iniciando stack Docker de desenvolvimento..."

# Verifica se .env existe
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado!"
    echo "💡 Criando .env a partir de .env.example..."
    cp ../../.env.example .env
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
    sed -i 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/localhost:8080/' .env
fi

# Gera APP_KEY se necessário
if grep -q "^APP_KEY=$" .env; then
    echo "🔑 Gerando APP_KEY..."
    docker-compose run --rm app php artisan key:generate
fi

# Inicia os containers
echo "🐳 Iniciando containers..."
docker-compose up -d

# Aguarda MySQL estar pronto
echo "⏳ Aguardando MySQL..."
sleep 10

# Executa migrations
echo "📊 Executando migrations..."
docker-compose exec app php artisan migrate --force

# Executa seeders
echo "🌱 Executando seeders..."
docker-compose exec app php artisan db:seed --force

echo "✅ Stack de desenvolvimento iniciada com sucesso!"
echo "📱 Acesse em: http://localhost:8080"
echo "🎨 Vite: http://localhost:5173"
echo "🔍 Logs: docker-compose logs -f"
