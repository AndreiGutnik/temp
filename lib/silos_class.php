<?php

require_once "global_class.php";

class Silos extends GlobalClass{
    
    public function __construct($db){
		parent:: __construct("silos", $db);
	}

/* ��������� ������ ���� �������*/    
    public function getAllSilos(){
		return $this->getAllRecordsOnField("silos_id", "silos_id", "silos_id", true);
	}
    
 /* ��������� ���� �����*/
    public function getAllTemp($silos_id, $term_id){
        return $this->getAllOnTwoField("silos_id", $silos_id, "term_id", $term_id, "", "temp_id", true);
    }
    
/* ��������� ���� ����� �� �������*/
    public function getAllSilosInfo(){
        return $this->getAllRecordsOnField("*", "silos_id", "silos_id", true);
    }
    
/* ��������� ���� ������������� ����������� ������*/
    public function getAllTermInfo($silos_id){
        return $this->getFieldsOnValueField("term_id", "silos_id", $silos_id, "term_id", "", true);
    }
    
/* �
    
/* �������� �������� ������������� ����������� ������*/
    public function delTermOnID($silos_id, $term_id){
        return $this->deleteAllOnFields("silos_id", $silos_id, "term_id", $term_id);
    }

/* �������� ���� ������� ����������� ������*/
    public function delSilosOnID($silos_id){
        return $this->deleteAllOnField("silos_id", $silos_id);
    }

/* ���������� ������*/    
    public function insertSilos($silos_id, $term_id, $temp_id){
        return $this->insertOnField(array("silos_id"=>$silos_id, "term_id"=>$term_id, "temp_id"=>$temp_id, "temp_addr"=>""));
    }
    
/* ���������� ������� �������������*/
    public function setTempAddr($silos_id, $term_id, $temp_id, $temp_addr){
        return $this->setFieldOnWhere(array("temp_addr"=>$temp_addr), array("silos_id"=>$silos_id, "term_id"=>$term_id, "temp_id"=>$temp_id), "AND");
    }
    
/* ��������� ���� �������������, ��������� ������
��������������� �� �����������*/
    public function getAllOnSilosID($silos_id){
        return $this->getAllOnFields("silos_id", $silos_id, "term_id", "term_id", true);
    }

/* ��������� ���� ���������� ������ �� ���������� �������������*/    
    public function getTempOnSilosForTerm($silos_id, $term_id){
        return $this->getFieldsOnInnerTable("temp", "temp_addr", array("temp_id", "temp"), "silos_id", $silos_id, "term_id", $term_id, "temp_id", "temp_id", true);
    }
    
/* ��������*/
    public function redirect($link){
        header("Location: $link");
        exit;
    }   
}

?>