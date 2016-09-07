<?php

    mb_internal_encoding("UTF-8");
    
    require_once "lib/database_class.php";
    require_once "lib/frontpagecontent_class.php";
    
    $db = new DataBase();
    if(isset($_GET["view"])) $view=$_GET["view"];
    else $view="";
    switch ($view){
        case "":
            $content = new FrontPageContent($db);
            break;
        default: exit;
    }
    
    echo $content->getContent();

?>