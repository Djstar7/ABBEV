# Movie Dashboard - Dashboard de gestion de films

## Description

Dashboard Laravel avec Blade pour uploader et gérer des films avec un design inspiré de Netflix utilisant une palette de couleurs bleu ciel et noir.

## Fonctionnalités

### Dashboard Admin
- Interface moderne avec thème bleu ciel (#0ea5e9) et noir (#0f172a)
- Design responsive avec Tailwind CSS
- Navigation intuitive

### Gestion des Catégories
- Créer, modifier et supprimer des catégories
- Compteur de médias par catégorie
- Système de slugs automatique

### Gestion des Médias
- Upload de vidéos (MP4, MOV, AVI, WMV - Max 500MB)
- Upload de miniatures (JPEG, PNG, JPG, WEBP - Max 2MB)
- Champs disponibles:
  - Titre
  - Description
  - Catégorie
  - Durée (en secondes)
  - Date de publication
  - Mise en vedette
  - Compteur de vues
- Système de slugs automatique
- Pagination des médias

### API REST pour le Frontend

Toutes les routes API sont préfixées par `/api/v1`

#### Endpoints disponibles:

**Lister tous les médias**
```
GET /api/v1/media
Paramètres optionnels:
- category_id: Filtrer par catégorie
- is_featured: Filtrer les médias en vedette
- search: Rechercher dans titre et description
- per_page: Nombre d'éléments par page (défaut: 12)
```

**Obtenir un média spécifique**
```
GET /api/v1/media/{slug}
Note: Incrémente automatiquement le compteur de vues
```

**Lister les médias en vedette**
```
GET /api/v1/media/featured
Retourne les 10 derniers médias en vedette
```

**Lister toutes les catégories**
```
GET /api/v1/categories
Retourne toutes les catégories avec le nombre de médias
```

## Structure de la base de données

### Table `categories`
- id
- name
- slug (unique)
- description (nullable)
- timestamps

### Table `media`
- id
- category_id (foreign key)
- title
- slug (unique)
- description (nullable)
- duration (en secondes)
- video_path
- thumbnail_path (nullable)
- published_at (nullable)
- is_featured (boolean, défaut: false)
- views_count (integer, défaut: 0)
- timestamps

## Installation

1. Configurer la base de données dans `.env`
2. Lancer les migrations: `php artisan migrate`
3. Créer le lien symbolique pour le stockage: `php artisan storage:link`
4. Démarrer le serveur: `php artisan serve`

## Routes principales

### Dashboard
- `/` - Redirection vers la liste des médias
- `/media` - Liste des médias
- `/media/create` - Formulaire d'ajout de média
- `/media/{id}/edit` - Formulaire d'édition de média
- `/categories` - Liste des catégories
- `/categories/create` - Formulaire d'ajout de catégorie
- `/categories/{id}/edit` - Formulaire d'édition de catégorie

### API
- `/api/v1/media` - Liste des médias (avec filtres)
- `/api/v1/media/featured` - Médias en vedette
- `/api/v1/media/{slug}` - Détails d'un média
- `/api/v1/categories` - Liste des catégories

## Thème et Design

### Palette de couleurs
- **Bleu ciel primaire**: #0ea5e9 (`sky-primary`)
- **Bleu ciel clair**: #38bdf8 (`sky-light`)
- **Noir de fond**: #0f172a (`dark-bg`)
- **Noir des cartes**: #1e293b (`dark-card`)

### Caractéristiques visuelles
- Cards avec hover effects
- Bordures subtiles bleu ciel
- Transitions fluides
- Design moderne et épuré
- Typographie système sans-serif

## Technologies utilisées

- **Backend**: Laravel 12
- **Frontend**: Blade templates
- **Styling**: Tailwind CSS (via CDN)
- **Base de données**: SQLite (par défaut)
- **Upload**: Laravel Storage (public disk)

## Sécurité

- Validation des formulaires côté serveur
- Protection CSRF sur tous les formulaires
- Validation des types de fichiers
- Limitation de la taille des fichiers
- Sanitisation des slugs
- Foreign key constraints

## Notes

- Les vidéos sont stockées dans `storage/app/public/videos`
- Les miniatures sont stockées dans `storage/app/public/thumbnails`
- Les fichiers sont accessibles via `/storage/` après le lien symbolique
- L'API retourne uniquement les médias publiés (published_at <= maintenant)
