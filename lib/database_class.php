<?php
/*класс для работы с базой данных*/

require_once "config_class.php";
require_once "checkvalid_class.php";

class Database {
    
    private $config;
    private $mysqli;
    private $valid;

/*метод, подключения к БД*/    
    public function __construct(){
        $this->config=new Config();
        $this->valid=new CheckValid();
        $this->mysqli=new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->db);
        $this->mysqli->query("SET NAMES 'UTF-8'");
    }

/*функция, которая отправляет запрос и возвращает результат*/    
    private function query($query){
        return $this->mysqli->query($query);
    }
    
/*метод, занимающийся выборкой*/
    private function select($table_name, $fields, $where="", $group="", $order="", $up=true, $limit=""){
        for($i=0; $i<count($fields); $i++){
            if((strpos($fields[$i], "(") === false) && ($fields[$i]!="*")) $fields[$i]="`".$fields[$i]."`";
        }
        $fields=implode(",", $fields);
        $table_name=$this->config->db_prefix.$table_name;
        if($group) $group = "GROUP BY $group";
        if(!$order) $order="ORDER BY `id`";
        else {
            if($order!="RAID()"){
                $order="ORDER BY $order";
                if(!$up) $order.=" DESC";
            }
            else $order="ORDER BY $order";
        }
        if($limit) $limit="LIMIT $limit";
        if($where){
            $query="SELECT $fields FROM $table_name WHERE $where $group $order $limit";
        }
        else {
            $query="SELECT $fields FROM $table_name $group $order $limit";
        }
        $result_set=$this->query($query);
        if(!$result_set) return false;
        $i=0;
        while($row=$result_set->fetch_assoc()) {
            $data[$i]=$row;
            $i++;
        }
        $result_set->close();
        return $data;
    }
    
/*метод, занимающийся выборкой из двух связанных таблиц*/
    private function selectInner($table_name_first, $table_name_second, $inner_field, $fields, $where1, $where2, $group="", $order="", $up=true, $limit=""){
        for($i=0; $i<count($fields); $i++){
            if((strpos($fields[$i], "(") === false) && ($fields[$i]!="*")) $fields[$i]="`".$fields[$i]."`";
        }
        $fields=implode(",", $fields);
        $table_name_first=$this->config->db_prefix.$table_name_first;
        $table_name_second=$this->config->db_prefix.$table_name_second;
        if($group) $group = "GROUP BY $group";
        if(!$order) $order="ORDER BY `id`";
        else {
            if($order!="RAID()"){
                $order="ORDER BY $order";
                if(!$up) $order.=" DESC";
            }
            else $order="ORDER BY $order";
        }
        if($limit) $limit="LIMIT $limit";
        $inner = "$table_name_first."."$inner_field=$table_name_second."."$inner_field";     
        
        
        $query="SELECT $fields FROM $table_name_first INNER JOIN $table_name_second ON $inner WHERE $where1 AND $where2 $group $order $limit";
        $result_set=$this->query($query);
        if(!$result_set) return false;
        $i=0;
        while($row=$result_set->fetch_assoc()) {
            $data[$i]=$row;
            $i++;
        }
        $result_set->close();
        return $data;
    }

/*метод, добавляющий записи*/
    public function insert($table_name, $new_values){
        $table_name = $this->config->db_prefix.$table_name;
        $query = "INSERT INTO $table_name (";
        foreach($new_values as $field=>$value) $query.="`".$field."`,";
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";
        foreach($new_values as $value) $query .= "'".addslashes($value)."',";
        $query = substr($query, 0, -1);
        $query .= ")";
        //echo $query;
        return $this->query($query);
    }
    
/*метод обновления записей*/
    public function update($table_name, $upd_values, $where="", $and=""){
        $table_name=$this->config->db_prefix.$table_name;
        $query="UPDATE $table_name SET";
        foreach($upd_values as $field=>$value) $query.="`$field`='".addslashes($value)."',";
        $query=substr($query, 0, -1);
        
        if($where){
            $query .= " WHERE ";
            if($and){
                foreach($where as $field=>$value) $query.="`".$field."`='".addslashes($value)."' ".$and." ";
                $query = substr($query, 0, -4);
                return $this->query($query);
                //echo $query;
            }
            else {
                foreach($where as $field=>$value) $query.="`".$field."`='".addslashes($value)."'";
                return $this->query($query);
            }
        }        
        else return false;
    }
    
/*метод удаления записей*/
    public function delete($table_name, $where=""){
        $table_name=$this->config->db_prefix.$table_name;
        if($where) {
            $query="DELETE FROM $table_name WHERE $where";
            return $this->query($query);
        }
        echo $query;
        //else return false;
    }
    
/*метод очищения таблицы*/
    public function alldelete($table_name){
        $table_name=$this->config->db_prefix.$table_name;
        $query="TRUNCATE TABLE `$table_name`";
        return $this->query($query);
    }

/*получение значения искомого поля по знчяению известного поля*/
    public function getField($table_name, $field_out, $field_in, $value_in) {
        $data=$this->select($table_name, array($field_out), "`$field_in`='$value_in'");
        if(count($data)!=1) return false;
        return $data[0][$field_out];
    }
    
/*получение поля по id*/
    public function getFieldOnID($table_name, $id, $field_out){
        if(!$this->existsID($table_name, $id)) return false;
        return $this->getField($table_name, $field_out, "id", $id);
    }
    
/*получение всех записей из таблицы*/
    public function getAll($table_name, $order, $up) {
        return $this->select($table_name, array("*"), "", $order, $up);
    }
    
