<?php
include_once 'DAL/mySQL.php';

function showContacts($acteurs) {
    if (isset($acteurs)) {
        echo '<div>';
        foreach($acteurs as $acteur){
            echo '<div>';
            echo '<div>';
            echo '<div>'.$acteur['Id'].'</div>';
            echo '<div>'.$acteur['Name'].'</div>';
            echo '<div>'.$acteur['Phone'].'</div>';
            echo '<div>'.$acteur['Birth'].'</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}




require_once "MasterPage.php";
?>