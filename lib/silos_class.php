<?php

require_once "global_class.php";

class Silos extends GlobalClass{
    
    public function __construct($db){
		parent:: __construct("silos", $db);
	}

/* Получение списка всех силосов*/    
    public function getAllSilos(){
		return $this->getAllRecordsOnField("silos_id", "silos_id", "silos_id", true);
	}
    
 /* Получение всех полей*/
    public function getAllTemp($silos_id, $term_id){
        return $this->getAllOnTwoField("silos_id", $silos_id, "term_id", $term_id, "", "temp_id", true);
    }
    
/* Получение всех полей по силосам*/
    public function getAllSilosInfo(){
        return $this->getAllRecordsOnField("*", "silos_id", "silos_id", true);
    }
    
/* Получение всех термоподвесок конкретного силоса*/
    public function getAllTermInfo($silos_id){
        return $this->getFieldsOnValueField("term_id", "silos_id", $silos_id, "term_id", "", true);
    }
    
/* П
    
/* Удаление заданной термоподвески конкретного силоса*/
    public function delTermOnID($silos_id, $term_id){
        return $this->deleteAllOnFields("silos_id", $silos_id, "term_id", $term_id);
    }

/* Удаление всех записей конкретного силоса*/
    public function delSilosOnID($silos_id){
        return $this->deleteAllOnField("silos_id", $silos_id);
    }

/* Добавление силоса*/    
    public function insertSilos($silos_id, $term_id, $temp_id){
        return $this->insertOnField(array("silos_id"=>$silos_id, "term_id"=>$term_id, "temp_id"=>$temp_id, "temp_addr"=>""));
    }
    
/* Добавление адресов термодатчиков*/
    public function setTempAddr($silos_id, $term_id, $temp_id, $temp_addr){
        return $this->setFieldOnWhere(array("temp_addr"=>$temp_addr), array("silos_id"=>$silos_id, "term_id"=>$term_id, "temp_id"=>$temp_id), "AND");
    }
    
/* получение всех термоподвесок, заданного силоса
отсортированных по возрастанию*/
    public function getAllOnSilosID($silos_id){
        return $this->getAllOnFields("silos_id", $silos_id, "term_id", "term_id", true);
    }

/* Получение всех температур силоса по конкретной термоподвеске*/    
    public function getTempOnSilosForTerm($silos_id, $term_id){
        return $this->getFieldsOnInnerTable("temp", "temp_addr", array("temp_id", "temp"), "silos_id", $silos_id, "term_id", $term_id, "temp_id", "temp_id", true);
    }
    
/* Редирект*/
    public function redirect($link){
        header("Location: $link");
        exit;
    }   
}

?>