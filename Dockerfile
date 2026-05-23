FROM php:7.4-apache

# ── System deps: APCu, Node.js 20, supervisord ───────────────────────────
RUN apt-get update && apt-get install -y curl supervisor && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# ── PHP config ────────────────────────────────────────────────────────────
RUN { \
      echo "memory_limit = 256M"; \
      echo "max_execution_time = 300"; \
      echo "extension=apcu.so"; \
      echo "apc.shm_size=64M"; \
    } > /usr/local/etc/php/conf.d/avalokitam.ini

# ── Apache: port 8080, modules, vhost ────────────────────────────────────
RUN a2enmod proxy proxy_http rewrite headers && \
    sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

COPY docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf

# ── PHP files + SPA ───────────────────────────────────────────────────────
COPY dist/*.php  /var/www/html/
COPY dist/*.txt  /var/www/html/
COPY dist/spa    /var/www/html/

# ── Node.js AI backend ────────────────────────────────────────────────────
COPY backend/package.json backend/package-lock.json* /app/
RUN cd /app && npm ci --omit=dev
COPY backend/ /app/

# ── Supervisord ───────────────────────────────────────────────────────────
COPY docker/supervisord.conf /etc/supervisor/conf.d/avalokitam.conf

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
