#!/bin/bash

# Script de inicialização da stack Docker de desenvolvimento

set -e

echo "🚀 Iniciando stack Docker de desenvolvimento..."

# Verifica se .env.dev existe
if [ ! -f .env.dev ]; then
    echo "❌ Arquivo .env.dev não encontrado!"
    exit 1
fi

# Copia .env.dev para .env se não existir
if [ ! -f .env ]; then
    echo "📋 Copiando .env.dev para .env..."
    cp .env.dev .env
fi

# Gera APP_KEY se necessário
if [ -z "$APP_KEY" ]; then
    echo "🔑 Gerando APP_KEY..."
    APP_KEY=$(openssl rand -base64 32)
    sed -i "s/APP_KEY=/APP_KEY=$APP_KEY/" .env
fi

# Inicia os containers
echo "🐳 Iniciando containers..."
docker-compose -f docker-compose.dev.yml up -d

# Executa migrations
echo "📊 Executando migrations..."
docker-compose -f docker-compose.dev.yml exec app php artisan migrate --force

# Executa seeders
echo "🌱 Executando seeders..."
docker-compose -f docker-compose.dev.yml exec app php artisan db:seed --force

echo "✅ Stack de desenvolvimento iniciada com sucesso!"
echo "📱 Acesse em: http://localhost:8080"
echo "🔍 Logs: docker-compose -f docker-compose.dev.yml logs -f"
