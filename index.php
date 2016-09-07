<?php
    mb_internal_encoding("UTF-8");
    //
    require_once "lib/database_class.php";
    require_once "lib/frontpagecontent_class.php";
    require_once "lib/siloscontent_class.php";
    require_once "lib/settingscontent_class.php";
    require_once "lib/regcontent_class.php";
    require_once "lib/messagecontent_class.php";
    /*require_once "lib/searchcontent_class.php";
    require_once "lib/notfoundcontent_class.php";
    require_once "lib/pollContent_class.php";*/
    
    $db=new Database();
    if(isset($_GET["view"])) $view=$_GET["view"];
    else $view="";
    switch ($view){
        // Главная
        case "":
            $content=new FrontpageContent($db);
        break;
        // Вывод выбранного силоса
        case "silos":
            $content=new SilosContent($db);
        break;
        // Настройки
        case "settings":
            $content=new SettingsContent($db);
        break;
        // Регистрация
        case "reg":
            $content=new RegContent($db);
        break;
        // Сообщение после регистрации
        case "message":
            $content=new MessageContent($db);
        break;
        /*case "search":
            $content=new SearchContent($db);
        break;
        case "noyfound":
            $content=new NotFoundContent($db);
        break;
        case "poll":
            $content=new PollContent($db);
        break;*/
        default: exit/*$content = new NotFoundContent($db)*/;
    }
    
    echo $content->getContent();
?>