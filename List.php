<?php
include_once "DAL/bookmarks.php";
require 'utilities/htmlHelper.php';
require 'SessionTimeOut.php';
session_start();
require 'VerificationAcessIllegalEtSessionExpiree.php';

$_SESSION['idFavoris'] = 1;
$content = html_open("div").html_open("b")."Favoris".html_close("b").html_close("div")
.html_open("hr")
        ."<div class=' bookmarks-header-layout bookmark-Header'>"
                .html_open("div").
                    "Titre"
                .html_close("div")
                .html_open("div").
                    "Description"
                .html_close("div")
                .html_open("div").
                    "URL"
                .html_close("div")
                .html_open("div").
                    "Source"
                .html_close("div")
                .html_open("div")
                    ."<a href='AddForm.php'><img src='images/Add.png' alt='Ajouter'>".html_close("a")
                    .html_close("div")
                    .html_open("div")
                .html_close("div")
        .html_close("div")
    .html_close("hr");


$bookmarks = readBookmarks();
foreach($bookmarks as $ligne)
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

function getId($idImage)
{
    $_SESSION['idImage'] = $idImage;
}
unset($key);
include_once "MasterPage.php";
?>

    