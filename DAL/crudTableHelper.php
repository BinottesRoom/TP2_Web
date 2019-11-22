<?php
include_once "utilities/htmlHelper.php";

class CRUDTableHelper{
        public $sortedKey;
        public $previousSortedKey;
        public $previousAscendant;
        public $ascendant;
        public $records;
        public $tableAccess;
        public $keys;
        public $captionKey;
        public $captions;
        public $sortables;

        public function __construct($tableAccess){
            $this->ascendant = true;  
            $this->previousAscendant = true;  
            $this->sortedKey = '';
            $this->previousSortedKey = '';
            $this->tableAccess = $tableAccess;            
            $this->captionKey = '' ;
            $this->captions = [];              
            $this->sortables = [];
        }
        public function setcaptionKey($key){
            $this->captionKey = $key;
        }
        public function addColumn($key, $caption, $sortable = false){
            $this->keys[]=$key;              
            $this->captions[$key]=$caption;              
            $this->sortables[$key]=$sortable;            
        }
        public function setSortedKey($key){
            if ($this->sortables[$key]){
                if ($this->sortedKey === $key) {
                    $this->ascendant = !$this->ascendant;
                } else {
                    $this->previousSortedKey = $this->sortedKey;
                    $this->previousAscendant = $this->ascendant;
                    $this->sortedKey = $key;
                    $this->ascendant = true;
                }
            } 
        }
        public function getRecords() {
            $orderby='';
            if ($this->sortedKey !== '') {
                $orderby = "ORDER BY ".$this->sortedKey.($this->ascendant?" ASC":" DESC");
                if ($this->previousSortedKey !== '')
                    $orderby .= ", ".$this->previousSortedKey.($this->previousAscendant?" ASC":" DESC");
            }
            $this->records = $this->tableAccess->selectAll($orderby);
        }
        private function recordName(){
            return substr($this->tableAccess->tableName(), 0, -1);
        }
        private function tableName(){
            return $this->tableAccess->tableName();
        }
        private function listURL($key=''){
            return "list".$this->tableName().".php?sortBy=$key";
        }
        private function createURL(){
            return "create".$this->recordName()."Form.php";
        }
        private function editURL($id){
            return "edit".$this->recordName()."Form.php?id=$id";
        }
        private function detailsURL($id){
            return "details".$this->recordName()."Form.php?id=$id";
        }
        private function deleteURL($id){
            return "delete".$this->recordName()."Form.php?id=$id";
        }
        public function makeHeaderColumn($key){
            $html = "";
            if ($this->sortables[$key]){   
                $imageURL = "images/Sort.png";
                if ($key === $this->sortedKey) {
                    if ($this->ascendant)
                        $imageURL = "images/SortAsc.png";
                    else
                        $imageURL = "images/SortDesc.png";
                }
                $url = $this->listURL($key);
                $inlineRedirect = "onclick=\"location.href = '$url';\"";
                $html .= "<div class='headerListItem sortedItem' $inlineRedirect><img src='$imageURL' class='sortIcon'> ";
            } else {
                $html = "<div class='headerListItem' >";
            }
            $caption = $this->captions[$key];
            $html .= "$caption</div>";
            return $html;
        }
        public function makeCreateButton(){
            return html_flashButton('iconPlus', $this->createURL(), 'Ajouter');
        }
        public function makeEditButton($id, $tooltip){
            return html_flashButton('iconEdit', $this->editURL($id),  $tooltip, 'left');
        }
        public function makeDetailsButton($id, $tooltip){     
            return html_flashButton('iconDetails', $this->detailsURL($id),  $tooltip, 'left');
        }
        public function makeDeleteButton($id, $tooltip){
            return html_flashButton('iconDelete', $this->deleteURL($id),  $tooltip, 'left');
        }
        public function makeHeaderList(){
            $cssClass = $this->tableName().'HeaderListLayout';
            $html="<div class='$cssClass'>";
            foreach($this->keys as $key){
                $html.= $this->makeHeaderColumn($key);
            }
            $html .= $this->makeCreateButton();
            $html.= "</div>";
            
            return $html;
        }
        public function makeCell($content){
            $html = "<div class='cellContent'>";
            $html.= "<div class='innerCellContent'>";
            $html.= $content;
            $html.= "</div>";            
            $html.= "</div>";
            return $html;
        }
        public function makeList(){
            $this->getRecords();
            $cssClass = $this->tableName().'ListLayout';

            $html='';
            $html.="<div class='scrollList'>";
            foreach($this->records as $record){
                $html.="<div class='$cssClass'>";
                $htmlViewRecord = $this->tableAccess->getHtmlView($record);
                foreach($this->keys as $key){
                    $html.= $this->makeCell($htmlViewRecord[$key]);
                }
            $html .= $this->makeEditButton($htmlViewRecord['Id'], 'Modifier '.$htmlViewRecord[$this->captionKey]);
                $html .= $this->makeDetailsButton($htmlViewRecord['Id'], 'Détails '.$htmlViewRecord[$this->captionKey]);
                $html .= $this->makeDeleteButton($htmlViewRecord['Id'], 'Effacer '.$htmlViewRecord[$this->captionKey]);
                $html.="</div>";
            }
            $html.="</div>";
            return $html;
        }
        public function makeCRUD() {
            return $this->makeHeaderList().$this->makeList();
        }
    }

    ?>