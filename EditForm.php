<?php
require 'utilities/htmlHelper.php';
require 'AutresFct.php';
require_once 'SessionTimeOut.php';
session_start();
require 'VerificationAcessIllegalEtSessionExpiree.php';
include_once "DAL/bookmarks.php";


$TitreError = isset($_SESSION['TitreInvalide'])? $_SESSION['TitreInvalide'] : '';
$DescError = isset($_SESSION['DescriptionInvalide'])? $_SESSION['DescriptionInvalide'] : '';
$UrlError = isset($_SESSION['URLInvalide'])? $_SESSION['URLInvalide'] : '';

    
$id = $_GET['id'];
$fd = findBookmark($id);
//$_SESSION['idEdit'] = $id;

if($fd['Source'] != $_COOKIE['Nom'])
{
    header('LoginForm.php');
    exit();
}

$content = "<div style=\"display:inline\">";
$content .= html_open("h3");
$content .="Modification de favori";
$content .= html_close("h3");
$content .= html_close("div");
$content .= "<hr>".html_open("div")."<form id='bookmarkForm' method='POST' action='Edit.php'>";
$content .= html_open("b").html_label("Titre", "Titre").html_close("b");//titre
$content .="<br>";
$content .= html_textbox("Titre", "Titre", $fd['Title']);
$content .= "<br>".showError($TitreError)."<br>";
$content .= html_open("b").html_label("Description", "Description").html_close("b");//description
$content .= "<br>";
$content .= html_textbox("Description", "Description", $fd['Description']);
$content .= "<br>".showError($DescError)."<br>";
$content .= html_open("b").html_label("URL", "URL").html_close("b");//url
$content .= "<br>";
$content .= html_textbox("URL", "URL", $fd['Url']);
$content .= "<br>".showError($UrlError)."<br>";
$content .= html_submit("modifier", "Modifier");
$content .= html_close("form");
$content .= html_close("div");
$content .= html_close("hr");
$content .= "<a href='List.php'><img src='images/Back.png' alt='Retour en arrière'>".html_close("a");


include_once 'MasterPage.php';
?>