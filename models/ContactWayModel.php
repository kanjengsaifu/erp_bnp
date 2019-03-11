<?php

require_once("BaseModel.php");
class ContactWayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getContactWayLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(contact_way_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_contact_way  
        WHERE contact_way_code LIKE ('$code%') 
        ";
        echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getContactWayBy($contact_way_name = ''){
        $sql = " SELECT contact_way_code, contact_way_name, contact_way_detail   
        FROM tb_contact_way 
        WHERE contact_way_name LIKE ('%$contact_way_name%') 
        ORDER BY contact_way_code  
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

    function getContactWayByCode($code){
        $sql = " SELECT * 
        FROM tb_contact_way 
        WHERE contact_way_code = '$code' 
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

    function updateContactWayByCode($code,$data = []){
        $sql = " UPDATE tb_contact_way SET     
        contact_way_name = '".static::$db->real_escape_string($data['contact_way_name'])."', 
        contact_way_detail = '".static::$db->real_escape_string($data['contact_way_detail'])."'  
        WHERE contact_way_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertContactWay($data = []){
        $sql = " INSERT INTO tb_contact_way (
            contact_way_code,
            contact_way_name,
            contact_way_detail
        ) VALUES (
            '".$data['contact_way_code']."', 
            '".static::$db->real_escape_string($data['contact_way_name'])."', 
            '".static::$db->real_escape_string($data['contact_way_detail'])."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteContactWayByCode($code){
        $sql = " DELETE FROM tb_contact_way WHERE contact_way_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>