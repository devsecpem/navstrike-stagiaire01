FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

USER www-data

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s \
	CMD curl -f http://localhost/ || exit 1

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html/"]
