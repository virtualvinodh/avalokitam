FROM virtualvinodh/avalokitam

# ── PHP files + SPA ───────────────────────────────────────────────────────
ADD ./*.php   /var/www/
ADD ./*.txt   /var/www/
ADD ./spa     /var/www/

# ── Apache modules ────────────────────────────────────────────────────────
RUN a2enmod proxy proxy_http rewrite headers

# ── PHP tuning ────────────────────────────────────────────────────────────
RUN echo "memory_limit = 256M"       >> /etc/php5/apache2/php.ini && \
    echo "max_execution_time = 300"  >> /etc/php5/apache2/php.ini

# ── Apache: port 8080, AllowOverride, AI proxy, SPA fallback ─────────────
RUN sed -i 's#AllowOverride [Nn]one#AllowOverride All#' /etc/apache2/apache2.conf && \
    sed -i 's/Listen 80/Listen 8080/'                   /etc/apache2/ports.conf

COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf

# ── Startup: inject AI_BACKEND_URL into vhost at runtime ─────────────────
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080
CMD ["/entrypoint.sh"]
