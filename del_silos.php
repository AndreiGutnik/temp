<?php
mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/silos_class.php";
    
    $db=new Database();
    $silos = new Silos($db);
    if(isset($_POST["silos_id"])) $silos_id=$_POST["silos_id"];
    else echo "Не выбран силос";
    
    $silos->delSilosOnID($silos_id);
    if($_POST["delsilos"]){
        $r = $_SERVER['HTTP_REFERER'];	   
    }
    else exit;
    $silos->redirect($r);
?>