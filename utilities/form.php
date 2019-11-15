<?php

const UNCHANGEDPASSWORD = '____Unchanged_Password____';

function sanitizeString($str) {
      $str = stripslashes($str);
      $str = htmlentities($str);
      $str = strip_tags($str);
      //$str = str_replace('|','', $str);
      return $str;
}

function formAntiForgeryToken() {
      $guid = com_create_guid();
      $_SESSION['formAntiForgeryToken'] = $guid;
      return '<input type="hidden" name="formAntiForgeryToken" value="'.$_SESSION["formAntiForgeryToken"].'">';
}

function isFormLegit() {
      if (empty($_SESSION['formAntiForgeryToken'])) return false;
      if (empty($_POST['formAntiForgeryToken'])) return false;
      return (strcmp($_SESSION['formAntiForgeryToken'], $_POST['formAntiForgeryToken']) === 0);
}

function commandAntiForgeryToken() {
      $guid = com_create_guid();
      $_SESSION['commandAntiForgeryToken'] = $guid;
      return "&commandAntiForgeryToken=$guid";     
}

function isCommandLegit() {
      if (empty($_SESSION['commandAntiForgeryToken'])) return false;
      if (empty($_GET['commandAntiForgeryToken'])) return false;
      return (strcmp($_SESSION['commandAntiForgeryToken'], $_GET['commandAntiForgeryToken']) === 0);
}

?>