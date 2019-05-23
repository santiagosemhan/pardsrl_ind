FROM php:7.2-apache

MAINTAINER santiagosemhan <santiagosemhan@gmail.com>

# Override with custom opcache settings
# COPY php-config/custom.ini $PHP_INI_DIR/conf.d/

RUN docker-php-ext-install mysqli pdo pdo_mysql opcache

#
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
ENV SF_CACHE_DIR /var/cache
ENV SF_LOG_DIR /var/logs

WORKDIR /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

RUN mkdir -p ${SF_LOG_DIR} ${SF_CACHE_DIR}  && \
    chmod 777 ${SF_LOG_DIR} ${SF_CACHE_DIR}

EXPOSE 80

ADD start.sh /usr/local/bin/start.sh

ENTRYPOINT ["/usr/local/bin/start.sh"]

CMD ["apache2-foreground"]
