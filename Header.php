<?php
require_once 'utilities/htmlHelper.php';

    echo "<img src='images/favicon.ico'>";
echo html_open("div ");
    echo "<h1>Gestionnaire de favoris</h1>";
echo html_close("div");

if(isset($_COOKIE['Nom']))
{
    echo html_open("div ");
        echo "Visiteur: ";
        echo $_COOKIE['Nom'];
    echo html_close("div");
    echo html_open("div ");
        echo "Visites: ";
        echo $_COOKIE['NbVisites'];
    echo html_close("div");
}

if(isset($_SESSION['ValidUser']) && $_SESSION['ValidUser'] == true)
{
    echo html_open("div ");
        echo '<a href="Logout.php"><img src="images/Exit.png"></a>';
    echo html_close("div");
}

require_once 'MasterPage.php';
?>