#!/bin/bash

# Script de build Docker para Planeta Boy

set -e

echo "🚀 Iniciando build Docker..."

# Variáveis
IMAGE_NAME="planeta-boy"
IMAGE_TAG="${1:-latest}"

echo "📦 Buildando imagem Docker..."
docker build -t ${IMAGE_NAME}:${IMAGE_TAG} .

echo "✅ Build concluído!"
echo "📦 Imagem: ${IMAGE_NAME}:${IMAGE_TAG}"

# Opcional: Testar a imagem
read -p "Deseja testar a imagem? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🧪 Testando container..."
    docker run --rm ${IMAGE_NAME}:${IMAGE_TAG} php -v
    echo "✅ Teste concluído!"
fi

echo "🎉 Build finalizado!"
