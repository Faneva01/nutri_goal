# ====================================================
#        GUIDE GIT RAPIDE - NUTRI_GOAL
# ====================================================

# Projet GitHub :
# https://github.com/Faneva01/nutri_goal

# ====================================================
# 1. CLONER LE PROJET (UNE SEULE FOIS)
# ====================================================

git clone https://github.com/Faneva01/nutri_goal.git

cd nutri_goal


# ====================================================
# 2. SE METTRE SUR LA BRANCHE DEV
# ====================================================

git checkout dev

git pull origin dev

# ====================================================
# 3. CREER SA BRANCHE DE FONCTIONNALITE
# ====================================================

# ⚠ Toujours créer sa branche depuis dev

# Format recommandé :

# feature/nom-fonctionnalite

# Exemples :

git checkout -b feature/login

git checkout -b feature/dashboard

git checkout -b feature/recommandation-regime

git checkout -b feature/profil-utilisateur


# ====================================================
# 4. DEVELOPPER NORMALEMENT
# ====================================================

# Après modifications :

git add .

# Puis faire un commit :

git commit -m "Ajout système de login"

# Exemples de bons commits :

git commit -m "Ajout formulaire utilisateur"

git commit -m "Correction bug dashboard"

git commit -m "Ajout page recommandations"


# ====================================================
# 5. ENVOYER SA BRANCHE SUR GITHUB
# ====================================================

# Exemple :

git push -u origin feature/login


# ====================================================
# 6. CREER UN PULL REQUEST
# ====================================================

# Sur GitHub :

# feature/login  ----->  dev

# ⚠ Un autre membre doit vérifier et accepter le merge.


# ====================================================
# 7. METTRE A JOUR LE PROJET
# ====================================================

# Avant de commencer une nouvelle fonctionnalité :

git checkout dev

git pull origin dev


# ====================================================
# 8. COMMANDES UTILES
# ====================================================

# Voir les branches :

git branch


# Voir toutes les branches :

git branch -a


# Changer de branche :

git checkout nom_branche


# Télécharger les modifications :

git pull


# Envoyer les modifications :

git push


# ====================================================
# 9. REGLES IMPORTANTES
# ====================================================

# ✅ Toujours travailler sur une branche feature/...

# ✅ Toujours partir de dev

# ✅ Toujours faire :

git pull origin dev

# avant de commencer


# ✅ Faire des commits clairs


# ❌ Ne jamais travailler directement sur main

# ❌ Ne jamais push directement sur dev

# ❌ Ne jamais supprimer une branche sans accord


# ====================================================
# 10. STRUCTURE DES BRANCHES
# ====================================================

# main
#     → version stable et fonctionnelle

# dev
#     → développement principal

# feature/...
#     → fonctionnalités individuelles


# Exemples :

# feature/login

# feature/dashboard

# feature/recommandation-regime

# feature/profil-utilisateur


# ====================================================
# 11. WORKFLOW COMPLET
# ====================================================

# 1. Aller sur dev :

git checkout dev

# 2. Récupérer les dernières modifications :

git pull origin dev

# 3. Créer sa branche :

git checkout -b feature/login

# 4. Développer

# 5. Ajouter les modifications :

git add .

# 6. Commit :

git commit -m "Ajout système login"

# 7. Push :

git push -u origin feature/login

# 8. Faire un Pull Request :
# feature/login -----> dev


# ====================================================