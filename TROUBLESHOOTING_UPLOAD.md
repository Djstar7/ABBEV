# 🔧 Dépannage - Erreur "File is too large"

## ✅ Correction Appliquée

Les limites FilePond ont été augmentées de **10MB à 500MB** pour :
- Thumbnail (vignette)
- Cover (couverture)
- Banner (bannière)
- Vidéos : **5GB** (inchangé)

## 🚀 Actions à Faire IMMÉDIATEMENT

### 1. Recharger la Page (IMPORTANT)
Appuyez sur **Cmd + Shift + R** (Mac) pour vider le cache et recharger.

Ou :
- **Chrome/Edge** : Cmd + Shift + R
- **Firefox** : Cmd + Shift + R
- **Safari** : Cmd + Option + R

### 2. Vérifier Quel Fichier Vous Uploadez

#### Pour une **IMAGE** (thumbnail, cover, banner) :
- ✅ Taille max : **500MB**
- ✅ Formats : JPG, PNG, WEBP, etc.

#### Pour une **VIDÉO** :
- ✅ Taille max : **5GB**
- ✅ Formats : MP4, MKV, AVI, WEBM
- ✅ Upload par chunks (automatique)

### 3. Si l'Erreur Persiste

#### Option A : Vérifier dans la Console du Navigateur
1. Appuyez sur **F12** ou **Cmd + Option + I**
2. Allez dans l'onglet **Console**
3. Cherchez les erreurs en rouge
4. Envoyez-moi le message d'erreur exact

#### Option B : Vérifier le Type de Fichier
```
Assurez-vous que :
- Images → Champs thumbnail/cover/banner
- Vidéos → Champ vidéo
```

#### Option C : Redémarrer le Serveur PHP (si local)
```bash
# Arrêtez le serveur (Ctrl+C)
# Puis relancez :
php artisan serve
```

## 📊 Configuration Actuelle

| Type de Fichier | Taille Max | Upload par Chunks |
|-----------------|------------|-------------------|
| Vidéo           | 5GB        | ✅ Oui (2MB/chunk) |
| Image (thumb)   | 500MB      | ❌ Non             |
| Image (cover)   | 500MB      | ❌ Non             |
| Image (banner)  | 500MB      | ❌ Non             |

## 🔍 Diagnostic Rapide

### Testez avec un Petit Fichier (10MB)
1. Uploadez d'abord une petite vidéo (10MB)
2. Si ça marche → Le problème était la limite FilePond (maintenant corrigée)
3. Si ça ne marche pas → Autre problème (voir ci-dessous)

### Vérifier la Configuration PHP
```bash
php -i | grep -E "upload_max_filesize|post_max_size"
```

Devrait afficher :
```
upload_max_filesize => 5120M
post_max_size => 5120M
```

## 🆘 Si Rien ne Fonctionne

### Essayez ceci :

1. **Videz le cache du navigateur complètement** :
   - Chrome : Paramètres → Confidentialité → Effacer les données de navigation → Images et fichiers en cache
   - Safari : Développement → Vider les caches

2. **Essayez un autre navigateur** :
   - Chrome
   - Firefox
   - Safari

3. **Vérifiez que JavaScript est activé**

4. **Désactivez les extensions de navigateur** (AdBlock, etc.)

## 💡 Astuce : Upload Manuel via Backend

Si FilePond pose toujours problème, vous pouvez temporairement désactiver FilePond et utiliser l'upload classique HTML :

### Modification Temporaire

Dans `media/create.blade.php`, cherchez :
```html
<input type="file" name="video" id="video" accept="video/*" data-max-file-size="5GB">
```

Et commentez le script FilePond en bas du fichier :
```javascript
// const videoPond = FilePond.create(...);
```

Cela utilisera l'upload HTML classique (sans progress bar, mais fonctionnel).

## 📞 Besoin d'Aide ?

Si l'erreur persiste après avoir :
- ✅ Rechargé la page (Cmd + Shift + R)
- ✅ Testé avec un petit fichier
- ✅ Vérifié la console (F12)

Envoyez-moi :
1. Le message d'erreur exact
2. La taille du fichier que vous tentez d'uploader
3. Le type de fichier (vidéo ou image ?)
4. Sur quelle page (/media/create ou /episodes/create ?)
5. Capture d'écran de l'erreur
