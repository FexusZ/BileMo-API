# BileMo-API
## Projet n°7 OpenClassroom DAPHP
Réalisation d'une API REST PHP/Symfony (BileMo)

### Objectifs :
* Créer une application REST qui suit les 3 niveaux du modèle de maturité de Richardson.
  * Niveau 1 : Respect du modèle de données, chaque ressource peut être identifiée via une URL.
  * Niveau 2 : Utilisation des verbes HTTP (GET, POST, PUT...) et utilisation des codes status (200: OK, 201 Created....).
  * Niveau 3 : L'Api est auto-découvrable, elle utilise une logique hypermedia similaire à HTML par exemple. Cela permet aux utilisateurs d'obtenir les urls liées à une ressource.
* Création de trois ressource
  * Reseller : Revendeur partenaire de BileMo.
    * Création d'un nouveau revendeur.
    * Affichage des informations revendeur.
    * Suppression d'un compte Revendeur.
  * Product : Produits vendus par BileMo.
    * Affichage de la liste des produits.
    * Affichage du détail d'un produit.
  * User : Utilisateur lié à un revendeur.
    * Création d'un nouvel utilisateur lié à un revendeur.
    * Modification d'un utilisateur existant lié à un revendeur.
    * Suppression d'un utilisateur existant lié à un revendeur.
    * Affichage de la liste d'utilisateurs lié à un revendeur.
    * Affichage du détail d'un utilisateur lié à un revendeur.
* Application Stateless :
  * Le serveur ne garde pas en session requête et l'utilisateur, obligation de se reconnecter entre chaque appel, pour avoir une information liée à la requête précédente, il faut indiquer au serveur toutes les informations de la première requête.
### Langage utilisé ?
	* PHP 7.4.7, MySQL, Symfony
### Comment utiliser le projet ?
* Installation :
	* Importer le projet sur votre serveur
	* Telecharger composer, et faite un 'composer install', pour générer le vendor et l'autoloader de composer.
	* Modifier le fichier .env pour y mettre les informations de connexion à la base de données
	* Mettre en place la base de données :
		* Par ligne de commande, passer le serveur en mode développeur pour acceder au maker de symfony, faite un php bin/console ou symfony console make:migration, puis un doctrine:migrations:migrate, pour avoir un jeu de données de base vous pouvez aussi exécuter la commande "php bin/console doctrine:fixtures:load", vous pouvez repasser en environnement de production.
### Bundle utilisés
* ...
### Lien codeclimate
* <a href="https://codeclimate.com/github/FexusZ/BileMo-API/maintainability"><img src="https://api.codeclimate.com/v1/badges/d5132b08cc03eebf2b97/maintainability" /></a>
### V.1.0.0
* Initial release
