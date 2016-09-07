<?php
mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/silos_class.php";
    
    $db=new Database();
    $silos = new Silos($db);
    
    if(isset($_POST["silos_id"]) && isset($_POST["kol_term"]) && isset($_POST["kol_temp"])){
        $silos_id=$_POST["silos_id"];
        $kol_term=$_POST["kol_term"];
        $kol_temp=$_POST["kol_temp"];
    }
    else echo "Не все поля заполнены";
    
    $max_term_id = (int)count($silos->getAllOnSilosID($silos_id));
    for($i=$max_term_id+1; $i<=$max_term_id+$kol_term; $i++){
        for($j=1; $j<=$kol_temp; $j++){          
            $silos->insertSilos($silos_id, $i, $j);
        }
    }
    if($_POST["setterm"]){
        $r = $_SERVER['HTTP_REFERER'];	   
    }
    else exit;
    $silos->redirect($r);
?>