#!/bin/bash

echo Installing dependencies
composer install

npm install
npm run production

echo Generating new key
php artisan key:generate

echo Refreshing the database
php artisan migrate
