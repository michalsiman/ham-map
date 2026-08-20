FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends cron \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY . /var/www/html/
COPY docker/crontab /etc/cron.d/ham-map-cron
COPY docker/entrypoint-cron.sh /usr/local/bin/entrypoint-cron.sh

RUN chmod 0644 /etc/cron.d/ham-map-cron \
    && crontab /etc/cron.d/ham-map-cron \
    && chmod 0755 /usr/local/bin/entrypoint-cron.sh \
    && chown -R www-data:www-data /var/www/html/data_files

EXPOSE 80
