<?php
require_once("BaseModel.php");

class VisitModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getVisitLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(visit_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_visit 
        WHERE visit_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getVisitBy($name = ''){
        $sql = "SELECT *
        FROM tb_visit 
        WHERE visit_name LIKE ('%$name%') 
        ORDER BY visit_name
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getVisitByCode($code){
        $sql = " SELECT * 
        FROM tb_visit 
        WHERE visit_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getVisitByUserCode($user_code){
        $sql = "SELECT *
        FROM tb_visit
        WHERE user_code = '$user_code' 
        ORDER BY visit_name
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    } 

    function updateVisitByCode($code,$data = []){
        $sql = " UPDATE tb_visit SET 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        visit_prefix = '".static::$db->real_escape_string($data['visit_prefix'])."', 
        visit_name = '".static::$db->real_escape_string($data['visit_name'])."', 
        visit_lastname = '".static::$db->real_escape_string($data['visit_lastname'])."', 
        visit_address = '".static::$db->real_escape_string($data['visit_address'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        visit_mobile = '".static::$db->real_escape_string($data['visit_mobile'])."',  
        visit_line = '".static::$db->real_escape_string($data['visit_line'])."',  
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        visit_username = '".static::$db->real_escape_string($data['visit_username'])."', 
        visit_password = '".static::$db->real_escape_string($data['visit_password'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE visit_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertVisit($data = []){
        $data['visit_code']=mysqli_real_escape_string(static::$db,$data['visit_code']);
        $data['visit_name']=mysqli_real_escape_string(static::$db,$data['visit_name']);
        $data['visit_lastname']=mysqli_real_escape_string(static::$db,$data['visit_lastname']);
        $data['visit_address']=mysqli_real_escape_string(static::$db,$data['visit_address']);
        $data['visit_mobile']=mysqli_real_escape_string(static::$db,$data['visit_mobile']);
        $data['visit_line']=mysqli_real_escape_string(static::$db,$data['visit_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['visit_username']=mysqli_real_escape_string(static::$db,$data['visit_username']);
        $data['visit_password']=mysqli_real_escape_string(static::$db,$data['visit_password']);

        $sql = " INSERT INTO tb_visit ( 
            visit_code,
            status_code,
            visit_prefix,
            visit_name, 
            visit_lastname,
            visit_address,
            village_id,
            visit_mobile,
            visit_line,
            profile_image,
            id_card_image,
            visit_username,
            visit_password,
            addby,
            adddate
            )  VALUES ('".  
            $data['visit_code']."','".
            $data['status_code']."','".
            $data['visit_prefix']."','".
            $data['visit_name']."','".
            $data['visit_lastname']."','".
            $data['visit_address']."','".
            $data['village_id']."','".
            $data['visit_mobile']."','".
            $data['visit_line']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['visit_username']."','".
            $data['visit_password']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteVisitByCode($code){
        $sql = " DELETE FROM tb_visit WHERE visit_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>