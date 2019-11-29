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
        return $emptyMovie;
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
        $allactoritems = [];//
        $allactors = Casts()->ActorsToItems();//

        if ($editMode) {
            $html = html_beginForm('','movieForm', true);
            $html.= html_Hidden('Id', $id);
            $movieRecord = $this->get($id);
            $html.= html_Hidden('PosterGUID', $movieRecord['PosterGUID']);
            $allactoritems = Casts()->CastsActorsToItems($id);//
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
                $html.= html_label('Titre', 'Title');
                $html.= html_textbox('Title', 'Titre', $movieRecord['Title']);
                $html.= html_label('Pays', 'CountrieId');
                $html.= Countries()->htmlComboBox($movieRecord['CountrieId']);
                $dateOnly = explode(' ', $movieRecord['Year'])[0];
                if ($editMode)
                {
                    $html .= html_label('Année', 'Year');
                    $html .= html_textbox('Year', 'Année', $dateOnly);
                }
                else
                {
                    $html.= html_datepicker('Year', 'Année', $dateOnly);
                }
                $html.= html_label('Auteur', 'Author');
                $html.= html_textbox('Author', 'Auteur', $movieRecord['Author']);
                $html.= html_label('Style', 'Style');
                $html.= Styles()->htmlComboBox($movieRecord['StyleId']);
                $html.= html_label('Synopsis', 'Synopsis');
                $html .= html_textarea('synopsis', '', 5, $movieRecord['Synopsis']);
                $html.= html_submit('Submit', 'Enregistrer', 'form-comtrol important');
            $html.="</div>";
            
            $html .= Casts()->getHtmlForm($allactors,$allactoritems);//
        $html.="</div>";
        $html.= html_closeForm();
        return $html;
    }

    public function createFromForm(){   
        if (isset($_POST['Submit'])){
            $newMovie['Id'] = 0;
            $newMovie['Title'] = $_POST['Title'];
            $newMovie['Synopsis'] = $_POST['synopsis'];
            $newMovie['CountrieId'] = $_POST['CountrieId'];
            $newMovie['Year'] = $_POST['Year'];
            $newMovie['Author'] = $_POST['Author'];
            $newMovie['StyleId'] = $_POST['StyleId'];
            $newMovie['PosterGUID'] = $this->_imageHelper->upLoadImage();
            $newMovie['SelectedItems'] = null;
            $newMovieId = $this->insert($newMovie);
            if(isset($_POST['SelectedItems']))
                $newMovie['SelectedItems'] = $_POST['SelectedItems'];
            Casts()->saveFormActorsSelection($newMovieId,$newMovie['SelectedItems']);
        }
    }


    public function getDetailsHtml($id){
        $movieRecord = $this->get($id);
        $movieHtmlViewData = $this->getHtmlView($movieRecord);
        $allactoritems = Casts()->CastsActorsToItems($id);//

        $html = "<div class='detailsLayout'>";

            $html.="<div>";
                $posterURL = $this->_imageHelper->getURL($movieRecord['PosterGUID']);
                $html .= html_image($posterURL, 'poster');
            $html.="</div>";
    
            $html .="<div>";
                $html .= html_header($movieHtmlViewData['Title'],1);
                $html .= html_header($movieHtmlViewData['CountrieId'],3);
                $html .= html_header($movieHtmlViewData['Year'],3);
                $html .= html_header($movieHtmlViewData['Author'], 3);
                $html .= html_header($movieHtmlViewData['StyleId']);
                $html .= html_flashButton('iconEdit',"editMovieForm.php?id=$id", "éditer", "bottom");
                $html .= html_flashButton('iconDelete',"deleteMovieForm.php?id=$id", "effacer", "bottom");
                $html .= html_textarea('synopsis', '', 5, $movieRecord['Synopsis'], true);
            $html .= "</div>";
            $html .= makeSelectedList($allactoritems);
                       
        $html.="</div>";
        return $html;
    }

    public function editFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $newMovie['Id'] = $id;
            $newMovie['Title'] = $_POST['Title'];
            $newMovie['Synopsis'] = $_POST['synopsis'];
            $newMovie['CountrieId'] = intval($_POST['CountrieId']);
            $newMovie['Year'] = $_POST['Year']." 00:00:00";
            $newMovie['Author'] = $_POST['Author'];
            $newMovie['StyleId'] = $_POST['StyleId'];
            $newMovie['PosterGUID'] = $this->_imageHelper->upLoadImage($_POST['PosterGUID']);
            $this->update($newMovie);
            if(isset($_POST['SelectedItems']))
                $newMovie['SelectedItems'] =$_POST['SelectedItems'];
            Casts()->saveFormActorsSelection($newMovie['Id'],$newMovie['SelectedItems']);
        }
    }

    public function getDeleteHtmlForm($id){
        $movieRecord = $this->get($id);
        $movieHtmlViewData = $this->getHtmlView($movieRecord);

        $html = "<div class='deleteLayout'>";

            $html.="<div>";
                $posterURL = $this->_imageHelper->getURL($movieRecord['PosterGUID']);
                $html .= html_image($posterURL, 'poster');
            $html.="</div>";

            $html .="<div>";
                $html .= html_header($movieHtmlViewData['Title'],1);
                $html .= html_header($movieHtmlViewData['CountrieId'],3);
                $html .= html_header($movieHtmlViewData['Year'],3);
                $html .= html_header($movieHtmlViewData['Author'], 3);
                $html .= html_header($movieHtmlViewData['StyleId']);
                $html .= html_textarea('synopsis', '', 5, $movieRecord['Synopsis'], true);

                $html .= html_beginForm('','deleteMovieForm');
                    $html .= html_Hidden('Id', $id);
                    $html .= html_submit('Submit', 'Effacer', 'form-comtrol important'); 
                $html .= html_closeForm();
            $html.="</div>";

        $html.="</div>";
        return $html;
    }

    public function deleteFromForm(){
        if (isset($_POST['Submit'])){
            $id = intval($_POST['Id']);
            $this->delete($id);
        }
    } 
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
        $allmovieitems = [];//
        $allmovie = Casts()->MoviesToItems();//
        if ($editMode) {
            $html = html_beginForm('','actorForm', true);
            $html.= html_Hidden('Id', $id);
            $actorRecord = $this->get($id);
            $html.= html_Hidden('PhotoGUID', $actorRecord['PhotoGUID']);
            $allmovieitems = Casts()->CastsMoviesToItems($id);//
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
            
            $html .= Casts()->getHtmlForm($allmovie,$allmovieitems);//
        $html.="</div>";
        $html.= html_closeForm();
        return $html;
    }

    public function getDetailsHtml($id){
        $actorRecord = $this->get($id);
        $actorHtmlViewData = $this->getHtmlView($actorRecord);
        $allmovieitems = Casts()->CastsMoviesToItems($id);

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
            $html .= makeSelectedList($allmovieitems);
                       
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
            $newActor['SelectedItems'] = null;
            $newActorId = $this->insert($newActor);
            if(isset($_POST['SelectedItems']))
                $newActor['SelectedItems'] =$_POST['SelectedItems'];
            Casts()->saveFormMoviesSelection($newActorId,$newActor['SelectedItems']);
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
            if(isset($_POST['SelectedItems']))
            $newActor['SelectedItems'] =$_POST['SelectedItems'];
            Casts()->saveFormMoviesSelection($newActor['Id'],$newActor['SelectedItems']);
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

    public function getHtmlForm($Items,$selectedItems){
        $viewContent="";  
            $viewContent .= "<table>";
                $viewContent .= "<tr>";
                    $viewContent .= "<td>";
                        $viewContent .= makeSelectedList($selectedItems);
                    $viewContent .= "</td>";
                    $viewContent .= "<td>";
                        // il faut utiliser le fichier css/flashButtons.css pour 
                        // que les <div> de classes MoveLeft et MoveRight se comportent
                        // comme des boutons flash
                        $viewContent .= "<div id='MoveLeft'  ></div>"; 
                        $viewContent .= "<div id='MoveRight' ></div>";
                    $viewContent .= "</td>";
                    $viewContent .= "<td>";
                        $viewContent .= makeUnselectedList($Items, $selectedItems);
                    $viewContent .= "</td>";
                $viewContent .= "</tr>";
            $viewContent .= "</table>";
        return $viewContent;
    }

    function removeMovies($acteurID)
    {
        $this->deleteWhere("ActorId = $acteurID");
    }

    
    function removeActors($movieID)
    {
        $this->deleteWhere("MovieId = $movieID");
    }

    function MoviesToItems(){
        $items = [];
         foreach(Movies()->get() as $movie){
            $items[$movie['Id']] = $movie['Title'];  
        }
        return $items;
    }

    function CastsMoviesToItems($actorId){
        $items=[];
        foreach(Casts()->selectWhere("ActorId = $actorId") as $cast){
            $movie = Movies()->get($cast['MovieId']);
            $items[$movie['Id']] = $movie['Title'];  
            $items[$movie['PosterGUID']] = $movie['PosterGUID'];
        }
        return $items;
    }

    function saveFormMoviesSelection($actorId,$selectedItemsId) {
        $this->removeMovies($actorId);
        $selection['Id'] = 0;
        $selection['ActorId'] = $actorId;
        foreach($selectedItemsId as $idMovies) {
            $selection['MovieId'] = $idMovies;
            Casts()->insert($selection);
        }
    }

    function ActorsToItems(){
        $items = [];
         foreach(Actors()->get() as $actor){
            $items[$actor['Id']] = $actor['Name'];  
            $items[$actor['PhotoGUID']] = $actor['PhotoGUID'];
        }
        return $items;
    }

    function CastsActorsToItems($movieId){
        $items=[];
        foreach(Casts()->selectWhere("MovieId = $movieId") as $cast){
            $actor = Actors()->get($cast['ActorId']);
            $items[$actor['Id']] = $actor['Name'];
            $items[$actor['PhotosGUID']] = $actor['PhotosGUID'];  
        }
        return $items;
    }

    function saveFormActorsSelection($movieId,$selectedItemsId) {
        $this->removeActors($movieId);
        $selection['Id'] = 0;
        $selection['MovieId'] = $movieId;
        foreach($selectedItemsId as $idActors) {
            $selection['ActorId'] = $idActors;
            Casts()->insert($selection);
        }
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