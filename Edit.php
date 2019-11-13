<?php 
include_once 'DAL/classesDB.php';
require_once 'imageHelper.php';


function effacerTables() {
    DB()->nonQuerySqlCmd('DROP TABLE Acteurs');
}
include_once 'utilities/form.php';


if(isset($_POST['edit']))
{
    //effacerTables();
    DB()->beginTransaction();
    $acteur['Id']=$_POST['id'];
    $acteur['Name']=$_POST['Name'];  
    $acteur['Country']=$_POST['pays']; 
    $acteur['Birth']=$_POST['Birth'];
    //$fuckingGUID = ImageHelper()->upLoadImage($_GET['ActeurGUID']);
        $acteur['ActeurGUID'] = ImageHelper()->upLoadImage($_POST['GUIDselected']);

    Acteurs()->update($acteur);

    DB()->endTransaction();
}

header('Location:List.php');
exit();
?>
