# Sistema de turnos

Este es un proyecto de Laravel que permite gestionar turno (ej. Consultorio medico).

# Estructura de la base de datos

 - [MER](https://app.diagrams.net/#G1yrD6PBhljJ2zj71bvgZCZ2XpK46x7VTR#%7B%22pageId%22%3A%22TMvDeZPq3Egd9Cxy8RMZ%22%7D)
 - [Relacion de tablas](https://imgur.com/a/SKiRb6u)


# Requerimientos

- PHP 8.1.X
- Laravel 11.x
- Composer
- Node.js

# Instalación

1. Clonar el repositorio
2. Ejecutar `composer install` en la carpeta raíz del proyecto
3. Ejecutar `npm install` en la carpeta raíz del proyecto
4. Ejecutar `php artisan key:generate` en la carpeta raíz del proyecto
5. Ejecutar `php artisan migrate` en la carpeta raíz del proyecto
6. Ejecutar `php artisan db:seed` en la carpeta raíz del proyecto

# Configuración

1. Crear un archivo `.env` o utilizar el archivo `.env.example` en la carpeta raíz del proyecto
2. Modificar los valores de la variable `VITE_MAP_KEY` y agregar las credenciales de google maps en el archivo `.env` o `.env.example`
3. Ingresar a mailtrap y registrarse para obtener las credenciales de acceso SMTP y modificar los valores de la variable `MAIL_USERNAME` y `MAIL_PASSWORD` en el archivo `.env` o `.env.example`
4. Modificar los valores de la variable `APP_NAME` y `APP_LOCALE` en el archivo `.env` o `.env.example`


# Ejecución

1. Ejecutar `npm run dev` en la carpeta raíz del proyecto
2. Ejecutar `php artisan serve` en la carpeta raíz del proyecto
3. Abrir el navegador en `http://127.0.0.1:8000`




