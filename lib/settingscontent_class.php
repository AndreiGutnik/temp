<?php
require_once "modules_class.php";

class SettingsContent extends Modules{
    
    private $silos_info;
    
    public function __construct($db){
        parent::__construct($db);
        $this->silos_info = $this->silos->getAllSilosInfo();
    }
    
    protected function getTitle(){
        return "Настройки";
    }
    
    protected function getDescription(){
        return "";
    }
    
    protected function getKeywords(){
        return "";
    }
    
    protected function getTop(){
        //$sr[""] = "";
        return "";//$this->getReplaceTemplate($sr, "set_button");
    }
    
    protected function getMiddle(){
        if(!$this->user_info){
            $sr["title"]=$this->message->getTitle("ERROR_ENTRY");
            $sr["text"]=$this->message->getText("ERROR_ENTRY");
            return $this->getReplaceTemplate($sr, "message");
        }
        else { 
            // Добавление силоса
            if($this->data["set_silos"]==1){
                $sr[""] = "";
                $text .= $this->getReplaceTemplate($sr, "set_silos");
                return $text;
            }
            // Удаление силоса
            elseif($this->data["del_silos"]==1){
                $text .= "<form action='del_silos.php' name='delsilos' method='post'><p>Выберете силос для удаления</p><p><select name='silos_id'>";
                for($i=0; $i<count($this->silos_info); $i++){
                    $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                    $text .= $this->getReplaceTemplate($sr, "del_silos");
                }
                $text .= "</select></p><p><input type='submit' name='delsilos' value='Удалить'></p></form><a href='?view=settings'><button type='submit'>Назад</button></a>";            
                return $text;
            }
            // Добавление термоподвески
            elseif($this->data["set_term"]==1){
                $text .= "<form action='set_term.php' name='setterm' method='post'><p>Выберете силос для добавления термоподвески</p><p><select name='silos_id'>";
                for($i=0; $i<count($this->silos_info); $i++){
                    $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                    $text .= $this->getReplaceTemplate($sr, "del_silos");
                }
                $text .= "</select>";
                $text .= $this->getReplaceTemplate($sr, "set_term");
                return $text;
            }
            // Удаление термоподвески
            elseif($this->data["del_term"]==1){
                if(isset($_GET["silos_id"])){
                    $term_info = $this->silos->getAllTermInfo($_GET["silos_id"]);
                    $text .= "<form action='del_terms.php' name='delterms' method='post'><p>Выберете термоподвеску для удаления:</p><p><select name='term_id'>";
                    for($i=0; $i<count($term_info); $i++){
                        $sr["term_id"] = $term_info[$i]["term_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_term");
                    }
                    $text .= "</select>";
                    $text .= "<input type='hidden' name='silos_id' value=".$_GET["silos_id"].">";
                    $text .= "<p><input type='submit' name='delterms' value='Удалить'/></form><a href='?view=settings'><button>Назад</button></a></p>";
                    return $text;                            
                }
                else{    
                    $text .= "<form action='del_term.php' name='delterm' method='post'><p>Выберете силос для удаления термоподвески:</p><p><select name='silos_id'>";
                    for($i=0; $i<count($this->silos_info); $i++){
                        $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_silos");
                    }
                    $text .= "</select><p><input type='submit' name='delterm' value='ОК'/></form><a href='?view=settings'><button>Назад</button></a>";
                    //$text .= $this->getReplaceTemplate($sr, "del_term");
                    return $text;
                }
            }
            // Редактирование адресов датчиков температуры
            elseif($this->data["set_temp"]==1){            
                if(isset($_GET["silos_id"]) && isset($_GET["term_id"])){            
                    $temp_info = $this->silos->getAllTemp($_GET["silos_id"], $_GET["term_id"]);
                    $text .= "<form action='set_temps.php' name='settemps' method='post'><p>Заполните адреса датчиков температуры:</p>";
                    for($i=0; $i<count($temp_info); $i++){
                        $sr["temp_id"] = $temp_info[$i]["temp_id"];
                        $sr["temp_addr"] = $temp_info[$i]["temp_addr"];
                        $text .= $this->getReplaceTemplate($sr, "set_temps");
                    }
                    $text .= "<input type='hidden' name='silos_id' value=".$_GET["silos_id"]."><input type='hidden' name='term_id' value=".$_GET["term_id"].">";
                    $text .= "<input type='submit' name='settemps' value='Добавить'/></form> <a href='?view=settings'><button>Назад</button></a>";
                    return $text;
                }
                if(isset($_GET["silos_id"])){
                    $term_info = $this->silos->getAllTermInfo($_GET["silos_id"]);
                    $text .= "<form action='set_temp.php' name='settemp' method='post'><p>Выберете термоподвеску:</p><p><select name='term_id'>";
                    for($i=0; $i<count($term_info); $i++){
                        $sr["term_id"] = $term_info[$i]["term_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_term");
                    }
                    $text .= "</select>";
                    $text .= "<input type='hidden' name='silos_id' value=".$_GET["silos_id"].">";
                    $text .= "<p><input type='submit' name='settemp' value='ОК'/></form><a href='?view=settings'><button>Назад</button></a></p>";
                    return $text;                            
                }
                else{    
                    $text .= "<form action='del_term.php' name='delterm' method='post'><p>Выберете силос:</p><p><select name='silos_id'>";
                    for($i=0; $i<count($this->silos_info); $i++){
                        $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_silos");
                    }
                    $text .= "</select><p><input type='submit' name='delterm' value='ОК'/></form><a href='?view=settings'><button>Назад</button></a>";
                    return $text;
                }   
                /*else{
                    $text .= "<form action='set_temp.php' name='settemp' method='post'><p>Выберете силос для добавления датчиков температуры: <select name='silos_id'>";
                    for($i=0; $i<count($this->silos_info); $i++){
                        $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_silos");
                    }
                    $text .= "</select></p>";
                    $text .= $this->getReplaceTemplate($sr, "set_temp");
                    return $text;
                }*/
            }
            // Удаление адресов датчиков температуры
            /*elseif($this->data["del_temp"]==1){
                if(isset($_GET["silos_id"]) && isset($_GET["term_id"])){ 
                    $temp_info = $this->silos->getAllTempInfo($_GET["silos_id"], $_GET["term_id"]);
                    print_r($temp_info);
                    $text .= "<form action='del_temps.php' name='deltemps' method='post'><p>Выберете термоподвеску:</p><p><select name='term_id'>";
                    for($i=0; $i<count($temp_info); $i++){
                        $sr["temp_id"] = $temp_info[$i]["temp_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_temp");
                    }
                    $text .= "</select>";
                    $text .= "<input type='hidden' name='silos_id' value=".$_GET["silos_id"]."><input type='hidden' name='term_id' value=".$_GET["term_id"].">";
                    $text .= "<p><input type='submit' name='deltemps' value='Удалить'/></form><a href='?view=settings'><button>Назад</button></a></p>";
                    return $text;
                }
                if(isset($_GET["silos_id"])){
                    $term_info = $this->silos->getAllTermInfo($_GET["silos_id"]);
                    $text .= "<form action='del_temp.php' name='deltemp' method='post'><p>Выберете термоподвеску:</p><p><select name='term_id'>";
                    for($i=0; $i<count($term_info); $i++){
                        $sr["term_id"] = $term_info[$i]["term_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_term");
                    }
                    $text .= "</select>";
                    $text .= "<input type='hidden' name='silos_id' value=".$_GET["silos_id"].">";
                    $text .= "<p><input type='submit' name='deltemp' value='ОК'/></form><a href='?view=settings'><button>Назад</button></a></p>";
                    return $text;                            
                }
                else{    
                    $text .= "<form action='del_term.php' name='delterm' method='post'><p>Выберете силос:</p><p><select name='silos_id'>";
                    for($i=0; $i<count($this->silos_info); $i++){
                        $sr["silos_id"] = $this->silos_info[$i]["silos_id"];
                        $text .= $this->getReplaceTemplate($sr, "del_silos");
                    }
                    $text .= "</select><p><input type='submit' name='delterm' value='ОК'/></form><a href='?view=settings'><button>Назад</button></a>";
                    return $text;
                }
            }*/
            else{
                $sr[""] = "";
                return $this->getReplaceTemplate($sr, "set_button");
            }
        }
        /*else {
            $text .= "Пользователь не авторизован!";
            return $text;
        }*/        
    }
}
?>