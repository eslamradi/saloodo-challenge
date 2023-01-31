#!/bin/bash

composer install --no-interaction --optimize-autoloader
php artisan key:generate
php artisan jwt:secret --always-no
php artisan migrate --force

exec "$@"