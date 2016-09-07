<?php
mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/silos_class.php";
    
    $db=new Database();
    $silos = new Silos($db);
    
    if(isset($_POST["silos_id"]) && $_POST["term_id"]){
        $silos_id=$_POST["silos_id"];
        $term_id=$_POST["term_id"];
    }
    else echo "Не выбрана термоподвеска";
    
    $data =$silos->getAllOnSilosID($silos_id);
    for($i=0; $i<count($data); $i++){
        if($data[$i]["term_id"]==$term_id){
            $silos->delTermOnID($silos_id, $term_id);
            if($_POST["delterms"]){
                $r = $_SERVER['HTTP_REFERER'];	   
            }
            else exit;
            $silos->redirect($r);
        }
    }
?>