<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit'])){
        if (isset($_POST['Id'])) {
            Actors()->deleteFromForm();
        }
        redirect("listActors.php");
    }

$viewtitle = "Retrait d'acteur";
$viewContent =  Actors()->getDeleteHtmlForm($_GET["id"]);
include "view/master.php";
?>