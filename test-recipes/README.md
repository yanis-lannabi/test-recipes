# Test Les recettes Fullstack

## Installation rapide

```bash
# Installation des dépendances
composer install
npm install

# Configuration

# J'ai laissé le .env dans mon repo pour que vous puissiez lancer plus facilement sinon il faut faire la commande d'en dessous et remplacer par les bonne variables
cp .env.example .env

php artisan key:generate

# Base de données
php artisan migrate

# Lancement
Composer run dev   # Lance le front et le back
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