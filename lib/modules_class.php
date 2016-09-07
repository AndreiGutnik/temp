<?php
/*создание абстрактного класса, который будет являться родительским для классов, отвечающих каждый за свою таблицу*/

require_once "config_class.php";
require_once "article_class.php";
require_once "section_class.php";
require_once "user_class.php";
require_once "menu_class.php";
require_once "silos_class.php";
//require_once "settings_class.php";
require_once "message_class.php";
/*require_once "poll_class.php";
require_once "pollvariant_class.php";*/

abstract class Modules{
    
    protected $config;
    protected $article;
    protected $section;
    protected $user;
    protected $menu;
    protected $silos;
    protected $message;
    protected $data;
    protected $user_info;
    /*protected $poll;
    protected $poll_variant;*/
    
    public function __construct($db){
        session_start();
        $this->config=new Config();
        $this->article=new Article($db);
        $this->section=new Section($db);
        $this->user=new User($db);
        $this->menu=new Menu($db);
        $this->silos=new Silos($db);
        $this->message=new Message($db);
        /*$this->poll = new Poll($db);
        $this->poll_variant = new PollVariant($db);*/
        $this->data=$this->secureData($_GET);
        $this->user_info=$this->getUser();
    }

/*метод. отвечающий за подстановку соответствуючих полей*/    
    public function getContent(){
        $sr["title"]=$this->getTitle();
        $sr["meta_desc"]=$this->getDescription();
        $sr["meta_key"]=$this->getKeywords();
        $sr["menu"]=$this->getMenu();
        //$sr["menu"]=$this->getSilos();
        $sr["auth_user"]=$this->getAuthUser();
        $sr["temp"]=$this->getTemp();
        $sr["top"]=$this->getTop();
        $sr["middle"]=$this->getMiddle();
        $sr["bottom"]=$this->getBottom();
        return $this->getReplaceTemplate($sr, "main");
    }
    
    private function getUser(){
        $login=$_SESSION["login"];
        $password=$_SESSION["password"];
        if($this->user->checkUser($login, $password)) return $this->user->getUserOnLogin($login);
        else return false;
    }
    
/*вспомогательные методы для метода getContent.
В этом классе, должны быть перечислены методы для подстановок. которые используются на каждой странице*/
    abstract protected function getTitle();
    abstract protected function getDescription();
    abstract protected function getKeywords();
    abstract protected function getMiddle();
    
    protected function getMenu(){
        $menu=$this->menu->getAlls();
        for($i=0; $i<count($menu); $i++){
            $sr["title"]=$menu[$i]["title"];
            $sr["link"]=$this->config->address.$menu[$i]["link"];
            $text.=$this->getReplaceTemplate($sr, "menu_item");
        }
        return $text;
    }
    
    protected function getAuthUser(){
        if($this->user_info){
            $sr["username"]=$this->user_info["login"];
            return $this->getReplaceTemplate($sr, "user_panel");
        }
        if($_SESSION["error_auth"]==1){
            $sr["message_auth"]=$this->getMessage("ERROR_AUTH");
            unset($_SESSION["error_auth"]);
        }
        else{
            $sr["message_auth"]="";
        }
        return $this->getReplaceTemplate($sr, "form_auth");
    }
    
    protected function getTemp(){
        return "";
    }
    
    /*protected function getBanners(){
        $banners=$this->banner->getAlls();
        for($i=0; $i<count($banners); $i++){
            $sr["code"]=$banners[$i]["code"];
            $text .= $this->getReplaceTemplate($sr, "banner");
        }
        return $text;
    }*/
    
    protected function getTop(){
        return "";
    }
    
