FROM php:8.2-cli-bookworm

LABEL maintainer="Assalam Mebel"

ENV DEBIAN_FRONTEND=noninteractive
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

# 1. Install dependensi sistem dan library PHP & Chromium
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    chromium \
    fonts-liberation \
    procps \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        intl \
        bcmath \
        pcntl \
        posix \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install Node.js 22 LTS (menggunakan npm bawaan Node.js yang stabil)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Atur non-root user agar permissions file sinkron dengan Linux host
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} appuser || true \
    && useradd -u ${UID} -g ${GID} -m -s /bin/bash appuser || true \
    && mkdir -p /var/www/html \
    && chown -R appuser:appuser /var/www/html

WORKDIR /var/www/html

# Salin script entrypoint
COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

USER appuser

EXPOSE 8000 5173 3000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["composer", "run", "dev"]
