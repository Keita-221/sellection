# Tech Repair Center Pro

Site vitrine dynamique pour un atelier de réparation et de vente de téléphones et d'ordinateurs. Les visiteurs consultent les offres ; les professionnels se connectent pour gérer leurs comptes et leurs sélections.

**Démo en ligne :** _à compléter avec le lien du site_

## Fonctionnalités
- Page d'accueil et présentation de la structure
- Catalogue de sélections (produits et services) avec page de détail
- Inscription des professionnels protégée par un code de validation
- Connexion / déconnexion avec gestion de session
- Espace d'administration : gestion des comptes et des sélections

## Technologies
PHP · MariaDB / MySQL (mysqli) · HTML · CSS · Bootstrap · JavaScript

## Structure
```
index.php        page d'accueil
vitrine/         pages publiques (sélections, détails)
inscription/     création de compte
connexion/       connexion, déconnexion, tableau de bord
bac/             administration (comptes, sélections)
config/          connexion à la base (db.php) et modèle de configuration
schema.sql       structure de la base + données fictives
```

## Installation
1. Crée une base MySQL/MariaDB et importe `schema.sql`.
2. Dans `config/`, copie `config.example.php` en `config.php` et renseigne tes identifiants.
3. Place le projet sur un serveur PHP (Apache, XAMPP…) et ouvre `index.php`.

## Comptes de démonstration
| Rôle | Email | Mot de passe initial |
|---|---|---|
| Administrateur | `admin@demo.fr` | `ChangeMe-2026` |
| Professionnel | `pro@demo.fr` | `ChangeMe-2026` |

Code d'inscription de démonstration : `demo1234`.
Change ces valeurs après l'import si le site est public.

## Sécurité
- Aucun identifiant n'est écrit dans le code : `config/config.php` est ignoré par Git.
- Les données du dépôt sont fictives.

## Auteur
Projet universitaire (Licence 2).
