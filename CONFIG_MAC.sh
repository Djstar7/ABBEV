#!/bin/bash

# Script de configuration PHP pour Mac - Upload de gros fichiers (5GB)
# Ce script configure automatiquement PHP sur macOS pour supporter les uploads jusqu'à 5GB

echo "🚀 Configuration PHP pour upload de gros fichiers sur Mac..."
echo ""

# Couleurs pour l'affichage
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Détecter le fichier PHP.ini
PHP_INI=$(php --ini | grep "Loaded Configuration File" | cut -d: -f2 | xargs)
PHP_CONF_DIR=$(php --ini | grep "Scan for additional" | cut -d: -f2 | xargs)

echo -e "${BLUE}📍 Fichier PHP.ini détecté : ${NC}$PHP_INI"
echo -e "${BLUE}📍 Dossier conf.d : ${NC}$PHP_CONF_DIR"
echo ""

# Créer le fichier uploads.ini avec la nouvelle configuration
UPLOADS_INI="$PHP_CONF_DIR/uploads.ini"

echo -e "${BLUE}✏️  Création/Modification de $UPLOADS_INI${NC}"
echo ""

# Créer le contenu du fichier
cat > "$UPLOADS_INI" << 'EOF'
; Configuration pour les uploads de gros fichiers (jusqu'à 5GB)
; Modifié pour le projet ABBEV Movie Dashboard

upload_max_filesize = 5120M
post_max_size = 5120M
max_execution_time = 3600
max_input_time = 3600
memory_limit = 512M
EOF

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Fichier $UPLOADS_INI créé avec succès${NC}"
else
    echo -e "${RED}❌ Erreur lors de la création du fichier${NC}"
    echo -e "${RED}Essayez avec sudo :${NC}"
    echo -e "sudo bash CONFIG_MAC.sh"
    exit 1
fi

echo ""
echo -e "${BLUE}📋 Configuration appliquée :${NC}"
cat "$UPLOADS_INI"
echo ""

# Vérifier la configuration
echo -e "${BLUE}🔍 Vérification de la configuration PHP :${NC}"
echo ""
php -i | grep -E "upload_max_filesize|post_max_size|max_execution_time" | head -3
echo ""

# Instructions pour redémarrer les services
echo -e "${GREEN}✅ Configuration terminée !${NC}"
echo ""
echo -e "${BLUE}📌 Prochaines étapes :${NC}"
echo ""

# Détecter si Valet, Herd ou serveur manuel
if command -v valet &> /dev/null; then
    echo -e "  ${BLUE}Laravel Valet détecté.${NC} Redémarrez avec :"
    echo -e "  ${GREEN}valet restart${NC}"
    echo ""
elif command -v herd &> /dev/null; then
    echo -e "  ${BLUE}Laravel Herd détecté.${NC} Redémarrez l'application Herd"
    echo ""
elif pgrep -x "php" > /dev/null; then
    echo -e "  ${BLUE}Serveur PHP détecté.${NC} Redémarrez avec :"
    echo -e "  ${GREEN}# Arrêtez le serveur actuel (Ctrl+C)${NC}"
    echo -e "  ${GREEN}php artisan serve${NC}"
    echo ""
else
    echo -e "  ${BLUE}Démarrez votre serveur :${NC}"
    echo -e "  ${GREEN}php artisan serve${NC}"
    echo ""
fi

echo -e "${BLUE}🧪 Pour tester :${NC}"
echo "  1. Démarrez votre serveur"
echo "  2. Allez sur http://localhost:8000/media/create"
echo "  3. Uploadez un gros fichier"
echo ""

echo -e "${GREEN}✨ Configuration Mac terminée avec succès !${NC}"
