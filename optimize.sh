#!/bin/bash

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan optimize
php artisan filament:optimize
php artisan filament:optimize-clear
