<?php
include_once "DAL/crudTableHelper.php";
include_once "DAL/models.php";

session_start();

if (!isset($_SESSION["ActorsCRUDTableHelper"])) {
    $ActorsCRUDTableHelper = new CRUDTableHelper(Actors());
    $ActorsCRUDTableHelper->setcaptionKey('Name');
                                      //clé      ce qu'on veut voir
    $ActorsCRUDTableHelper->addColumn("PhotoGUID","Photo",false);
    $ActorsCRUDTableHelper->addColumn("Name","Nom",true);
    $ActorsCRUDTableHelper->addColumn("CountrieId","Pays",true);
    $ActorsCRUDTableHelper->addColumn("BirthDate","Naissance",true);
    $_SESSION["ActorsCRUDTableHelper"] = $ActorsCRUDTableHelper;
}

$ActorsCRUDTableHelper = $_SESSION["ActorsCRUDTableHelper"];


if (isset($_GET['sortBy']))
    $ActorsCRUDTableHelper->setSortedKey($_GET['sortBy']);

$viewtitle = "Liste des acteurs";
$viewContent =  $ActorsCRUDTableHelper->makeCRUD();

include "view/master.php";
?>
