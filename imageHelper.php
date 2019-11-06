<?php

class ImageHelper {
    public $basePath;
    public $defaultImage;

    public function __construct($basePath, $defaultImage) {
        $this->basePath = $basePath;        
        $this->defaultImage = $defaultImage;
    }

    public function getURL($GUID) {
        $url = $this->basePath.'/';
        if (!empty($GUID)) {
            $url.= $GUID.'.png';
        } else {
            $url.= $this->defaultImage;
        }
        return $url;
    }

    public function html_ImageUploader($GUID, $tooltipMessage = 'Cliquez pour changer la photo...' ) {
        $url = $this->getURL($GUID);
        $html = "
        <!-- nécessite le fichier javascript 'imageUpLoader.js' -->
        <img id='UploadedImage'
            class='Photo'
            src='$url'
            data-toggle='tooltip'
            data-placement='bottom'
            title='$tooltipMessage' />
        <input id='ImageUploader'
            name='ImageUploader'
            type='file'
            style='display:none'
            accept='image/jpeg,image/gif,image/png,image/bmp'/>";
        return $html;
    }

    private function newGUID() {
        $GUID = '';
        do {
            $GUID = com_create_guid();
        } while (file_exists($this->getURL($GUID)));
        return $GUID;
    }

    public function upLoadImage($previousGUID) {
        $GUID = '';
        $check = getimagesize($_FILES['ImageUploader']['tmp_name']);
        if ($check) {
            $this->removeFile($previousGUID);
            $GUID = $this->newGUID();
            move_uploaded_file($_FILES["ImageUploader"]["tmp_name"], $this->getURL($GUID));
            return $GUID;
        }
        return $previousGUID;
    }

    public function removeFile($GUID) {
        if (!empty($GUID)) {
            unlink($this->getURL($GUID));
        }    
    }
}