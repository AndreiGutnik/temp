<?php
require_once "modules_class.php";

class SilosContent extends Modules{
    
    private $silos_info;
    private $temp_info;
    
    public function __construct($db){
        parent::__construct($db);
        $this->silos_info = $this->silos->getAllOnSilosID($this->data["silos_id"]);
        if(!$this->silos_info) $this->notFound();        
        $this->temp_info = $this->silos->getTempOnSilosForTerm($this->data["silos_id"], $this->data["term_id"]);
    }
    
    protected function getTitle(){
        return "Термометрия - Силос ".$this->data["silos_id"];
    }
    
    protected function getDescription(){
        return "";
    }
    
    protected function getKeywords(){
        return "";
    }
    
    protected function getTop(){
        return "<h2>Силос ".$this->data["silos_id"]."</h2>";
    }

/* Вывод списка термоподвесок*/   
    protected function getMiddle(){
        return $this->getTerm();
    }
    
    private function getTerm(){
        for($i=0; $i<count($this->silos_info); $i++){
            $sr["term_id"]=$this->silos_info[$i]["term_id"];
            $sr["link_term"] = $this->config->address."?view=silos&amp;silos_id=".$this->data["silos_id"]."&amp;term_id=".$this->silos_info[$i]["term_id"];
            $text .= $this->getReplaceTemplate($sr, "silos_intro");
        }
        return $text;
    }

/* Вывод таблицы температуры*/    
    protected function getTemp(){
        return $this->getTempOnTerm();
    }
    
    private function getTempOnTerm(){        
        if($this->data["term_id"]){
            $sr["term_id"] = $this->data["term_id"];
            $text .= $this->getReplaceTemplate($sr, "temp_intro");
        }
        for($i=0; $i<count($this->temp_info); $i++){
            $sr["temp_id"]=$this->temp_info[$i]["temp_id"];
            $sr["temp"] = $this->temp_info[$i]["temp"];;
            $text .= $this->getReplaceTemplate($sr, "temp");
        }
        $text .= "</table></div><!-- end left-->";
        return $text;
    }
}
?>