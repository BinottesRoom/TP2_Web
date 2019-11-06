<?php
    session_start();
    
    require 'imageHelper.php';
    $imageHelper = new ImageHelper('images','No_image.png') ;
    $lastGUID = isset($_SESSION['lastGUID']) ? $_SESSION['lastGUID']:'';
    $html_imageUpLoader = $imageHelper->html_ImageUploader($lastGUID);

    if (isset($_POST['submit'])) {  
        $_SESSION['lastGUID'] = $imageHelper->upLoadImage('');
    }
?>

<!doctype html>
<html>
<header>
<link   rel="stylesheet" 
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
		crossorigin="anonymous">
<!-- Style pour les infobulles -->
<link rel="stylesheet" href="tooltip.css">
<style>
    .Photo {
        border-radius:8px;
        padding:12px;
        border:1px solid blue;
        margin:20px;
        width:200px;
    }
</style>
</header>
<body>
<form method='POST' enctype="multipart/form-data">
    <?php echo $html_imageUpLoader; ?> <br><br>
    <input type='submit' name='submit' value='submit'>
</form>
<script src='jquery-3.3.1.js'> </script>
<script src='imageUpLoader.js'></script>
</body>
</html>