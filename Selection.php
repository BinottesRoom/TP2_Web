<?php
    //include_once 'DAL/models.php';
    include_once 'DAL/DBA.php';
    include_once 'utilities/selectionUtilities.php';

    // retourne un tableau d'items (id, Nom de pays) issue de la table Selections
    function CastsActorsToItems($movieId){
        $items=[];
        foreach(Casts()->selectWhere("Id = $movieId") as $cast){
            $actor = Actors()->get($cast['ActorId']);
            $items[$actor['Id']] = $actor['Name'];  
        }
        return $items;
    }

    function CastsMoviesToItems($actorId){
        $items=[];
        foreach(Casts()->selectWhere("Id = $actorId") as $cast){
            $movie = Movies()->get($cast['MoviesId']);
            $items[$movie['Id']] = $movie['Name'];  
        }
        return $items;
    }
   
     // retourne un tableau d'items (id, Nom de pays) issue de la table Countries
    function ActorsToItems(){
        $items = [];
         foreach(Actors()->get() as $actor){
            $items[$actor['Id']] = $actor['Name'];  
        }
        return $items;
    }

    function MoviesToItems(){
        $items = [];
         foreach(Movies()->get() as $movie){
            $items[$movie['Id']] = $movie['Name'];  
        }
        return $items;
    }

    // Met à jour la table Selections avec la liste des options sélectionnés du <select...>
    // provenant du formulaire
    function saveFormActorsSelection($selectedItemsId) {
        $selection['Id'] = 0;
        foreach($selectedItemsId as $idActors) {
            $selection['ActorId'] = $idActors;
            Casts()()->insert($selection);
        }
    }

    function saveFormMoviesSelection($selectedItemsId) {
        $selection['Id'] = 0;
        foreach($selectedItemsId as $idMovies) {
            $selection['MovieId'] = $idMovies;
            Casts()->insert($selection);
        }
    }


?>