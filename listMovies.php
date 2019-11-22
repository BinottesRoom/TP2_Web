<?php
include_once "DAL/crudTableHelper.php";
include_once "DAL/models.php";

session_start();

if (!isset($_SESSION["MoviesCRUDTableHelper"])) {
    $MoviesCRUDTableHelper = new CRUDTableHelper(Movies());
    $MoviesCRUDTableHelper->setcaptionKey('Title');
                                      //clé      ce qu'on veut voir
    $MoviesCRUDTableHelper->addColumn("PosterGUID","Poster",false);
    $MoviesCRUDTableHelper->addColumn("Title","Titre",true);
    $MoviesCRUDTableHelper->addColumn("Author","Auteur",true);
    $MoviesCRUDTableHelper->addColumn("Year","Année",true);
    $MoviesCRUDTableHelper->addColumn("CountrieId","Pays",true);
    $MoviesCRUDTableHelper->addColumn("StyleId","Style",true);
    $_SESSION["MoviesCRUDTableHelper"] = $MoviesCRUDTableHelper;
}

$MoviesCRUDTableHelper = $_SESSION["MoviesCRUDTableHelper"];


if (isset($_GET['sortBy']))
    $MoviesCRUDTableHelper->setSortedKey($_GET['sortBy']);

$viewtitle = "Liste des films";
$viewContent =  $MoviesCRUDTableHelper->makeCRUD();

include "view/master.php";
?>
