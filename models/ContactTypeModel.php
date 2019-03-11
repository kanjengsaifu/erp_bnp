<?php

require_once("BaseModel.php");
class ContactTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getContactTypeLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(contact_type_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_contact_type 
        WHERE contact_type_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getContactTypeBy($contact_type_name = ''){
        $sql = " SELECT contact_type_code, contact_type_name, contact_type_detail   
        FROM tb_contact_type 
        WHERE contact_type_name LIKE ('%$contact_type_name%') 
        ORDER BY contact_type_code  
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

    function getContactTypeByCode($code){
        $sql = " SELECT * 
        FROM tb_contact_type 
        WHERE contact_type_code = '$code' 
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

    function updateContactTypeByCode($code,$data = []){
        $sql = " UPDATE tb_contact_type SET     
        contact_type_name = '".static::$db->real_escape_string($data['contact_type_name'])."', 
        contact_type_detail = '".static::$db->real_escape_string($data['contact_type_detail'])."'  
        WHERE contact_type_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertContactType($data = []){
        $sql = " INSERT INTO tb_contact_type (
            contact_type_code,
            contact_type_name,
            contact_type_detail
        ) VALUES (
            '".$data['contact_type_code']."', 
            '".static::$db->real_escape_string($data['contact_type_name'])."', 
            '".static::$db->real_escape_string($data['contact_type_detail'])."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteContactTypeByCode($code){
        $sql = " DELETE FROM tb_contact_type WHERE contact_type_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>