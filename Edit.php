<?php 
include_once 'DAL/classesDB.php';
require_once 'imageHelper.php';


function effacerTables() {
    DB()->nonQuerySqlCmd('DROP TABLE Acteurs');
}
include_once 'utilities/form.php';

$id = $_GET['id'];
if(isset($_POST['edit']))
{
    //effacerTables();
    DB()->beginTransaction();

    $acteur['Name']=$_POST['Name'];  
    $acteur['Country']=$_POST['pays']; 
    $acteur['Birth']=$_POST['Birth'];
    $acteur['ActeurGUID'] = ImageHelper()->upLoadImage($_POST['ActeurGUID']);

    Acteurs()->update($acteur);

    DB()->endTransaction();
}

//header('Location:List.php');
//exit();
?>
