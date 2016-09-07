<?php

require_once "global_class.php";

class Section extends GlobalClass{
    
/*переопределение родительского метода*/    
    public function __construct($db){
        parent::__construct("sections", $db);
    }
}
?>