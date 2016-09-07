<?php

require_once "global_class.php";

class Temp extends GlobalClass{
    
    public function __construct($db){
		parent:: __construct("temp", $db);
	}
}

?>