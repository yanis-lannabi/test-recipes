# Test Les Recettes Fullstack

Application de gestion de recettes construite avec Laravel 11, Vue.js 3, PostgreSQL et Docker.

## Prérequis

### Pour l'installation Docker (Recommandée)
- **Docker** et **Docker Compose** installés
- **Git** pour cloner le projet

### Pour l'installation locale
- **PHP 8.2+** avec extensions : `pdo_pgsql`, `mbstring`, `zip`, `gd`
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js 18+** et **npm**
- **PostgreSQL 15+**
- **Serveur web** (Apache/Nginx) ou PHP built-in server

## Installation avec Docker (Recommandée)

### Méthode rapide
```bash
# 1. Cloner le projet
git clone git@github.com:yanis-lannabi/test-recipes.git
cd test-recipes

# 2. Lancer l'installation automatique
./install.sh
```

### Méthode manuelle
```bash
# 1. Cloner le projet
git clone git@github.com:yanis-lannabi/test-recipes.git
cd test-recipes

# 2. Copier le fichier d'environnement
cp test-recipes/.env.example test-recipes/.env

# 3. Lancer le déploiement
./deploy.sh
```

**C'est tout !** L'application sera accessible sur http://localhost:8000

## 🔧 Installation locale (Sans Docker)

### 1. Préparer l'environnement

```bash
# Cloner le projet
git clone git@github.com:yanis-lannabi/test-recipes.git
cd test-recipes/test-recipes

# Copier et configurer l'environnement
cp .env.example .env
```

### 2. Configurer PostgreSQL

Créez une base de données PostgreSQL :

```sql
CREATE DATABASE test_recipe;
CREATE USER postgres WITH PASSWORD 'postgres';
GRANT ALL PRIVILEGES ON DATABASE test_recipe TO postgres;
```

Modifiez le fichier `.env` :
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=test_recipe
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### 3. Installer les dépendances PHP

```bash
# Installer Composer si pas déjà fait
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer les dépendances
composer install
```

### 4. Installer les dépendances frontend

```bash
# Installer Node.js si pas déjà fait (Ubuntu/Debian)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Installer les dépendances
npm install
```

### 5. Configurer Laravel

```bash
# Générer la clé d'application
php artisan key:generate

# Exécuter les migrations
php artisan migrate

# Lancer les seeders (optionnel)
php artisan db:seed
```

### 6. Construire les assets frontend

```bash
# Pour le développement
npm run dev

# Pour la production
npm run build
```

### 7. Lancer l'application

```bash
# Serveur de développement PHP
php artisan serve

# Ou configurer un serveur web (Apache/Nginx)
# L'application sera accessible sur http://localhost:8000
```

## 🌐 Accès aux services

### Avec Docker
- **Application** : http://localhost:8000
- **PgAdmin** : http://localhost:5050
  - Email : `admin@admin.com`
  - Mot de passe : `admin123`
- **PostgreSQL** : `localhost:5433`

### Installation locale
- **Application** : http://localhost:8000 (ou selon votre configuration serveur)
- **PostgreSQL** : `localhost:5432`

## 🛠️ Commandes utiles

### Docker
```bash
# Arrêter tous les services
docker-compose down

# Voir les logs
docker-compose logs app
docker-compose logs nginx

# Accéder au container Laravel
docker-compose exec app sh

# Rebuild complet
docker-compose down && docker-compose up -d --build
```

## Routes disponibles

### Recettes

#### Liste des recettes
- **URL**: `/recipes`
- **Méthode**: `GET`
- **Description**: Récupère la liste paginée des recettes
- **Réponse**: Liste des recettes avec pagination

#### Détails d'une recette
- **URL**: `/recipes/{id}`
- **Méthode**: `GET`
- **Description**: Récupère les détails d'une recette spécifique
- **Paramètres**: 
  - `id` (integer) - ID de la recette
- **Réponse**: Détails de la recette

#### Création d'une recette
- **URL**: `/recipes`
- **Méthode**: `POST`
- **Description**: Crée une nouvelle recette
- **Données requises**:
  ```json
  {
    "title": "string (max: 255)",
    "description": "string (min: 10)",
    "ingredients": "string (min: 10)"
  }
  ```

#### Modification d'une recette
- **URL**: `/recipes/{id}`
- **Méthode**: `PUT`
- **Description**: Met à jour une recette existante
- **Paramètres**: 
  - `id` (integer) - ID de la recette
- **Données requises**:
  ```json
  {
    "title": "string (max: 255)",
    "description": "string (min: 10)",
    "ingredients": "string (min: 10)"
  }
  ```

#### Suppression d'une recette
- **URL**: `/recipes/{id}`
- **Méthode**: `DELETE`
- **Description**: Supprime une recette
- **Paramètres**: 
  - `id` (integer) - ID de la recette 