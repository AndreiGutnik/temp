<?php
require_once "modules_class.php";

class FrontpageContent extends Modules{
    
    private $articles;
    private $page;
    
    public function __construct($db){
        parent::__construct($db);
        $this->articles=$this->article->getAllSortDate();
        
        if(isset($this->data["page"])){
            $this->page=$this->data["page"];
        }
        else{
            $this->page=1;
        }
    }
    
    protected function getTitle(){
        return "Термометрия";
    }
    
    protected function getDescription(){
        return "";
    }
    
    protected function getKeywords(){
        return "";
    }
    
    protected function getMiddle(){
        return $this->getBlockSilos();
    }
}
?>