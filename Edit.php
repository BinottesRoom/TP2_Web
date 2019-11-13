<?php 
include_once 'DAL/classesDB.php';
require_once 'imageHelper.php';


function effacerTables() {
    DB()->nonQuerySqlCmd('DROP TABLE Acteurs');
}
include_once 'utilities/form.php';


if(isset($_GET['edit']))
{
    //effacerTables();
    DB()->beginTransaction();
    $acteur['Id']=$_GET['id'];
    $acteur['Name']=$_GET['Name'];  
    $acteur['Country']=$_GET['pays']; 
    $acteur['Birth']=$_GET['Birth'];
    //$fuckingGUID = ImageHelper()->upLoadImage($_GET['ActeurGUID']);
    $acteur['ActeurGUID'] = ImageHelper()->upLoadImage($_GET['ActeurGUID']);

    Acteurs()->update($acteur);

    DB()->endTransaction();
}

//header('Location:List.php');
//exit();
?>
