<?php
require 'utilities/htmlHelper.php';
require_once 'SessionTimeOut.php';
session_start();
require 'VerificationAcessIllegalEtSessionExpiree.php';

$TitreError = isset($_SESSION['TitreInvalide'])? $_SESSION['TitreInvalide'] : '';
$DescError = isset($_SESSION['DescriptionInvalide'])? $_SESSION['DescriptionInvalide'] : '';
$UrlError = isset($_SESSION['URLInvalide'])? $_SESSION['URLInvalide'] : '';

$content = "<div style=\"display:inline\">";
$content .= html_open("h3");
$content .="Ajout d'un acteur";
$content .= html_close("h3");
$content .= html_close("div");
$content .= "<hr>".html_open("div")."<form id='bookmarkForm' method='POST' action='Add.php'>";
$content .= html_open("b").html_label("Name", "Name").html_close("b");//titre
$content .="<br>";
$content .= html_textbox("Name", "Name");
$content .= "<br>".showError($TitreError)."<br>";
$content .= html_open("b").html_label("Countries", "Countries").html_close("b");//description
$content .= "<br>";
$content .= html_textbox("Countries", "Countries");
$content .= "<br>".showError($DescError)."<br>";
$content .= html_open("b").html_label("Birth", "Birth").html_close("b");//url
$content .= "<br>";
$content .= html_textbox("Birth", "Birth");
$content .= "<br>".showError($UrlError)."<br>";
$content .= html_submit("ajouter", "Ajouter");
$content .= html_close("form");
$content .= html_close("div");
$content .= html_close("hr");
$content .= "<a href='List.php'><img src='images/Back.png' alt='Retour en arrière'>".html_close("a");

include_once 'MasterPage.php';
?>
