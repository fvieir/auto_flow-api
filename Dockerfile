FROM php:8.5.3-fpm-alpine

# UID/GID que você quer para o www-data (iguais ao host)
ARG UID=1000
ARG GID=1000

# Instalação da lib shadow antes de usar groupmod/usermod
# Para utilizar 'groupmod' e 'usermod'
RUN apk add --no-cache shadow

# Remapear www-data para o mesmo UID/GID do host
# Verificar dentro do container, com o comando "id www-data"
RUN groupmod -o -g "${GID}" www-data \
 && usermod  -o -u "${UID}" -g "${GID}" www-data \
 && mkdir -p /home/www-data \
 && chown -R www-data:www-data /home/www-data

# Instalação de Dependências e Configuração
# libzip-dev e zlib-dev → zip
# icu-dev → intl
# libxml2-dev → simplexml
# oniguruma-dev → mbstring
RUN apk add --no-cache \
    su-exec \
    autoconf \
    build-base \
    mysql-client \
    openssl \
    bash \
    nano \
    git \
    supervisor \
    libxml2-dev \
    oniguruma-dev \
    zlib-dev \
    libzip-dev \
    icu-dev

# Instala as extensões PHP necessárias: pdo, pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql pcntl simplexml mbstring zip intl

# Instala o Redis e suas dependências
RUN pecl install redis && docker-php-ext-enable redis

# Baixa e instala o Composer
RUN curl -sS https://getcomposer.org/installer  \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www

# Define permissões para o diretório da aplicação
COPY --chown=www-data:www-data . .

# Cria o diretório de logs para o Horizon e define permissões
RUN mkdir -p /var/www/storage/logs && \
    touch /var/www/storage/logs/horizon.log && \
    chown -R www-data:www-data /var/www/storage/logs

# Copia arquivos de configuração do PHP
COPY ./.docker/php/zzz-php.ini /usr/local/etc/php/conf.d/

# Copia arquivos de configuração do Nginx e PHP-FPM
COPY ./.docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./.docker/php-fpm/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Copia as configurações do Supervisor
COPY ./.docker/supervisor/supervisord.conf /etc/supervisord.conf
COPY ./.docker/supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
COPY ./.docker/supervisor/horizon.conf /etc/supervisor/conf.d/horizon.conf
COPY ./.docker/supervisor/scheduler.conf /etc/supervisor/conf.d/scheduler.conf

# Permissões mínimas
RUN mkdir -p /var/www/storage/logs /var/run/php /var/log/supervisor \
 && chown -R www-data:www-data /var/www /var/run/php /var/log/supervisor

# Copie o script para a imagem
COPY ./.docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Defina o entrypoint da imagem
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
