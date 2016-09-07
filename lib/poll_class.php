<?php

require_once "global_class.php";

class Poll extends GlobalClass{
    
/*переопределение родительского метода*/    
    public function __construct($db){
        parent::__construct("polls", $db);
    }
}
?>