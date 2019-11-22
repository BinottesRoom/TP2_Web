<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit'])){
        if (isset($_POST['Id'])) {
            Movies()->deleteFromForm();
        }
        redirect("listMovies.php");
    }

$viewtitle = "Retrait d'un film";
$viewContent =  Movies()->getDeleteHtmlForm($_GET["id"]);
include "view/master.php";
?>