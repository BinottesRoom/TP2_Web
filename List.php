<?php
include_once 'DAL/classesDB.php';
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

function showActeurs($acteurs) 
{
    $addToContent = "";
    if (isset($acteurs)) {
        $addToContent .= "<div class='bookmarks-row-layout'>";;
        foreach($acteurs as $acteur){
           
            $addToContent .= '<div>'.$acteur['Id'].'</div>';
            $addToContent .= '<div>'.$acteur['Name'].'</div>';
            $addToContent .= '<div>'.$acteur['Country'].'</div>';
            $addToContent .= '<div>'.$acteur['Birth'].'</div>';
            $addToContent .= "<div><a href='EditForm.php?'><img class='icon' src='images/icons/ICON_Edit_Neutral.png' alt='Ajouter'></a>
                                   <a href='DetailForm.php?'><img class='icon' src='images/icons/ICON_Details_Click.png' alt='Ajouter'></a>
                                   <a href='DeleteForm.php?'><img class='icon' src='images/icons/ICON_Delete_Neutral.png' alt='Ajouter'></a>
                             </div>";
        }
        $addToContent .= '</div>';
    }
    return $addToContent;
}


$content .= showActeurs(Acteurs()->get());

require_once "MasterPage.php";
?>