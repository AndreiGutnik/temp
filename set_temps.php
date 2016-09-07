<?php
mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/silos_class.php";
    
    $db=new Database();
    $silos = new Silos($db);
    
    if(isset($_POST["silos_id"]) && isset($_POST["term_id"])){
        $silos_id = $_POST["silos_id"];
        $term_id = $_POST["term_id"];
    }
    else echo "Не все поля заполнены";
    
    $data =$silos->getAllTemp($silos_id, $term_id);
    for($i=0; $i<count($data); $i++){
        $temp_id = $data[$i]["temp_id"];
        $temp_addr = $_POST["temp_addr".$data[$i]["temp_id"]];
        if($temp_addr !== ""){
            $silos->setTempAddr($silos_id, $term_id, $temp_id, $temp_addr);
        }
    }
    
    if($_POST["settemps"]){
        $r = $_SERVER['HTTP_REFERER'];	   
    }
    else exit;
    $silos->redirect($r);
?>