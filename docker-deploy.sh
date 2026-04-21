#!/bin/bash

# Script de deploy Docker para Planeta Boy

set -e

# Configurações - Altere conforme necessário
REMOTE_USER="${REMOTE_USER:-root}"
REMOTE_HOST="${REMOTE_HOST:-your-server.com}"
REMOTE_PORT="${REMOTE_PORT:-22}"
IMAGE_NAME="planeta-boy"
IMAGE_TAG="${1:-latest}"

echo "🚀 Iniciando deploy para ${REMOTE_HOST}..."

# Build da imagem
echo "📦 Buildando imagem Docker..."
docker build -t ${IMAGE_NAME}:${IMAGE_TAG} .

# Exportar imagem
echo "📤 Exportando imagem..."
docker save ${IMAGE_NAME}:${IMAGE_TAG} | gzip > ${IMAGE_NAME}-${IMAGE_TAG}.tar.gz

# Transferir para servidor remoto
echo "📡 Transferindo para servidor remoto..."
scp -P ${REMOTE_PORT} ${IMAGE_NAME}-${IMAGE_TAG}.tar.gz ${REMOTE_USER}@${REMOTE_HOST}:/tmp/

# Executar comandos no servidor remoto
echo "🔧 Configurando no servidor remoto..."
ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << 'ENDSSH'
    # Carregar imagem
    docker load -i /tmp/planeta-boy-latest.tar.gz
    
    # Parar containers antigos
    docker stop planeta-boy-app planeta-boy-nginx planeta-boy-queue planeta-boy-scheduler 2>/dev/null || true
    docker rm planeta-boy-app planeta-boy-nginx planeta-boy-queue planeta-boy-scheduler 2>/dev/null || true
    
    # Limpar arquivo temporário
    rm /tmp/planeta-boy-latest.tar.gz
    
    echo "✅ Imagem carregada e containers removidos"
ENDSSH

# Transferir arquivos de configuração
echo "📡 Transferindo configurações..."
scp -P ${REMOTE_PORT} docker-compose.yml ${REMOTE_USER}@${REMOTE_HOST}:/tmp/
scp -P ${REMOTE_PORT} nginx.conf ${REMOTE_USER}@${REMOTE_HOST}:/tmp/

# Iniciar containers no servidor remoto
echo "🚀 Iniciando containers..."
ssh -p ${REMOTE_PORT} ${REMOTE_USER}@${REMOTE_HOST} << 'ENDSSH'
    cd /opt/planeta-boy
    
    # Copiar configurações
    cp /tmp/docker-compose.yml .
    cp /tmp/nginx.conf .
    
    # Iniciar containers
    docker-compose up -d
    
    # Verificar status
    docker-compose ps
    
    echo "✅ Deploy concluído!"
ENDSSH

# Limpar arquivo local
rm ${IMAGE_NAME}-${IMAGE_TAG}.tar.gz

echo "🎉 Deploy finalizado com sucesso!"