    protected function getBottom(){
        return "";
    }
    
/*проверка массива на корректность*/
    private function secureData($data){
        foreach($data as $key=>$value){
            if(is_array($value)) $this->secureData($value);
            else $data[$key]=htmlspecialchars($value);
        }
        return $data;
    }
    
/* ---- Вывод блока силосов*/
protected function getBlockSilos(){
	$silos=$this->silos->getAllSilos();
    $kol = $this->config->count_col_silos; // Количество столбцов в таблице силосов
    $sum = count($silos); // количество строк в выборке
    
    $sum_row = ceil($sum/$kol); //количество получаемых строк в html-таблице
    if($sum<=$kol){
        $sum_col = $sum; //количество получаемых столбцов в html-таблице
    }
    else{
        $sum_col = $kol;
    }
    $i=0;
    $text = "";
    $j=0;
        for($row=0; $row<$sum_row; $row++){
            $text .= "<tr>";
 
                for($i=0; $i<$sum_col; $i++){
                    /*$text .= "<td>Привет $j</td>";*/
                    $sr["silos_id"]=$silos[$j]["silos_id"];
                    $sr["link_silos"]=$this->config->address."?view=silos&amp;silos_id=".$silos[$j]["silos_id"];
                    $text .= "<td>".$this->getReplaceTemplate($sr, "silos")."</td>";
                    $j++;
                }
            $text .= "</tr>";
            $sum = $sum-$kol;
            if($sum<=$kol){
                $sum_col = $sum;
            }
            else{
                $sum_col = $kol;
            }
        }
    return $text;
}
    
/*вывод блока статей*/
    /*protected function getBlogArticles($articles, $page){
        $start=($page-1)*$this->config->count_blog;
        if(count($articles)>$start+$this->config->count_blog){
            $end=$start+$this->config->count_blog;
        }
        else $end=count($articles);
        for($i=$start; $i<$end; $i++){
            $sr["title"]=$articles[$i]["title"];
            $sr["intro_text"]=$articles[$i]["intro_text"];
            $sr["date"]=$this->formatDate($articles[$i]["date"]);
            $sr["link_article"]=$this->config->address."?view=article&amp;id=".$articles[$i]["id"];
            $text.=$this->getReplaceTemplate($sr, "article_intro");
        }
        return $text;
    }
    
    protected function formatDate($time){
        return date("Y-m-d H:i:s", $time);
    }*/
    
    protected function getMessage($message=""){
        if($message==""){
            $message=$_SESSION["message"];
            /*для того, чтобы ошибка выводилась один раз*/
            unset($_SESSION["message"]);
        }
        $sr["message"]=$this->message->getText($message);
        return $this->getReplaceTemplate($sr, "message_string");
    }
    
    /*protected function getPagination($count, $count_on_page, $link){
        $count_pages=ceil($count/$count_on_page);
        $sr["number"]=1;
        $sr["link"]=$link;
        $pages=$this->getReplaceTemplate($sr, "number_page");
        if(strpos($link, "?") !== false){
            $symm="&amp;";
        }
        else $symm="?";
        for($i=2;$i<=$count_pages;$i++){
            $sr["number"]=$i;
            $sr["link"]=$link.$symm."page=$i";
            $pages.=$this->getReplaceTemplate($sr, "number_page");
        }
        $eis["number_pages"]=$pages;
        return $this->getReplaceTemplate($eis, "pagination");
    }*/
    
/*МЕТОДЫ ШАбЛОНИЗАТОРА*/
/*получение .tpl шаблона*/
    protected function getTemplate($name){
        $text=file_get_contents($this->config->dir_tmpl.$name.".tpl");
        return str_replace("%address%", $this->config->address, $text);
    }
    
/*замена сразу многих элементов*/
    protected function getReplaceTemplate($sr, $template){
        return $this->getReplaceContent($sr, $this->getTemplate($template));
    }
    
/*замена данных в некоторой строке*/    
    private function getReplaceContent($sr, $content){
        $search=array();
        $replace=array();
        $i=0;
        foreach($sr as $key=>$value){
            $search[$i]="%$key%";
            $replace[$i]=$value;
            $i++;
        }
        return str_replace($search, $replace, $content);
    }
    
    protected function redirect($link){
        header("Location: $link");
        exit;
    }
    
    protected function notFound(){
        $this->redirect($this->config->address."?view=notfound");
    }
}
?>