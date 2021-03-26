# Sortir.com

## Introduction

La société ENI souhaite développer pour ses stagiaires actifs ainsi que ses anciens stagiaires
une plateforme web leur permettant d’organiser des sorties.
La plateforme est une plateforme privée dont l’inscription sera gérée par le ou les
administrateurs.
Les sorties, ainsi que les participants ont un site de rattachement, pour permettre une
organisation géographique des sorties.

## Technologies

* PHP 8
* Symfony 5 (Twig, Doctrine)
* MySQL 8
* Bootstrap 4

## Setup

Pour faire fonctionner ce projet, clonez le projet localement dans le repertoire dédié d'un service web.

Il faut ensiute créer une base de donnée Sortir (ou configurez un autre nom dans le fichier .env du projet) et ouvrir un terminal à la racine du projet.
Pensez à modifier votre utilisateur bdd au besoin (DATABASE_URL="mysql://<NOM_USER>:<PASSWORD>@127.0.0.1:3306/sortie?serverVersion=8.0&charset=utf8"


Utilisez la commande Composer pour installer les composants du projet:

> composer install

Utilisez la commande php doctrine pour mettre en place, et à jour la base de donnée:

> php bin/console doctrine:schema:update --complete

Finalement il faut executer le fichier "create_events.sql" dans la base de donnée.

Pour pouvoir se connecter et apporter des changements il sera nécessaire d'ajouer au moins un utilisateur administratif à la main dans la base de donnée. Dans le dossier "sql" à la racine du projet se trouve des jeux de données exemple.
