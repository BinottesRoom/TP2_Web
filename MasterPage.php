<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TP1_Web_J.C._I.L.</title>
    <style>
    .Photo {
        width:300px;
    }
    .smallPhoto {
        width:150px;
    }
    .photoListLayout {
        display: grid;
        grid-gap: 20px;
        grid-template-columns: repeat(auto-fill, 220px );
    }
    a {
        color:blue;
    }
</style>
    <?php require 'AllCssLink.php';
     require 'AllJsLinks.php'; ?>
</head>
<body> 
    <div class="main">
<header class=" header-layout mainHeaderCell">
    <?php 
    require_once 'Header.php';
    ?>
</header>
<main class="">
<div class = 'section'>
    <?php
    echo $content;
    ?>
</div>
</main>
<footer class="footer-layout">
    <?php 
    require_once 'Footer.php';
    ?>
</footer>  
</div>
</body>
</html>
