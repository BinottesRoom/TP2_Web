<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    
    if (isset($_POST['Submit'])){
        Actors()->editFromForm();
        redirect("listActors.php");
    }
    $id = 0;
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
    } else {
        redirect("listActors.php");
    }
    $viewtitle = "Modification d'acteur";
    $viewContent =  Actors()->getHtmlForm($id);
    $viewScript = "js/actorEditFormValidation.js";
    include "view/master.php";
    ?>