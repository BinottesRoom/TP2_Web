<?php
include_once 'DBA.php';
include_once 'imageHelper.php';
include_once 'utilities/htmlHelper.php';
include_once 'utilities/selectionUtilities.php';

// Singletons d'accès aux tables
final class Countries extends TableAccess{
    public $Id;
    /** VARCHAR(128) */
    public $Name;
    
    public function init() {
        $this->Id = 0;          
        $this->Name ='';   
    }
    public function __construct($dataBaseAccess) {
        parent::__construct($dataBaseAccess);
    }
    public function htmlComboBox($selectedKey = 0){
        $countries = $this->selectAll('ORDER BY Name');
        $items=[];
        foreach($countries as $country){
            $items[$country['Id']] = $country['Name'];
        }
        return html_combobox('CountrieId', 'Pays', $items, intval($selectedKey));
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
final class Styles extends TableAccess{
    public $Id;
    /** VARCHAR(128) */
    public $Name;
    
    public function init() {
        $this->Id = 0;          
        $this->Name = '';   
    }
    public function __construct($dataBaseAccess) {
        parent::__construct($dataBaseAccess);
    }
    public function htmlComboBox($selectedKey = 0){
        $styles = $this->selectAll('ORDER BY Name');
        $items=[];
        foreach($styles as $style){
            $items[$style['Id']] = $style['Name'];
        }
        return html_combobox('StyleId', 'Style', $items, intval($selectedKey));
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
final class Movies extends TableAccess{
    public $Id;
    /** VARCHAR(48) */
    public $Title;
    /** VARCHAR(2048) */
    public $Synopsis;
    public $CountrieId;
    public $Year;    
    /** VARCHAR(48) */
    public $Author;
    public $StyleId;
    public $PosterGUID;
    public $_imageHelper;

    public function init() {
        $this->Id = 0;          
        $this->Title = '';   
        $this->Synopsis = '';   
        $this->CountrieId = 0;   
        $this->Year = intval(date("Y"));   
        $this->Author = '';   
        $this->StyleId = 0;  
        $this->PosterGUID = '';   
        $this->_imageHelper = new ImageHelper('images','NoMoviePoster.png');
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

    public function emptyMovie() {
        $emptyMovie['Id'] = 0;
        $emptyMovie['Title'] = '';
        $emptyMovie['Synopsis'] = '';
        $emptyMovie['CountrieId'] = 0;
        $emptyMovie['Year'] = date("Y-m-d H:i:s");
        $emptyMovie['Author'] = '';
        $emptyMovie['StyleId'] = 0;
        $emptyMovie['PosterGUID'] = '';
        return $emptyActor;
    }
    public function getHtmlView($movieRecord){
        $htmlView['Id'] = $movieRecord['Id'];
        $htmlView['Title'] = $movieRecord['Title'];
        $htmlView['CountrieId'] = Countries()->get($movieRecord['CountrieId'])['Name'];
        $dateOnly = explode(' ', $movieRecord['Year'])[0];
        $htmlView['Year'] = $dateOnly;
        $htmlView['Author']  = $movieRecord['Author'];
        $htmlView['StyleId'] = Styles()->get($movieRecord['StyleId'])['Name'];
        $photoURL = $this->_imageHelper->getURL($movieRecord['PosterGUID']);
        $htmlView['PosterGUID'] = "<img src='$photoURL' class='smallActorPhoto'>";
        return $htmlView;
    }

    public function getHtmlForm($id = 0){
        $editMode = ($id !== 0 );
        $movieRecord =  $this->emptyMovie();

        if ($editMode) {
            $html = html_beginForm('','movieForm', true);
            $html.= html_Hidden('Id', $id);
            $actorRecord = $this->get($id);
            $html.= html_Hidden('PosterGUID', $movieRecord['PosterGUID']);
        } else {
            $html = html_beginForm('','movieForm', true);
        }
        
        $html.="<div class='createFormLayout'>";

            //// Photo uploader
            $html.="<div>";
                $html.= $this->_imageHelper->html_ImageUploader($movieRecord['PosterGUID']);
            $html.="</div>";

            //// Name, CountrieId, BirthDate
            $html.="<div>";
                $html.= html_label('Nom', 'Name');
                $html.= html_textbox('Name', 'Nom', $actorRecord['Name']);
                $html.= html_label('Pays', 'CountrieId');
                $html.= Countries()->htmlComboBox($actorRecord['CountrieId']);
                $dateOnly = explode(' ', $actorRecord['BirthDate'])[0];
                $html.= html_datepicker('BirthDate', 'Naissance', $dateOnly);
                $html.= html_submit('Submit', 'Enregistrer', 'form-comtrol important');
            $html.="</div>";
            

        $html.="</div>";
        $html.= html_closeForm();
        return $html;
    }
    ///////////////////////////////////////////////////////////
}
final class Actors extends TableAccess{
    public $Id;
    /** VARCHAR(48) */
    public $Name;
    public $CountrieId;
    public $BirthDate;
    public $PhotoGUID;
    public $_imageHelper;
    
    public function init() {
        $this->Id = 0;          
        $this->Name = ''; 
        $this->CountrieId = 0;        
        $this->BirthDate = date("Y-m-d H:i:s");
        $this->PhotoGUID = "";
        $this->_imageHelper = new ImageHelper('images','NoActorPhoto.png');
    }
    public function __construct($dataBaseAccess) {
        parent::__construct($dataBaseAccess);
    }
    public function emptyActor() {
        $emptyActor['Id'] = 0;
        $emptyActor['Name'] = '';
        $emptyActor['CountrieId'] = 0;
        $emptyActor['BirthDate'] = date("Y-m-d H:i:s");
        $emptyActor['PhotoGUID'] = '';
        return $emptyActor;
    }
    public function getHtmlView($actorRecord){
        $htmlView['Id'] = $actorRecord['Id'];
        $htmlView['Name'] = $actorRecord['Name'];
        $htmlView['CountrieId'] = Countries()->get($actorRecord['CountrieId'])['Name'];
        $dateOnly = explode(' ', $actorRecord['BirthDate'])[0];
        $htmlView['BirthDate'] =$dateOnly;
        $photoURL = $this->_imageHelper->getURL($actorRecord['PhotoGUID']);
        $htmlView['PhotoGUID'] = "<img src='$photoURL' class='smallActorPhoto'>";
        return $htmlView;
    }
    public function getHtmlForm($id = 0){
        $editMode = ($id !== 0 );
        $actorRecord =  $this->emptyActor();

        if ($editMode) {
            $html = html_beginForm('','actorForm', true);
            $html.= html_Hidden('Id', $id);
            $actorRecord = $this->get($id);
            $html.= html_Hidden('PhotoGUID', $actorRecord['PhotoGUID']);
        } else {
            $html = html_beginForm('','actorForm', true);
        }
        
        $html.="<div class='createFormLayout'>";

            //// Photo uploader
            $html.="<div>";
                $html.= $this->_imageHelper->html_ImageUploader($actorRecord['PhotoGUID']);
            $html.="</div>";

            //// Name, CountrieId, BirthDate
            $html.="<div>";
                $html.= html_label('Nom', 'Name');
                $html.= html_textbox('Name', 'Nom', $actorRecord['Name']);
                $html.= html_label('Pays', 'CountrieId');
                $html.= Countries()->htmlComboBox($actorRecord['CountrieId']);
                $dateOnly = explode(' ', $actorRecord['BirthDate'])[0];
                $html.= html_datepicker('BirthDate', 'Naissance', $dateOnly);
                $html.= html_submit('Submit', 'Enregistrer', 'form-comtrol important');
            $html.="</div>";
            

        $html.="</div>";
        $html.= html_closeForm();
        return $html;
    }

    public function getDetailsHtml($id){
        $actorRecord = $this->get($id);
        $actorHtmlViewData = $this->getHtmlView($actorRecord);

        $html = "<div class='detailsLayout'>";

            $html.="<div>";
                $photoURL = $this->_imageHelper->getURL($actorRecord['PhotoGUID']);
                $html .= html_image($photoURL, 'photo');
            $html.="</div>";
    
            $html .="<div>";
                $html .= html_header($actorRecord['Name'],1);
                $html .= html_header($actorHtmlViewData['CountrieId'],3);
                $html .= html_header($actorHtmlViewData['BirthDate'],3);
                $html .= html_flashButton('iconEdit',"editActorForm.php?id=$id", "éditer", "bottom");
                $html .= html_flashButton('iconDelete',"deleteActorForm.php?id=$id", "effacer", "bottom");
            $html.="</div>";

                       
        $html.="</div>";
        return $html;
    }
    public function getDeleteHtmlForm($id){
        $actorRecord = $this->get($id);
        $actorHtmlViewData = $this->getHtmlView($actorRecord);

        $html = "<div class='deleteLayout'>";

            $html.="<div>";
                $photoURL = $this->_imageHelper->getURL($actorRecord['PhotoGUID']);
                $html .= html_image($photoURL, 'photo');
            $html.="</div>";
    
            $html .="<div>";
                $html .= html_header($actorHtmlViewData['Name'],1);
                $html .= html_header($actorHtmlViewData['CountrieId'],3);
                $html .= html_header($actorHtmlViewData['BirthDate'],3);

                $html .= html_beginForm('','deleteActorForm');
                    $html .= html_Hidden('Id', $id);
                    $html .= html_submit('Submit', 'Effacer', 'form-comtrol important'); 
                $html .= html_closeForm();
            $html.="</div>";

        $html.="</div>";
        return $html;
    }
    public function createFromForm(){
        if (isset($_POST['Submit'])){
            $newActor['Id'] = 0;
            $newActor['Name'] = $_POST['Name'];
            $newActor['CountrieId'] = $_POST['CountrieId'];
            $newActor['BirthDate'] = $_POST['BirthDate'];
            $newActor['PhotoGUID'] = $this->_imageHelper->upLoadImage();
            $newActorId = $this->insert($newActor);
        }
    }
    public function editFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $newActor['Id'] = $id;
            $newActor['Name'] = $_POST['Name'];
            $newActor['CountrieId'] = intval($_POST['CountrieId']);
            $newActor['BirthDate'] = $_POST['BirthDate']." 00:00:00";
            $newActor['PhotoGUID'] = $this->_imageHelper->upLoadImage($_POST['PhotoGUID']);
            $this->update($newActor);
        }
    }
    public function deleteFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $this->delete($id);
        }
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
final class Casts extends TableAccess{
    public $Id;
    public $ActorId;
    public $MovieId;

    public function init() {
        $this->Id = 0;          
        $this->ActorId = 0;   
        $this->MovieId = 0;      
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
function DB() {
    return DataBaseAccess::getInstance('moviesDB');
}
function Movies() {
    return Movies::getInstance(DB());
}
function Casts() {
    return Casts::getInstance(DB());
}
function Actors() {
    return Actors::getInstance(DB());
}
function Countries() {
    return Countries::getInstance(DB());
}
function Styles() {
    return Styles::getInstance(DB());
}
function InitCountries() {
    DB()->nonQuerySqlCmd('DROP TABLE Countries');
    $file = "Countries.txt";
    $fileHandle = fopen($file,'r');
    // Boucle de lecture
    DB()->beginTransaction();
    $country['Id']=0;
    while (!feof($fileHandle)){
        $fields = explode(';', fgets($fileHandle));
        $country['Name'] = $fields[0];        
        Countries()->insert($country);
    }
    DB()->endTransaction();
    fclose($fileHandle);
}
function InitStyles() {
    DB()->nonQuerySqlCmd('DROP TABLE Styles');
    $file = "Styles.txt";
    $fileHandle = fopen($file,'r');
    // Boucle de lecture
    DB()->beginTransaction();
    $style['Id']=0;
    while (!feof($fileHandle)){
        $fields = explode('|', fgets($fileHandle));
        $style['Name'] = $fields[1];        
        Styles()->insert($style);
    }
    DB()->endTransaction();
    fclose($fileHandle);
}
// Décommenter la ligne suivante pour transporter les données du fichier texte Countries.txt
// dans la table Countries de la BD DBM
//InitCountries();
//InitStyles();
?>