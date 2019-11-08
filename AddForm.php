<?php
require 'utilities/htmlHelper.php';
session_start();

$TitreError = isset($_SESSION['TitreInvalide'])? $_SESSION['TitreInvalide'] : '';
$DescError = isset($_SESSION['DescriptionInvalide'])? $_SESSION['DescriptionInvalide'] : '';
$UrlError = isset($_SESSION['URLInvalide'])? $_SESSION['URLInvalide'] : '';

$content = "<div style=\"display:inline\">";
$content .= html_open("h3");
$content .="Ajout d'un acteur";
$content .= html_close("h3");
$content .= html_close("div");
$content .= "<hr>".html_open("div")."<form id='bookmarkForm' method='POST' action='Add.php'>";
$content .= html_open("b").html_label("Name", "Name").html_close("b");
$content .="<br>";
$content .= html_textbox("Name", "Name");
$content .= html_open("b").html_label("Country", "Country").html_close("b");
$content .= "<br>";
$content .= html_textbox("Country", "Country");
$content .= html_open("b").html_label("Birth", "Birth").html_close("b");
$content .= "<br>";
$content .= html_textbox("Birth", "Birth");
$content .= html_submit("ajouter", "Ajouter");
$content .= html_close("form");
$content .= html_close("div");
$content .= html_close("hr");
$content .= "<a href='List.php'><img src='images/Back.png' alt='Retour en arrière'>".html_close("a");

include_once 'MasterPage.php';
?>
