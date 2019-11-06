<?php 
require 'SessionTimeOut.php';
session_start();
require 'VerificationAcessIllegalEtSessionExpiree.php';
include_once 'utilities/form.php';
require 'DAL/bookmarks.php';

unset($_SESSION['TitreInvalide']);
unset($_SESSION['DescriptionInvalide']);
unset($_SESSION['URLInvalide']);

if(isset($_POST['ajouter']))
{
    $titre = $_POST['Titre'];
    $description = $_POST['Description'];
    $url = $_POST['URL'];
    $fichier = "data/bookmarks.txt";

    $bookmarks['Id'] = $_SESSION['idFavoris'];
    $bookmarks['Title'] = sanitizeString($titre);
    $bookmarks['Description'] = sanitizeString($description);
    $bookmarks['Url'] = sanitizeString($url);
    $bookmarks['Source'] = $_COOKIE['Nom']; 

    $_SESSION['favorisValide'] = true;
    if(trim($bookmarks['Title']) == "" || $bookmarks['Title'] == null )
    {
        $_SESSION['favorisValide'] = false;
        $_SESSION['TitreInvalide'] = 'Le Titre est invalide';
    }
    else if(trim($bookmarks['Description']) == "" || $bookmarks['Description'] == null)
    {
        $_SESSION['favorisValide'] = false;
        $_SESSION['DescriptionInvalide'] = 'La description est invalide';
    }
    else if(trim($bookmarks['Url']) == "" || $bookmarks['Url']== null)
    {
        $_SESSION['favorisValide'] = false;
        $_SESSION['URLInvalide'] = 'L`url donnée est invalide';
    }

    if(!$_SESSION['favorisValide'])
    {
        header('AddForm.php');
        exit();
    }
    else
    {
        addBookmark($bookmarks);
    }

}

header('Location:List.php');
exit();
?>