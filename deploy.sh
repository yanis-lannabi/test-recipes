#!/bin/bash

echo "Démarrage de l'application..."

docker-compose down
docker-compose up -d --build

echo "Attente de la base de données..."
sleep 8

echo "Installation des dépendances..."
docker-compose exec app composer install --no-interaction
docker-compose exec app npm install

echo "Construction des assets frontend..."
docker-compose exec app npm run build

echo "Correction des permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

docker-compose exec app php artisan key:generate --force
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force 2>/dev/null || echo "Pas de seeders"

echo ""
echo "Application disponible sur : http://localhost:8000"
echo "PgAdmin disponible sur : http://localhost:5050"
echo "Base PostgreSQL : localhost:5433"
echo ""
echo "Pour arrêter : docker-compose down" 