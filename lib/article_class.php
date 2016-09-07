<?php

require_once "global_class.php";

class Article extends GlobalClass{
    
/*переопределение родительского метода*/    
    public function __construct($db){
        parent::__construct("articles", $db);
    }
    
/*получение всех статей. отсортированных по дате
по убыванию*/
    public function getAllSortDate(){
        return $this->getAlls("date", false);
    }
    
/*получение всех статей, заданного раздела
отсортированных по дате по убыванию*/
    public function getAllOnSectionID($section_id){
        return $this->getAllOnFields("section_id", $section_id, "date", false);
    }
    
    public function searchArticles($words){
        return $this->search($words, array("title", "full_text"));
    }
    












}
?>