<?php
    include_once 'DAL/models.php';
    include_once 'utilities/redirection.php';

$viewtitle = "Détails film";
$viewContent =  Movies()->getDetailsHtml($_GET["id"]);
include "view/master.php";
?>