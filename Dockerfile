FROM php:8.1-fpm

# Copiar archivos de composer
COPY composer.lock composer.json /var/www/

# Establecer directorio de trabajo
WORKDIR /var/www

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Instalar composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Agregar usuario para la aplicación Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar contenido del directorio de la aplicación existente
COPY. /var/www

# Cambiar permisos del directorio de la aplicación
COPY --chown=www:www. /var/www

# Cambiar al usuario actual a www
USER www

# Exponer puerto 9000 y comenzar el servidor php-fpm
EXPOSE 9000
CMD ["php-fpm"]
