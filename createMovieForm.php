<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit']))
    {
        Movies()->createFromForm();
        redirect("listMovies.php");
    }

$viewtitle = "Ajout de films";
$viewContent =  Movies()->getHtmlForm();
$viewScript = "js/actorCreateFormValidation.js";
include "view/master.php";
?>