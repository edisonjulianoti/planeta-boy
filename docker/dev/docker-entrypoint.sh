#!/bin/sh
set -e

# docker-entrypoint.sh
# Ajusta o UID do www-data para match com o dono do diretório montado
# Resolve problemas de permissão entre host e container

# Pega o UID do diretório do projeto (volume montado)
HOST_UID=$(stat -c '%u' /var/www/html)

# Só altera se não for root (UID 0)
if [ "$HOST_UID" != "0" ] && [ "$HOST_UID" != "$(id -u www-data)" ]; then
    echo "Ajustando www-data UID: $(id -u www-data) -> $HOST_UID"
    
    # Muda UID do www-data
    usermod -u "$HOST_UID" www-data
    
    # Ajusta grupo também se o GID do diretório for diferente
    HOST_GID=$(stat -c '%g' /var/www/html)
    if [ "$HOST_GID" != "0" ] && [ "$HOST_GID" != "$(id -g www-data)" ]; then
        groupmod -g "$HOST_GID" www-data
    fi
    
    # Reajusta permissão dos arquivos que precisam ser graváveis
    # (storage e bootstrap/cache)
    chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache 2>/dev/null || true
fi

# Garante permissão mínima de leitura para arquivos novos (644) e pastas (755)
find /var/www/html/storage -type d -exec chmod 775 {} \; 2>/dev/null || true
find /var/www/html/storage -type f -exec chmod 664 {} \; 2>/dev/null || true
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true

echo "Permissões ajustadas. Iniciando PHP-FPM..."

# Executa o comando original (php-fpm)
exec "$@"
