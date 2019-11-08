<?php
include_once 'DBA.php';

// Singletons d'accès aux tables
final class Acteurs extends TableAccess{
    public $Id;
    /** VARCHAR(50) */
    public $Name;
    /** VARCHAR(50) */
    public $Country;
    /** DATE */
    public $Birth;
    
    public function init() {
        $this->Id = 0;          
        $this->Name = '';
        $this->Country = '';
        $this->Birth = ''; 
    }

    public function __construct($dataBaseAccess) {
        parent::__construct($dataBaseAccess);
    }

    /// Cette portion ajoute la fonctionnalité "Singleton"//////
    private static $_instance = null;
    public static function getInstance($dataBaseAccess) {
        if(is_null(self::$_instance)) {
            $calledClass = get_called_class();
            self::$_instance = new $calledClass($dataBaseAccess);  
        }
        return self::$_instance;
    }
    ///////////////////////////////////////////////////////////
}
final class Countries extends TableAccess{
    public $Id;
    /** VARCHAR(128) */
    public $NameFR;
    /** VARCHAR(128) */
    public $NameEN;
    
    public function init() {
        $this->Id = 0;          
        $this->NameFR = '';   
        $this->NameEN = '';   
    }

    public function __construct($dataBaseAccess) {
        parent::__construct($dataBaseAccess);
    }

    /// Cette portion ajoute la fonctionnalité "Singleton"//////
    private static $_instance = null;
    public static function getInstance($dataBaseAccess) {
        if(is_null(self::$_instance)) {
            $calledClass = get_called_class();
            self::$_instance = new $calledClass($dataBaseAccess);  
        }
        return self::$_instance;
    }
    ///////////////////////////////////////////////////////////
}

// Singleton d'accès à la base de données
function DB() {
    return DataBaseAccess::getInstance('AuteursDB');
}

// Fonctions d'accès aux singletons d'accès aux tables
function Acteurs() {
    return Acteurs::getInstance(DB());
}
function Countries() {
    return Countries::getInstance(DB());
}

function InitCountries() {
    DB()->nonQuerySqlCmd('DROP TABLE Countries');
    $file = "DAL/Countries.txt";
    $fileHandle = fopen($file,'r');
    // Boucle de lecture
    DB()->beginTransaction();
    $country['Id']=0;
    while (!feof($fileHandle)){
        $fields = explode('|', fgets($fileHandle));
        $country['NameFR'] = $fields[4];        
        $country['NameEN'] = $fields[5];
        Countries()->insert($country);
    }
    DB()->endTransaction();
    fclose($fileHandle);
}

// Décommenter la ligne suivante pour transporter les données du fichier texte Countries.txt
// dans la table Countries de la BD DBM
InitCountries();
?>