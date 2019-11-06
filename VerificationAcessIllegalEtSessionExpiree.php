<?php
require_once 'SessionTimeOut.php';
set_Session_Timeout(240,'LoginForm.php');

if(!isset($_SESSION['ValidUser']))
{
    $_SESSION['ValidUser'] = false;
    header('Location:LoginForm.php');
    exit();
}
?>