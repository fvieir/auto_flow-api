#!/bin/bash

# strict mode (faz seu entrypoint parar no primeiro erro real)
set -euxo pipefail

# Pastas necessárias
mkdir -p \
  /var/run/php \
  /var/log/supervisor \
  /var/www/storage/logs \
  /var/www/bootstrap/cache

# Permissões
chown -R www-data:www-data \
  /var/run/php \
  /var/log/supervisor \
  /var/www/storage \
  /var/www/bootstrap/cache

# Caso dê “Permission denied”, executar os blocos abaixo comentados
# diretórios: 2775  (rwx para owner/grupo, setgid p/ herdar o grupo)
#find /var/www/storage /var/www/bootstrap/cache -type d -exec chmod 2775 {} +

# arquivos: 0644 (nada de bit de execução em .gitignore etc.)
#find /var/www/storage /var/www/bootstrap/cache -type f -exec chmod 0644 {} +

# Instala vendor se o autoload não existir (cobre vendor ausente OU install
# interrompido que deixou a pasta vendor pela metade, sem autoload.php)
if [ ! -f /var/www/vendor/autoload.php ]; then
  export HOME=/home/www-data
  mkdir -p "$HOME/.composer"
  chown -R www-data:www-data "$HOME"
  su-exec www-data composer install --no-interaction --no-progress
  chown -R www-data:www-data /var/www/vendor
fi

cd /var/www

# Se não existir .env, copia do .env.example (e acusa erro se nem exemplo existir)
if [ ! -f .env ]; then
  [ -f .env.example ] || { echo "Faltando .env e .env.example" >&2; exit 1; }
  cp .env.example .env
  chown www-data:www-data .env
fi

# Gera APP_KEY se não houver linha APP_KEY= (ou estiver vazia)
if ! grep -q '^APP_KEY=' .env || grep -Eq '^APP_KEY=\s*$' .env; then
  php artisan key:generate --force
fi

# Gera a chave JWT se APP_KEY existir E JWT_SECRET não existir ou estiver vazia
#if grep -q '^APP_KEY=' .env && (! grep -q '^JWT_SECRET=' .env || grep -Eq '^JWT_SECRET=\s*$' .env); then
#  php artisan jwt:secret --force
#fi

# Inicia o supervisor
exec supervisord -c /etc/supervisord.conf -n
