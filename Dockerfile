ARG PHP_VERSION=8.5
ARG COMPOSER_VERSION=2.8

FROM composer:${COMPOSER_VERSION} AS vendor

FROM php:${PHP_VERSION}-cli-bookworm

LABEL maintainer="Liberu Genealogy <info@liberu.co.uk>"
LABEL org.opencontainers.image.title="Liberu Genealogy"
LABEL org.opencontainers.image.description="Production-ready Docker setup for Liberu Genealogy (Laravel Octane / RoadRunner)"
LABEL org.opencontainers.image.source=https://github.com/liberu-genealogy/genealogy-laravel
LABEL org.opencontainers.image.licenses=MIT

ARG USER_ID=1000
ARG GROUP_ID=1000
ARG TZ=UTC

ENV DEBIAN_FRONTEND=noninteractive \
    TERM=xterm-color \
    OCTANE_SERVER=roadrunner \
    TZ=${TZ} \
    LANG=C.UTF-8 \
    USER=laravel \
    ROOT=/var/www/html \
    APP_ENV=production \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_FUND=0 \
    COMPOSER_MAX_PARALLEL_HTTP=48 \
    WITH_HORIZON=false \
    WITH_SCHEDULER=false \
    WITH_REVERB=false

WORKDIR ${ROOT}

SHELL ["/bin/bash", "-eou", "pipefail", "-c"]

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN echo "Acquire::http::No-Cache true;" >> /etc/apt/apt.conf.d/99custom \
    && echo "Acquire::BrokenProxy    true;" >> /etc/apt/apt.conf.d/99custom

RUN apt-get update \
    && apt-get upgrade -yqq \
    && apt-get install -yqq --no-install-recommends --show-progress \
        apt-utils \
        curl \
        wget \
        vim \
        git \
        unzip \
        ncdu \
        procps \
        ca-certificates \
        supervisor \
        libsodium-dev \
    && install-php-extensions \
        apcu \
        bz2 \
        pcntl \
        mbstring \
        bcmath \
        sockets \
        pdo_pgsql \
        pdo_mysql \
        pdo_sqlite \
        opcache \
        exif \
        zip \
        intl \
        gd \
        redis \
        igbinary \
        ffi \
    && apt-get -y autoremove \
    && apt-get clean \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/log/lastlog /var/log/faillog

RUN arch="$(uname -m)" \
    && case "$arch" in \
        armhf)   _cronic_fname='supercronic-linux-arm'   ;; \
        aarch64) _cronic_fname='supercronic-linux-arm64' ;; \
        x86_64)  _cronic_fname='supercronic-linux-amd64' ;; \
        x86)     _cronic_fname='supercronic-linux-386'   ;; \
        *) echo >&2 "error: unsupported architecture: $arch"; exit 1 ;; \
    esac \
    && wget -q "https://github.com/aptible/supercronic/releases/download/v0.2.38/${_cronic_fname}" \
        -O /usr/bin/supercronic \
    && chmod +x /usr/bin/supercronic \
    && mkdir -p /etc/supercronic \
    && echo "*/1 * * * * php ${ROOT}/artisan schedule:run --no-interaction" > /etc/supercronic/laravel

RUN userdel --remove --force www-data \
    && groupadd --force -g ${GROUP_ID} ${USER} \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g ${GROUP_ID} -u ${USER_ID} ${USER}

RUN cp ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

COPY --link --from=vendor /usr/bin/composer /usr/bin/composer
COPY --link .docker/supervisord.conf /etc/
COPY --link .docker/octane/RoadRunner/supervisord.roadrunner.conf /etc/supervisor/conf.d/
COPY --link .docker/supervisord.*.conf /etc/supervisor/conf.d/
COPY --link .docker/php.ini ${PHP_INI_DIR}/conf.d/99-php.ini
COPY --link .docker/start-container /usr/local/bin/start-container
COPY --link .docker/healthcheck /usr/local/bin/healthcheck
COPY --link composer.* ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-ansi \
    --no-scripts \
    --no-progress \
    --audit

COPY --link package.json package-lock.json* ./

RUN npm ci --omit=dev 2>/dev/null || true

COPY --link . .

RUN mkdir -p \
        storage/framework/{sessions,views,cache,testing} \
        storage/logs \
        bootstrap/cache \
    && chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck

RUN composer dump-autoload --optimize --no-dev

RUN if composer show 2>/dev/null | grep -q 'spiral/roadrunner-cli'; then \
        ./vendor/bin/rr get-binary --quiet && chmod +x rr; \
    else \
        echo "spiral/roadrunner-cli not found — skipping rr binary download"; \
    fi

RUN npm run build 2>/dev/null || true

RUN chown -R ${USER_ID}:${GROUP_ID} ${ROOT} \
    && find / -perm /6000 -type f -exec chmod a-s {} + 2>/dev/null || true

USER ${USER}

EXPOSE 8000
EXPOSE 6001

ENTRYPOINT ["start-container"]

HEALTHCHECK --start-period=10s --interval=5s --timeout=5s --retries=8 \
    CMD healthcheck || exit 1
