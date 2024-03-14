@echo off
echo Limpieza de caché Laravel

:: Limpiar la caché de rutas
php artisan route:cache

:: Limpiar la caché de configuración
php artisan config:cache

:: Limpiar la caché de vistas
php artisan view:clear

:: Limpiar la caché de aplicaciones
php artisan cache:clear

:: optimizar de aplicaciones
php artisan optimize

composer dump-autoload

echo Limpieza de caché completada.
