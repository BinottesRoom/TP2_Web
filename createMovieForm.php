<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit']))
    {
        Movies()->createFromForm();
        redirect("listActors.php");
    }

$viewtitle = "Ajout de films";
$viewContent =  Movies()->getHtmlForm();
$viewScript = "js/actorCreateFormValidation.js";
include "view/master.php";
?>