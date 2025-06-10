#!/bin/bash

echo "Installation de l'application Les recettes fullstack"
echo "============================================="

# Vérifier Docker
if ! command -v docker &> /dev/null; then
    echo "Docker n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "Docker Compose n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

echo "Docker détecté"

# Créer le fichier .env s'il n'existe pas
if [ ! -f "test-recipes/.env" ]; then
    if [ -f "test-recipes/.env.example" ]; then
        echo "Création du fichier .env..."
        cp test-recipes/.env.example test-recipes/.env
        echo "Fichier .env créé"
    else
        echo "Fichier .env.example non trouvé, création manuel..."
        cat > test-recipes/.env << 'EOF'
APP_NAME="Les recettes fullstack"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=test_recipe
DB_USERNAME=postgres
DB_PASSWORD=postgres

LOG_CHANNEL=stack
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
EOF
        echo "Fichier .env créé"
    fi
else
    echo "Fichier .env déjà présent"
fi

# Lancer l'installation
echo "Lancement de l'installation..."
chmod +x deploy.sh
./deploy.sh

echo ""
echo "Installation terminée !"
echo ""
echo "Ouvrez votre navigateur sur : http://localhost:8000"
echo "PgAdmin disponible sur : http://localhost:5050"
echo ""
echo "Pour arrêter l'application : docker-compose down" 