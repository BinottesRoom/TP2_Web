<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    
    if (isset($_POST['Submit'])){
        Movies()->editFromForm();
        redirect("listMovies.php");
    }
    $id = 0;
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
    } else {
        redirect("listMovies.php");
    }
    $viewtitle = "Modification de film";
    $viewContent =  Movies()->getHtmlForm($id);
    $viewScript = "js/movieEditFormValidation.js";
    include "view/master.php";
    ?>