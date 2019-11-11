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
    public $ActeurGUID;

    
    public function init() {
        $this->Id = 0;          
        $this->Name = '';
        $this->Country = '';
        $this->Birth = ''; 
        $this->ActeurGUID = '';
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
    return DataBaseAccess::getInstance('ActeursDB');
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
//InitCountries();
include_once 'DBA.php';
include_once 'imageHelper.php';

final class Photos extends TableAccess{
    public $Id;
    /** VARCHAR(128) */
    public $Title;
    public $PhotoGUID;
    public $_imageHelper;
      
    public function init() {
        $this->Id = 0;          
        $this->Title = ''; 
        $this->PhotoGUID = "";
        $this->_imageHelper = new ImageHelper('images','No_image.png'); 
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

    public function emptyPhoto() {
        $emptyPhoto['Id'] = 0;
        $emptyPhoto['Title'] = '';        
        $emptyPhoto['PhotoGUID'] = '';
        return $emptyPhoto;
    }

    public function htmlViewValues($photoRecord){
        $title = $photoRecord['Title'];
        $photoURL = $this->_imageHelper->getURL($photoRecord['PhotoGUID']);
        $htmlViewValues['Title'] = "<h4>$title</h4>";
        $htmlViewValues['PhotoGUID'] = "<img src='$photoURL' class='smallPhoto'>";
        return $htmlViewValues;
    }

    public function htmlForm($id = 0){
        $editMode = ($id !== 0 );
        $photoRecord = [];
        $html = "<form method='POST' enctype='multipart/form-data'>";
        if ($editMode) {
            $html.= "<input type='hidden' name ='Id' value='$id'>";
            $photoRecord = $this->get($id);
            $photoGUID = $photoRecord['PhotoGUID'];
            $html .= "<input type='hidden' name ='PhotoGUID' value='$photoGUID'>";
        } else {
            $photoRecord =  $this->emptyPhoto();
        }
        $html.= "<label for='Title'>Titre</label>";
        $html.= '<input name="Title" value="'.$photoRecord['Title'].'">'."<br>"."<br>";
        $html.= $this->_imageHelper->html_ImageUploader($photoRecord['PhotoGUID'])."<br>"."<br>";       
        $html.= "<input type='submit' name='Submit' value='Enregistrer'>";
        $html.= "</form>";
        return $html;
    }

    public function htmlDeleteForm($id){
        $photoRecord = $this->get($id);
        $htmlViewValues = $this->htmlViewValues($photoRecord);
        $html = $htmlViewValues['PhotoGUID']."<br>";
        $html .= $htmlViewValues['Title'];
        $html .= "<form method='POST' enctype='multipart/form-data'>";
            $html .= "<input type='hidden' name ='Id' value='$id'>"; 
            $photoGUID = $photoRecord['PhotoGUID']; 
            $html .= "<input type='hidden' name ='PhotoGUID' value='$photoGUID'>"; 
            $html.= "<input type='submit' name='Submit' value='Effacer'>";
        $html.= "</form>";
        return $html;
    }

    public function htmlList(){
        $photos = $this->get();
        $html = "<div class='photoListLayout'>";
        foreach($photos as $photo){
            $htmlViewValues = $this->htmlViewValues($photo);
            $html .= "<div>";
            $html .= $htmlViewValues['PhotoGUID']."<br>";
            $html .= $htmlViewValues['Title'];
            $id = $photo['Id'];
            $html .= "<a href='edit.php?id=$id'>Modifier</a>";
            $html .= "&nbsp; - &nbsp;";
            $html .= "<a href='delete.php?id=$id'>Effacer</a>";
            $html .= "</div>";
        }
        $html .= "</div>";
        return $html;
    }

    public function createFromForm(){
        if (isset($_POST['Submit'])){
            $newPhoto['Id'] = 0;
            $newPhoto['Title'] = $_POST['Title'];
            $newPhoto['PhotoGUID'] = $this->_imageHelper->upLoadImage();
            $this->insert($newPhoto);
        }
    }

    public function editFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $newPhoto['Id'] = $id;
            $newPhoto['Title'] = $_POST['Title'];
            $newPhoto['PhotoGUID'] = $this->_imageHelper->upLoadImage($_POST['PhotoGUID']);
            $this->update($newPhoto);
        }
    }

    public function deleteFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $this->_imageHelper->removeFile($_POST['PhotoGUID']);
            $this->delete($id);
        }
    }
}

// Fonctions d'accès aux singletons d'accès aux tables
function Photos() {
    return Photos::getInstance(DB());
}
?>