#!/bin/bash

# Script para iniciar ambas stacks Docker (produção e desenvolvimento) simultaneamente

set -e

echo "🚀 Iniciando stacks Docker de produção e desenvolvimento simultaneamente..."

# Inicia stack de desenvolvimento
echo "📦 Iniciando stack de desenvolvimento..."
cd docker/dev
./start.sh &
DEV_PID=$!
cd ../..

# Inicia stack de produção
echo "🏭 Iniciando stack de produção..."
cd docker/prod
./start.sh &
PROD_PID=$!
cd ../..

echo "⏳ Aguardando inicialização..."
wait $DEV_PID
wait $PROD_PID

echo "✅ Ambas stacks iniciadas com sucesso!"
echo ""
echo "📊 Ambientes Online:"
echo "  🏭 Produção:   http://localhost"
echo "  📦 Desenvolvimento: http://localhost:8080"
echo "  🎨 Vite Dev:   http://localhost:5173"
echo ""
echo "🔍 Logs:"
echo "  Produção:   cd docker/prod && docker-compose logs -f"
echo "  Desenvolvimento: cd docker/dev && docker-compose logs -f"
echo ""
echo "🛑 Parar stacks:"
echo "  Produção:   cd docker/prod && docker-compose down"
echo "  Desenvolvimento: cd docker/dev && docker-compose down"
