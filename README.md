# kaemo_test_sf2
Test technique pour Kaemo

## Installation
+ Prérequis : PHP, MySQL, Git et Composer sont installé
+ Cloner le repository
+ Créer une base de données et un utilisateur

+ Installer les dépendances
```
composer install
```

+ Créer le modèle de données
```
bin/console doctrine:schema:create
```



## Utilisation
### Commandes :
+ Import depuis le fichier var/films.xml 
```
bin/console kaemo:movie:import
```
+ Ajouter un film manuellement
```
bin/console kaemo:movie:create
```

### Services REST

+ Liste des films 
/api/videos

+ Infos d'un film identifié par son ID
/api/videos/{id}

documentation disponible à /api/doc

