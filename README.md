# Tech Repair Center Pro

Site vitrine dynamique pour un atelier de réparation et de vente de téléphones et d'ordinateurs. Les visiteurs consultent les offres et les services ; les professionnels se connectent pour gérer leur espace, et un administrateur gère les comptes et les sélections.

**Démo en ligne :** [https://techservices.wuaze.com](https://techservices.wuaze.com/)

> Projet universitaire (Licence 2).

---

## Sommaire
1. [Fonctionnalités](#fonctionnalités)
2. [Technologies](#technologies)
3. [Structure du projet](#structure-du-projet)
4. [Base de données](#base-de-données)
5. [Rôles et accès](#rôles-et-accès)
6. [Sécurité](#sécurité)
7. [Pistes d'amélioration](#pistes-damélioration)
8. [Auteur](#auteur)

---

## Fonctionnalités

**Partie publique**
- Page d'accueil avec la présentation de la structure (nom, adresse, contact, horaires, texte de bienvenue)
- Présentation des services : réparation de téléphones, réparation d'ordinateurs, etc.
- Catalogue de **sélections** (regroupements de produits et de services) avec page de détail pour chaque offre
- Affichage des actualités

**Comptes et connexion**
- Inscription des professionnels, protégée par un **code de validation**
- Connexion et déconnexion avec gestion de session PHP
- Un compte doit être validé pour pouvoir se connecter

**Espace d'administration**
- Tableau de bord avec le profil de l'utilisateur connecté
- Gestion des comptes (réservée à l'administrateur)
- Création et suppression de sélections

---

## Technologies

| Domaine | Outils |
|---|---|
| Back-end | PHP (mysqli) |
| Base de données | MariaDB / MySQL |
| Front-end | HTML, CSS, JavaScript |
| Bibliothèques | Bootstrap, jQuery, Font Awesome |

---

## Structure du projet

```
.
├── index.php            Page d'accueil
├── vitrine/             Pages publiques : liste des sélections, détail d'une offre
├── inscription/         Formulaire et traitement de l'inscription
├── connexion/           Connexion, déconnexion, tableau de bord
├── bac/                 Administration : comptes et sélections
├── config/              Connexion à la base de données
├── css/  js/  img/      Feuilles de style, scripts et images du thème
├── fontawesome/         Icônes
└── ressources/          Images des produits et services
```

---

## Base de données

La base contient 8 tables, toutes préfixées `t_` et suffixées par un code de 3 lettres.

| Table | Rôle |
|---|---|
| `t_compte_cpt` | Comptes : email et mot de passe haché |
| `t_profil_pro` | Profil du professionnel : nom, prénom, validité du compte, statut, date de création |
| `t_presentation_pre` | Présentation de la structure : nom, adresse, email, téléphone, horaires, texte d'accueil, code de validation d'inscription, image |
| `t_selection_sel` | Sélections : intitulé, texte introductif, date, état |
| `t_element_elt` | Éléments (produits et services) : intitulé, descriptif, image, date, état |
| `t_rassemblement_ras` | Association entre sélections et éléments (relation plusieurs-à-plusieurs) |
| `t_lien_lin` | Liens rattachés à un élément : titre, URL, auteur, date de publication |
| `t_actualite_act` | Actualités : titre, texte, date de publication, état |

**Relations principales**
- Une sélection regroupe plusieurs éléments, et un élément peut appartenir à plusieurs sélections (`t_rassemblement_ras`).
- Un compte possède un profil (`t_profil_pro`) et peut créer des sélections et des actualités.
- Un élément peut avoir plusieurs liens (`t_lien_lin`).

---

## Rôles et accès

| Profil | Accès |
|---|---|
| Visiteur | Pages publiques, inscription, connexion |
| Professionnel connecté | Tableau de bord, gestion de ses sélections |
| Administrateur | Tout ce qui précède + gestion des comptes |

- Un professionnel peut se connecter seulement si son compte est validé.
- Les pages d'administration redirigent vers la connexion si l'utilisateur n'a pas le bon rôle.

---

## Sécurité

- Aucun identifiant de base de données n'est écrit dans le code source : ils sont lus dans un fichier de configuration non versionné.
- Les pages d'administration vérifient le rôle de l'utilisateur avant d'afficher quoi que ce soit.
- Le dépôt ne contient aucune donnée réelle.

---

## Pistes d'amélioration

- Remplacer MD5 par `password_hash()` / `password_verify()` pour les mots de passe
- Utiliser des requêtes préparées pour toutes les requêtes SQL
- Ajouter une protection CSRF sur les formulaires
- Regrouper les pages de sélection qui se ressemblent (`sel.php`, `select.php`, `selection.php`)

---

## Auteur

Projet universitaire (Licence 2) – Ibrahima Keita
