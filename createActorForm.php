<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit'])){
        Actors()->createFromForm();
        redirect("listActors.php");
    }

$viewtitle = "Ajout d'acteur";
$viewContent =  Actors()->getHtmlForm();
$viewScript = "js/actorCreateFormValidation.js";
include "view/master.php";
?>