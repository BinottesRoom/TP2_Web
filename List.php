<?php
include_once 'DAL/mySQL.php';

class Acteur extends SQL_Helper {
    public $Id;
    /** VARCHAR(48) */
    public $Name;
    /** VARCHAR(30) */
    public $Country;
    /** Date */
    public $Birth;
    
    public function __construct() {
        $this->Id = 0;          
        $this->Name = '';
        $this->Country = '';
        $this->Birth = '';
    }
}

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