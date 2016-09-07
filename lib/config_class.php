<?php
class Config{
    var $sitename = "Termometry";
    var $address = "http://localhost/temp/";
    var $secret = "dog999"; // Приставка к паролю
    var $db = "temp";
    var $host = "localhost";
    var $db_prefix = "tp_"; // Префикс к таблицам БД
    var $user = "andrei";
    var $password = "203523";
    var $admname = "Андрей Гутник";
    var $admemail = "andrei_83.83@mail.ru";
    var $dir_tmpl = "tmpl/"; // Путь к папке с шаблонами
    var $dir_text = "lib/text/"; // Путь к папке с сообщениями
    //var $count_blog = 2; // Количество статей на странице
    
    var $count_col_silos = 4; // Количество столбцов в таблице силосов
    
    var $min_login = 4; // Минимальная длина логина
    var $max_login = 255; // Максимальная длина логина
}
?>