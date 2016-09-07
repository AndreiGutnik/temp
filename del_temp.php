<?php
mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/silos_class.php";
    
    $db=new Database();
    $silos = new Silos($db);
    
    if(isset($_POST["silos_id"]) && isset($_POST["term_id"])){
        $silos_id=$_POST["silos_id"];
        $term_id=$_POST["term_id"];
    }
    else echo "Не все поля заполнены";
    
    $data =$silos->getAllOnSilosID($silos_id);
    for($i=0; $i<count($data); $i++){
        if($data[$i]["term_id"]==$term_id){
            if($_POST["deltemp"]){
                $r = $_SERVER['HTTP_REFERER']."&silos_id=$silos_id&term_id=$term_id";	   
            }
            else exit;
            $silos->redirect($r);
        }
    }
    echo "Термоподвеска не найдена";
?>