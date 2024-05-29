<?php 
$self = $_SERVER['PHP_SELF'];
if(!strpos($self, "seller/login.php") && !strpos($self, "seller/registration.php")){
    if(!isset($_SESSION['userdata']['id'])){
        redirect('./seller/login.php');
    }
}
?>