FROM php:8.3-apache

# Instalar extensões
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurar xdebug
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ativar mod_rewrite
RUN a2enmod rewrite

# Definir DocumentRoot para a pasta public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Ajustar a configuração do Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Criar diretório da app
WORKDIR /var/www/html

# Copiar todos os arquivos para dentro do container
COPY . .

# Cria pastas necessárias e define permissões
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/coverage-report \
    && chmod -R 777 /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/coverage-report

# Instalar dependências
RUN composer install --no-interaction --prefer-dist

# Ajustar permissões (evita erros de acesso)
RUN chown -R www-data:www-data /var/www/html

# Expor a porta padrão do Apache
EXPOSE 80