# Sistema de Gestión de Horarios SENA

## Introducción

Este proyecto es una aplicación web desarrollada con Laravel para la gestión de horarios de estudiantes y docentes en el Centro de Servicios Financieros-SENA (Servicio Nacional de Aprendizaje). La aplicación permite planificar, asignar y visualizar horarios de manera eficiente, facilitando la organización académica y administrativa.

## Requisitos

- Laravel: Laravel  10.8 o superior.
- PHP: PHP  8.2.0 o superior.
- Base de datos: PostgreSQL  16 o superior con las credenciales de acceso.
- node.js  20.11.1 o superior.
- npm  10.2.4.
- Composer.

## Instalación

### Configuración del Entorno

1. **php.ini**: Asegúrate de que las siguientes extensiones estén descomentadas en tu archivo `php.ini`:
   - zip
   - pdo_pgsql
   - openssl
   - mbstring
   - gd
   - fileinfo
   - curl

2. **Composer**: Ejecuta los siguientes comandos en la terminal:
   - `composer install` para instalar las dependencias.
   - `composer update` para actualizar las dependencias.

3. **Archivo .env**: Crea un archivo `.env` en la raíz del proyecto basándote en el archivo `.env.example` y configura las credenciales necesarias.

### Migraciones Laravel - PHP

1. Asegúrate de tener una base de datos PostgreSQL llamada `horario` creada.
2. Configura la conexión a la base de datos en el archivo `.env`.
3. Ejecuta `php artisan migrate_in_order` para aplicar las migraciones.

### Vite.js

1. Ejecuta en la terminal `npm install` para instalar las dependencias.
2. Ejecuta en la terminal `npm run build` para construir la aplicación.
3. Ejecuta en la terminal `npm run dev` para iniciar el servidor de desarrollo de Vite.

### Ejecución del Proyecto Laravel

1. Ejecuta en la terminal `php artisan key:generate` para generar la clave de la aplicación.
2. Ejecuta en la terminal `php artisan serve` para iniciar el servidor de desarrollo de Laravel.

## Contribuciones

Contribuciones, informes de errores y solicitudes de características son bienvenidos. Asegúrate de leer y seguir las directrices de contribución.


