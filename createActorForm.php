<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';
    if (isset($_POST['Submit']))
    {
        DB()->beginTransaction();
        Selections()->deleteAll();//-----------------
        if (isset($_POST['SelectedItems']))
            saveFormSelection($_POST['SelectedItems']);
        DB()->endTransaction();
        Actors()->createFromForm();
        redirect("listActors.php");
    }

$viewtitle = "Ajout d'acteur";
$viewContent =  Actors()->getHtmlForm();
$viewScript = "js/actorCreateFormValidation.js";

// produire les items qui seront présentés dans les <select...> du formulaire
$selectedItems = CastsActorsToItems();
$movieItems = countriesToItems($selectedItems);

// pruduction du html constituant le formulaire avec les deux <select...>
$viewContent="";  
$viewContent .= "<form method='post'>";
    $viewContent .= "<table>";
        $viewContent .= "<tr>";
            $viewContent .= "<td>";
                $viewContent .= makeSelectedList($selectedItems);
            $viewContent .= "</td>";
            $viewContent .= "<td>";
                // il faut utiliser le fichier css/flashButtons.css pour 
                // que les <div> de classes MoveLeft et MoveRight se comportent
                // comme des boutons flash
                $viewContent .= "<div id='MoveLeft'  ></div>"; 
                $viewContent .= "<div id='MoveRight' ></div>";
            $viewContent .= "</td>";
            $viewContent .= "<td>";
                $viewContent .= makeUnselectedList($movieItems, $selectedItems);
            $content .= "</td>";
        $viewContent .= "</tr>";
    $viewContent .= "</table>";
    $viewContent .= "<input type='submit' name='submit' value='Enregistrer'>";
$viewContent .= "</form>";
include "view/master.php";
?>