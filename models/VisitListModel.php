<?php
require_once("BaseModel.php");

class VisitListModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getVisitListLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(visit_list_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_visit_list 
        WHERE visit_list_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getVisitListBy($name = ''){
        $sql = "SELECT *
        FROM tb_visit_list 
        WHERE visit_list_name LIKE ('%$name%') 
        ORDER BY visit_list_name
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

    function getVisitListByCode($code){
        $sql = " SELECT * 
        FROM tb_visit_list 
        WHERE visit_list_code = '$code' 
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

    function getVisitListByUserCode($user_code){
        $sql = "SELECT *
        FROM tb_visit_list
        WHERE user_code = '$user_code' 
        ORDER BY visit_list_name
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

    function updateVisitListByCode($code,$data = []){
        $sql = " UPDATE tb_visit_list SET 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        visit_list_prefix = '".static::$db->real_escape_string($data['visit_list_prefix'])."', 
        visit_list_name = '".static::$db->real_escape_string($data['visit_list_name'])."', 
        visit_list_lastname = '".static::$db->real_escape_string($data['visit_list_lastname'])."', 
        visit_list_address = '".static::$db->real_escape_string($data['visit_list_address'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        visit_list_mobile = '".static::$db->real_escape_string($data['visit_list_mobile'])."',  
        visit_list_line = '".static::$db->real_escape_string($data['visit_list_line'])."',  
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        visit_list_username = '".static::$db->real_escape_string($data['visit_list_username'])."', 
        visit_list_password = '".static::$db->real_escape_string($data['visit_list_password'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE visit_list_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertVisitList($data = []){
        $data['visit_list_code']=mysqli_real_escape_string(static::$db,$data['visit_list_code']);
        $data['visit_list_name']=mysqli_real_escape_string(static::$db,$data['visit_list_name']);
        $data['visit_list_lastname']=mysqli_real_escape_string(static::$db,$data['visit_list_lastname']);
        $data['visit_list_address']=mysqli_real_escape_string(static::$db,$data['visit_list_address']);
        $data['visit_list_mobile']=mysqli_real_escape_string(static::$db,$data['visit_list_mobile']);
        $data['visit_list_line']=mysqli_real_escape_string(static::$db,$data['visit_list_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['visit_list_username']=mysqli_real_escape_string(static::$db,$data['visit_list_username']);
        $data['visit_list_password']=mysqli_real_escape_string(static::$db,$data['visit_list_password']);

        $sql = " INSERT INTO tb_visit_list ( 
            visit_list_code,
            status_code,
            visit_list_prefix,
            visit_list_name, 
            visit_list_lastname,
            visit_list_address,
            village_id,
            visit_list_mobile,
            visit_list_line,
            profile_image,
            id_card_image,
            visit_list_username,
            visit_list_password,
            addby,
            adddate
            )  VALUES ('".  
            $data['visit_list_code']."','".
            $data['status_code']."','".
            $data['visit_list_prefix']."','".
            $data['visit_list_name']."','".
            $data['visit_list_lastname']."','".
            $data['visit_list_address']."','".
            $data['village_id']."','".
            $data['visit_list_mobile']."','".
            $data['visit_list_line']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['visit_list_username']."','".
            $data['visit_list_password']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteVisitListByCode($code){
        $sql = " DELETE FROM tb_visit_list WHERE visit_list_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>