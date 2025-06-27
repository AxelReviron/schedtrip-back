FROM dunglas/frankenphp

ENV SERVER_NAME=schedtrip.com

ARG USER=appuser

RUN \
	# Use "adduser -D ${USER}" for alpine based distros
	useradd ${USER}; \
	# Add additional capability to bind to port 80 and 443
	setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
	# Give write access to /data/caddy and /config/caddy
	chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    intl \
    gd \
    zip \
    exif
    # Add other PHP extensions here...

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY Caddyfile /etc/caddy/Caddyfile

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN npm ci --only=production

RUN npm run build

RUN chown -R ${USER}:${USER} /app

USER ${USER}

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
