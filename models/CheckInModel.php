<?php
require_once("BaseModel.php");

class CheckInModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getCheckInLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(check_in_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_check_in 
        WHERE check_in_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getCheckInBy($name = ''){
        $sql = "SELECT *
        FROM tb_check_in
        LEFT JOIN tb_check_in_type ON tb_check_in.check_in_type_code = tb_check_in_type.check_in_type_code
        WHERE check_in_topic LIKE ('%$name%') 
        ORDER BY tb_check_in.check_in_type_code
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

    function getCheckInByCode($code){
        $sql = " SELECT * 
        FROM tb_check_in 
        WHERE check_in_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getCheckInByType($type){
        $sql = "SELECT *
        FROM tb_check_in
        WHERE check_in_type_code = '$type' 
        ORDER BY check_in_type_code
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

    function updateCheckInByCode($code,$data = []){
        $sql = " UPDATE tb_check_in SET 
        check_in_type_code = '".static::$db->real_escape_string($data['check_in_type_code'])."', 
        check_in_topic = '".static::$db->real_escape_string($data['check_in_topic'])."', 
        score = '".static::$db->real_escape_string($data['score'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE check_in_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCheckIn($data = []){
        $data['check_in_code']=mysqli_real_escape_string(static::$db,$data['check_in_code']);
        $data['check_in_topic']=mysqli_real_escape_string(static::$db,$data['check_in_topic']);

        $sql = " INSERT INTO tb_check_in (
            check_in_code,
            check_in_type_code,
            check_in_topic, 
            score,
            addby,
            adddate
            )  VALUES ('".  
            $data['check_in_code']."','".
            $data['check_in_type_code']."','".
            $data['check_in_topic']."','".
            $data['score']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteCheckInByCode($code){
        $sql = " DELETE FROM tb_check_in WHERE check_in_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>