
# Openclassrooms - Projet 5 - Créez votre premier blog en PHP
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/80001455acc04ea2aaae57cd656e13fb)](https://www.codacy.com/gh/Elisa-K/P5_Blog/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Elisa-K/P5_Blog&amp;utm_campaign=Badge_Grade)

Projet n°5 du parcours Openclassrooms "Développeur d'application PHP/Symfony" :
Création d'un blog en PHP orienté objet en utilisant une architecture MVC

## Pré-requis
- PHP 8.1 ou supérieur
- Composer
- Serveur web (Apache, mySQL, PHP)
- Visual Studio Code, PHPStorm, SublimText, ...

## Installation
Pour installer le projet, suivez les étapes suivantes :

1. Cloner le repository
```bash
  git clone https://github.com/Elisa-K/P5_Blog.git
```
2. Accèder au répertoire du projet
```bash
  cd P5_Blog
```
3. Installer les dépendances
```bash
  composer install
```
4. Créer la base de donnée en important le fichier `blog.sql` situé dans le dossier sql

5. Créer un fichier `.env` à la racine du projet et configurer les informations de connexion à votre base de données et de messagerie. Utiliser le fichier `.env.example` comme exemple.

```
# Base de données
DB_HOST="localhost"
DB_NAME="blog"
DB_CHARSET="utf8mb4"
DB_USER="root"
DB_PASSWORD="root"

# Messagerie
MAILER_HOST="smtp.example.com"
MAILER_USERNAME="example@mail.com"
MAILER_PASSWORD="1234"
MAILER_PORT=465
MAILER_RECIPIENT="example@mail.com"
```
6. Lancer le projet en créant un VirtualHost

## Utilisation
Connectez-vous avec le compte administrateur par défaut :
```
email : admin@admin.com
password : passwordAdmin
```
ou
avec le compte utilisateur par défaut :
```
email : user@user.com
password : passwordUser
```

## Dépendances utilisées
**Twig - DotEnv - TinyMCE - PHPMailer**
