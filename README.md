Application Vie Presque de Merde
========================

Cette application permet de récupérer en ligne de commande un nombre de post sur le site Vie de merde.
Elle expose en rest les posts récupérés.

Installation
--------------

Prérequis:

  * Composer

  * PHPUnit

Après avoir récupérer les sources:

  * Exécuter un composer update pour télécharger les librairies requises

  * Modifier les informations base de données /app/config/parameters.yml

  * Créer la base de donnée : app/console doctrine:database:create

  * Créer les table : app/console doctrine:schema:update

Récupérer les posts
-------------------

  * Exécuter la commande : app/console vdm:update

  * Choisissez le nombre de posts que vous souhaitez récupérer

  * Vous serez informé sur le nombre de posts ajouté ou mis à jour

Afficher les posts récupérés
----------------------------

  * Affichage de tous les posts : saisir en complément de l'url de votre projet sur votre serveur /api/posts

  * Affichage d'un post : saisir en complément de l'url de votre projet sur votre serveur /api/posts/1 pour le post dont l'id est 1

  * Affichage des posts d'un auteur : saisir en complément de l'url de votre projet sur votre serveur /api/posts?author=Genius

  * Affichage des posts entre deux date : saisir en complément de l'url de votre projet sur votre serveur /api/posts?from="date"&to="date"

  * Vous pouvez cumuler les paramètres pour affiner votre recherche

Exécution des tests unitaire
----------------------------

  * Exécution de la commande : phpunit --verbose  -c app/