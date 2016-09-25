# WacKeep

Le but de ce projet était de réaliser un "GoogleKeeper like".

### Installation

```
composer install
npm install
bower install
php artisan migrate --force
```

Ne pas oublier les variables d'environnements dans le fichier **.env**

### Informations

J'ai utilisé un plugin jQuery ([Isotope](http://isotope.metafizzy.com/)) car je vais surement mettre ce projet sur mon portfolio et 
je souhaitais avoir la meilleur ergonomie possible. (Seulement pour le positionnement des divs)

Au cas ou, "algo"(lol) sur quatre columns (div=400px):

```
EVENT ADD/UPDATE NOTE
    -> MET EN CACHE LES DIVS (CLONE)
    -> BOUCLE SUR LES DIVS 
        -> RECUPERE LA HEIGHT DE TOUTES LES COLONNES
        -> SI PLUSIEURS COLUMN AVEC LA MEME HEIGHT (0)
            -> APPEND A LA COLLONE AVEC L'INDEX LE PLUS PETIT
        -> SINON
            -> APPEND LA NOTE A LA PLUS PETITE COLONNE
```

Ouais je sais, tu te demandes ce que je fais à la wac et pas dans une école d'ingé lol.


