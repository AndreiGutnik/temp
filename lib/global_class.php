<?php
/*абстрактный родительский класс, который содержит методы,
присущие всем дочерним классам работы с данными для
каждой таблицы*/

require_once "config_class.php";
require_once "checkvalid_class.php";
require_once "database_class.php";

abstract class GlobalClass{
    
    private $db;
    private $table_name;
    protected $config;
    protected $valid;
    
    protected function __construct($table_name, $db){
        $this->db=$db;
        $this->table_name=$table_name;
        $this->config=new Config();
        $this->valid=new CheckValid();
    }

/*добавление новой записи*/
    protected function add($new_values){
        return $this->db->insert($this->table_name, $new_values);
    }
    
/*обновление записи по id*/
    protected function edit($id, $upd_fields){
        return $this->db->updateOnID($this->table_name, $id, $upd_fields);
    }
    
/*удалени записи по id*/
    public function delete($id){
        return $this->db->deleteOnID($this->table_name, $id);
    }
    
/*удвление всего*/
    public function deleteAll(){
        return $this->db->deleteAll($this->table_name);
    }
    
/* ----- удвление всех записей по полю]*/
    public function deleteAllOnField($field, $value){
        return $this->db->delete($this->table_name, "`$field`='".addslashes($value)."'");
    }
    
/* ----- Удаление всех записей по двум полям*/
    public function deleteAllOnFields($field1, $value1, $field2, $value2){
        return $this->db->delete($this->table_name, "`$field1`='".addslashes($value1)."' AND `$field2`='".addslashes($value2)."'");
    }

/* ----- Добавление записей*/
    public function insertOnField($values){
        return $this->db->insert($this->table_name, $values);
    }


/*получение поля по известному значению другого поля*/
    protected function getField($field_out, $field_in, $value_in){
        return $this->db->getField($this->table_name, $field_out, $field_in, $value_in);
    }
    
/*получение значения поля по id*/
    protected function getFieldOnID($id, $field){
        return $this->db->getFieldOnID($this->table_name, $id, $field);
    }
    
/*изменение значения поля по id*/
    protected function setFieldOnID($id, $field, $value){
        return $this->db->setFieldOnIDdb($this->table_name, $id, $field, $value);
    }
    
/*получение всей записи целиком по id*/
    public function get($id){
        return $this->db->getElementOnID($this->table_name, $id);
    }
    
/*получение всех записей*/
    public function getAlls($order="", $up=true){
        return $this->db->getAll($this->table_name, $order, $up);
    }
    
/*получение всех полей записи по определенному полю*/
    protected function getAllOnFields($field, $value, $group="", $order="", $up=true){
        return $this->db->getAllOnField($this->table_name, $field, $value, $group, $order, $up);
    }

/* ----- Получение всех записей по двум полям*/ 
    public function getAllOnTwoField($field1, $value1, $field2, $value2, $group="", $order="", $up=true){
        return $this->db->getAllsOnFields($this->table_name, $field1, $value1, $field2, $value2, $group="", $order, $up);
    }
    
/* ----- Добавление записи по условию И*/ 
    public function setFieldOnWhere($values, $where, $and){
        return $this->db->update($this->table_name, $values, $where, $and);
    }    

/* ----- получение всех записей определенного поля*/
    protected function getAllRecordsOnField($field, $group="", $order="", $up=true){
        return $this->db->getAllsRecordsOnField($this->table_name, $field, $group, $order, $up);
    }

/* ----- получение полей по значению определенного поля*/
public function getFieldsOnValueField($field_out, $field_in, $value_in, $group, $order, $up){
    return $this->db->getFieldOnValueField($this->table_name, $field_out, $field_in, $value_in, $group, $order, $up);
}

/* ----- Получение полей объединенных таблиц по условию*/
public function getFieldsOnInnerTable($table_name_second, $inner_field, $fields, $field1, $value1, $field2, $value2, $group, $order, $up){
    return $this->db->getFieldsOnTable($this->table_name, $table_name_second, $inner_field, $fields, $field1, $value1, $field2, $value2, $group, $order, $up);
} 

/*получение случайных записей в определенном количестве*/
    public function getRandomElements($count){
        return $this->db->getRandomElements($this->table_name, $count);
    }
    
/*получение id последней вставленной записи*/
    public function getLastID(){
        return $this->db->getLastID($this->table_name);
    }
    
/*вывод количества записей в таблице*/
    public function getCount(){
        return $this->db->getCount($this->table_name);
    }
    
/*проверка наличия записи в таблице по известному значению поля*/    
    public function isExist($field, $value){
        return $this->db->isExists($this->table_name, $field, $value);
    }

/*реализация поиска по ключевым словам в БД*/    
    protected function search($words, $fields){
        return $this->db->search($this->table_name, $words, $fields);
    }
}
?>