/*получение всех записей по определенному полю*/
    public function getAllOnField($table_name, $field, $value, $group="", $order, $up){
        return $this->select($table_name, array("*"), "`$field`='".addslashes($value)."'", $group, $order, $up);
    }
    
/* ------ получение всех записей по нескольким полям*/
    public function getAllsOnFields($table_name, $field1, $value1, $field2, $value2, $group="", $order, $up){
        return $this->select($table_name, array("*"), "`$field1`='".addslashes($value1)."' AND `$field2`='".addslashes($value2)."'", $group, $order, $up);
    }
    
/* ----- получение всех записей определенного поля*/
    public function getAllsRecordsOnField($table_name, $field, $group, $order, $up){
        return $this->select($table_name, array($field), "", $group, $order, $up);
    }

/* ----- получение поля по значению определенного поля*/
    public function getFieldOnValueField($table_name, $field_out, $field_in, $value_in, $group, $order, $up){
        return $this->select($table_name, array($field_out), "`$field_in`='".addslashes($value_in)."'", $group, $order, $up);
    }

/* ----- получение полей объединенных таблиц*/
    public function getFieldsOnTable($table_name_first, $table_name_second, $inner_field, $fields, $field1, $value1, $field2, $value2, $group="", $order="", $up=true){
        return $this->selectInner($table_name_first, $table_name_second, $inner_field, $fields, "`$field1`='".addslashes($value1)."'", "`$field2`='".addslashes($value2)."'", $group, $order, $up);
    }

    
/*удаление записи по id*/
    public function deleteFieldOnID($table_name, $id){
        if(!$this->existsID($table_name, $id)) return false;
        return $this->delete($table_name, "`id`='$id'");
    }
    
/*изменение значения определенного поля*/
    public function setField($table_name, $field, $value, $field_in, $value_in){
        return $this->update($table_name, array($field=>$value), "`$field_in`='".addslashes($value_in)."'");
    }
    
/*изменение значения по id*/
    public function setFieldOnIDdb($table_name, $id, $field, $value){
        if($this->existsID($table_name, $id)) return false;
        return $this->setField($table_name, $field, $value, "id", $id);
    }
    
/*получение всей записи целиком по id*/
    public function getElementOnID($table_name, $id){
        if($this->existsID($table_name, $id)) return false;
        $arr=$this->select($table_name, array("*"), "`id`='".addslashes($id)."'");
        return $arr[0];
    }
    
/*получение случайных записей в определенном количестве*/
    public function getRandomElements($table_name, $count){
        return $this->select($table_name, array("*"), "", "RAND()", true, $count);
    }
    
/*вывод количества записей в таблице*/
    public function getCount($table_name){
        $data=$this->select($table_name, array("COUNT('id')"));
        return  $data[0]["COUNT('id')"];
    }
    
/*получение id последней вставленной записи*/
    public function getLastID($table_name){
        $data=$this->select($table_name, array("MAX('id')"));
        return  $data[0]["MAX('id')"];
    }

/*проверка наличия записи в таблице по значению*/    
    public function isExists($table_name, $field, $value){
        $data=$this->select($table_name, array("id"), "`$field`='".addslashes($value)."'");
        if(count($data)===0) return false;
        return true;
    }
    
/*проверка на корректность id в таблице*/
    private function existsID($table_name, $id){
        if($this->valid->validID($id)) return false;
        $data=$this->select($table_name, array("id"), "`id`='".addslashes($id)."'");
        if(count($data)===0) return false;
        return true;
    }

/*поиск в таблице БД по ключевым словам в заданных полях таблицы*/    
    public function search($table_name, $words, $fields){
        $words=mb_strtolower($words);
        $words=trim($words);
        /*функция для экранирования всех спецсимволов*/
        $words=quotemeta($words);
        if($words=="") return false;
        $where="";
        $arraywords=explode(" ", $words);
        $logic="OR";
        foreach($arraywords as $key=>$value){
            if(isset($arraywords[$key-1])) $where.=$logic;
            for($i=0;$i<count($fields);$i++){
                $where.="`".$fields[$i]."` LIKE '%".addslashes($value)."%'";
                if(($i+1)!=count($fields)) $where.=" OR";
            }
        }
        $results=$this->select($table_name, array("*"), $where);
        if(!$results) return false;
        $k=0;
        $data=array();
        for($i=0; $i < count($results); $i++){
            for($j=0; $j < count($fields); $j++){
                $results[$i][$fields[$j]]=mb_strtolower(strip_tags($results[$i][$fields[$j]]));
            }
            $data[$k]=$results[$i];
            $data[$k]["relevant"]=$this->getRelevantForSearch($results[$i], $fields, $words);
            $k++;
        }
        $data = $this->orderResultSearch($data, "relevant");
        return $data;
    }
    
/*метод подсчета числа совпадений*/    
    private function getRelevantForSearch($result, $fields, $words){
        $relevant = 0;
        $arraywords = explode(" ", $words);
        for($i = 0; $i < count($fields); $i++){
            for($j = 0; $j < count($arraywords); $j++){
                $relevant += substr_count($result[$fields[$i]], $arraywords[$j]);   
            }
        }
        return $relevant;
    }
    
/*метод по сортировке поиска*/
    private function orderResultSearch($data, $order){
        for($i = 0; $i < count($data)-1; $i++){
            $k = $i;
            for($j = $i + 1; $j < count($data); $j++){
                if($data[$j][$order] > $data[$k][$order]) $k = $j;
            }
            $temp = $data[$k];
            $data[$k]  = $data[$i];
            $data[$i] = $temp;
        }
        return $data;
    }
    
    public function __destruct(){
        if($this->mysqli) $this->mysqli->close();
    }
}
?>