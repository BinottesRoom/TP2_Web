<?php 
include_once 'DAL/classesDB.php';
require_once 'imageHelper.php';


function effacerTables() {
    DB()->nonQuerySqlCmd('DROP TABLE Acteurs');
}
include_once 'utilities/form.php';


if(isset($_POST['ajouter']))
{
    //effacerTables();
    DB()->beginTransaction();

    $acteur['Name']=$_POST['Name'];  
    $acteur['Country']=$_POST['pays']; 
    $acteur['Birth']=$_POST['Birth'];
    $acteur['ActeurGUID'] = ImageHelper()->upLoadImage($_POST['ActeurGUID']);

    Acteurs()->insert($acteur);

    DB()->endTransaction();
}

header('Location:List.php');
exit();
?>

