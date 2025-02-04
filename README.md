
# Siga SATP - Backend

Sistema Integrado de Gestión Administrativa - SIGA SATP

# Instalación

1. Correr las migraciones y los seeders: `php artisan migrate:refresh --seed`

2. Ejecutar el comando: `php artisan passport:install` para obtener los personal access cliente de passport


## Development server

Run `php artisan serve` for a dev server. Navigate to `http://127.0.0.1:8000`

## Deploy heroku

Luego de hacer merge con master correr:

1. Elimiar la bd: `php artisan bd:wipe`

2. Correr las migraciones y los seeders: `php artisan migrate:refresh --seed`

3. Ejecutar el comando: `php artisan passport:install` para obtener los personal access cliente de passport

4. probar fork