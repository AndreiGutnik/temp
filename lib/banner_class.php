<?php

require_once "global_class.php";

class Banner extends GlobalClass{
    
/*переопределение родительского метода*/    
    public function __construct($db){
        parent::__construct("banners", $db);
    }
}
?>