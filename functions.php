<?php
require_once "lib/database_class.php";
require_once "lib/manage_class.php";

$db=new Database();
$manage=new Manage($db);
if(isset($_POST["reg"])){
    $r=$manage->regUser();
}
elseif(isset($_POST["auth"])) {
    $r=$manage->login();
}
elseif(isset($_GET["logout"])){
    $r=$manage->logout();
}
elseif(isset($_POST["poll"])){
    $r=$manage->poll();
} 
    
else exit;
$manage->redirect($r);
?>