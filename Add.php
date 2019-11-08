<?php 
include_once 'DAL/classesDB.php';

function effacerTables() {
    DB()->nonQuerySqlCmd('DROP TABLE Acteurs');
}
include_once 'utilities/form.php';


if(isset($_POST['ajouter']))
{
    effacerTables();
    $acteur['Id']=0;
    DB()->beginTransaction();

    $acteur['Name']=$_POST['Name'];  
    $acteur['Country']=$_POST['Country']; 
    $acteur['Birth']=$_POST['Birth'];

    DB()->endTransaction();
}

header('Location:List.php');
exit();
?>

