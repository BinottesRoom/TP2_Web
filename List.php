<?php
include_once 'DAL/mySQL.php';
require 'utilities/htmlHelper.php';



$content = html_open("div").html_open("b")."Favoris".html_close("b").html_close("div")
.html_open("hr")
        ."<div class=' bookmarks-header-layout bookmark-Header'>"
                .html_open("div").html_close("div")
                .html_open("div").
                    "Nom"
                .html_close("div")
                .html_open("div").
                    "Pays"
                .html_close("div")
                .html_open("div").
                    "Naissance"
                .html_close("div")
                .html_open("div")
                    ."<a href='AddForm.php'><img class='icon' src='images/icons/ICON_Add_Neutral.png' alt='Ajouter'>".html_close("a")
                .html_close("div")
        .html_close("div")
    .html_close("hr");

    foreach($ItemsInBD as $ligne)
    {
        $content .= "<div class='bookmarks-row-layout' >";
        $_SESSION['idFavoris']++;
        
        foreach($ligne as $key2 => $value)
        {
            
            if ($ligne["Id"] != $value)
            {
                if($value == $ligne['Url'])
                {
                    $content .= html_open("div");
                    $content .= "<a href='$value'><img src='https://s2.googleusercontent.com/s2/favicons?domain=$value'>$value</a>";
                    $content .= html_close("div");
                }
                else
                {
                    $content .= html_open("div");
                    $content .= $value;
                    $content .= html_close("div");
                }
            }
    
        }
    
        $id = $ligne['Id'];
        $content .= html_open("div")."<a href='EditForm.php?id=$id'><img src='images/Modify.png' alt='Ajouter'>".html_close("a").html_close("div");
        $content .= html_open("div")."<a href='DeleteForm.php?id=$id'><img src='images/Erase.png' alt='Effacer'>".html_close("a").html_close("div");
        $content .= html_close("div");
        $content .= "<form method='post' action='DeleteForm.php'></form>";
        unset($value);
    }

function showActeurs($acteurs) {
    if (isset($acteurs)) {
        echo '<div>';
        foreach($acteurs as $acteur){
            echo '<div>';
                echo '<div></div>';
                echo '<div>'.$acteur['Id'].'</div>';
                echo '<div>'.$acteur['Name'].'</div>';
                echo '<div>'.$acteur['Country'].'</div>';
                echo '<div>'.$acteur['Birth'].'</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}

require_once "MasterPage.php";
?>