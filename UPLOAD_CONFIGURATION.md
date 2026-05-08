# Configuration pour Upload de Gros Fichiers (jusqu'à 5GB)

Ce projet utilise **FilePond** avec **Laravel Chunk Upload** pour gérer l'upload de fichiers volumineux avec :
- ✅ Barre de progression en temps réel
- ✅ Possibilité d'annuler l'upload à tout moment
- ✅ Upload par chunks (morceaux de 2MB)
- ✅ Retry automatique en cas d'erreur réseau
- ✅ Support de fichiers jusqu'à 5GB (configurable)

## 📦 Packages Installés

1. **pion/laravel-chunk-upload** (Backend)
   - Gestion des uploads par chunks côté serveur
   - Compatible avec FilePond, Dropzone, etc.

2. **FilePond** (Frontend)
   - Interface utilisateur moderne
   - Progress bar intégrée
   - Drag & drop
   - Annulation d'upload

## ⚙️ Configuration Requise

### 1. Configuration PHP (php.ini)

Si le fichier `.htaccess` ne fonctionne pas (serveurs nginx, FPM, etc.), modifiez votre `php.ini` :

```ini
upload_max_filesize = 5120M
post_max_size = 5120M
max_execution_time = 3600
max_input_time = 3600
memory_limit = 512M
```

**Localisation du php.ini :**
```bash
php --ini
```

### 2. Configuration Apache (.htaccess)

Le fichier `public/.htaccess` a déjà été configuré automatiquement avec :
```apache
php_value upload_max_filesize 5120M
php_value post_max_size 5120M
php_value max_execution_time 3600
php_value max_input_time 3600
php_value memory_limit 512M
```

### 3. Configuration Nginx

Si vous utilisez Nginx, ajoutez ceci dans votre configuration :

```nginx
server {
    client_max_body_size 5120M;
    client_body_timeout 3600s;

    location ~ \.php$ {
        fastcgi_read_timeout 3600s;
        fastcgi_send_timeout 3600s;
    }
}
```

Puis redémarrez Nginx :
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### 4. Configuration Laravel

Aucune configuration supplémentaire n'est nécessaire dans Laravel. Le middleware `ValidatePostSize` est automatiquement géré par les chunks.

## 🚀 Utilisation

### Uploader un Fichier Vidéo

1. Allez sur la page **Ajouter un média** : `/media/create`
2. Faites glisser votre vidéo (jusqu'à 5GB) dans la zone FilePond
3. L'upload démarre automatiquement avec une barre de progression
4. Vous pouvez annuler à tout moment en cliquant sur le bouton ❌

### Structure des Fichiers

Les fichiers uploadés sont stockés dans :
- **Vidéos** : `storage/app/public/videos/`
- **Images** : `storage/app/public/images/`
- **Autres** : `storage/app/public/files/`

## 🔧 Routes API

### Upload Chunked
```
POST /upload/chunk
```
Gère l'upload par chunks avec FilePond.

### Suppression (Annulation)
```
DELETE /upload/chunk
```
Supprime un fichier uploadé (utilisé lors de l'annulation).

## 📝 Modification de la Limite de Taille

Pour augmenter la limite au-delà de 5GB :

1. **Frontend** (`resources/views/media/create.blade.php`) :
```javascript
maxFileSize: '10GB',  // Changer ici
```

2. **Backend** (`public/.htaccess` ou `php.ini`) :
```apache
php_value upload_max_filesize 10240M  # 10GB
php_value post_max_size 10240M        # 10GB
```

3. **Taille des Chunks** (optionnel) :
```javascript
chunkSize: 5000000,  // 5MB par chunk pour les très gros fichiers
```

## 🐛 Dépannage

### Erreur "PostTooLargeException"

Si vous recevez encore cette erreur :

1. Vérifiez que PHP utilise bien le bon `php.ini` :
```bash
php -i | grep "Loaded Configuration File"
```

2. Redémarrez votre serveur web après modification :
```bash
# Apache
sudo systemctl restart apache2

# Nginx + PHP-FPM
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

# Laravel Sail (Docker)
./vendor/bin/sail restart
```

3. Vérifiez que le `.htaccess` est bien lu par Apache :
```bash
# Dans la config Apache, vérifiez :
AllowOverride All
```

### L'upload se bloque ou échoue

1. Vérifiez les logs Laravel :
```bash
tail -f storage/logs/laravel.log
```

2. Vérifiez les logs PHP :
```bash
tail -f /var/log/php8.2-fpm.log
```

3. Vérifiez les permissions :
```bash
chmod -R 775 storage/app/public
chown -R www-data:www-data storage
```

### FilePond n'apparaît pas

1. Vérifiez que les scripts sont chargés (ouvrez la console du navigateur)
2. Vérifiez qu'il n'y a pas d'erreurs JavaScript
3. Assurez-vous que le CSRF token est valide

## 📚 Ressources

- [FilePond Documentation](https://pqina.nl/filepond/)
- [Laravel Chunk Upload](https://github.com/pionl/laravel-chunk-upload)
- [Configuration PHP](https://www.php.net/manual/en/ini.core.php)

## 🔒 Sécurité

- ✅ Validation du type MIME
- ✅ Validation de la taille
- ✅ Protection CSRF
- ✅ Authentification requise
- ✅ Noms de fichiers sécurisés (sanitization)

## 💡 Améliorations Futures

- [ ] Support de la reprise d'upload après déconnexion (resumable)
- [ ] Compression vidéo automatique
- [ ] Génération de thumbnails automatique
- [ ] Upload vers le cloud (S3, Azure, etc.)
- [ ] File d'attente (Queue) pour les post-traitements
