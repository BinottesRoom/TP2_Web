<?php
include_once 'DAL/DBA.php';
require_once 'DAL/classesDB.php';
require 'utilities/htmlHelper.php';
require_once 'imageHelper.php';

$id = $_GET['id'];
$acteur = Acteurs()->get($id);
$nom = $acteur['Name'];
$date = $acteur['Birth'];
$country = $acteur['Country'];
$url = ImageHelper()->getURL($acteur['ActeurGUID']);

$content = "<div style=\"display:inline\">";
$content .= html_open("h3");
$content .="Modifier $nom";
$content .= html_close("h3");
$content .= html_close("div");
$content .= "<hr>".html_open("div")."<form id='bookmarkForm' method='POST' action='Edit.php' enctype='multipart/form-data'>";
$content .= html_open("b").html_label("Name", "Name").html_close("b");
$content .="<br>";
$content .= html_textbox("Name", "Name", $nom)."<br>";

$content .= html_open("b").html_label("Country", "Country").html_close("b");
$content .= "<br>";
$content .= "<select name='pays'>";
$paysAAfficher = Countries()->get();
DB()->beginTransaction();
foreach($paysAAfficher as $pays)
{
    if ($pays[1] != $country)
    {
        $content .="<option value='$pays[1]'>$pays[1]</option>";
    }
    else
    {
        $content .="<option value='$country>$country</option>";
    }
    
}
DB()->endTransaction();
$content .= "</select>"."<br>";
$content .= html_open("b").html_label("Birth", "Birth").html_close("b");
$content .= "<br>";
$content .= "<input type='date' name='Birth' value='$date'>";
$content .= html_open('div', 'divPhoto');
$content .= imageHelper()->html_ImageUploader($url);
$content .= html_close('div');
$content .= html_submit("edit", "Modifier");       
$content .= html_close("form");
$content .= html_close("div");
$content .= html_close("hr");
$content .= "<a href='List.php'><img src='images/icons/ICON_Left_Click.png' alt='Retour en arrière'>".html_close("a");

include_once 'MasterPage.php';
?>