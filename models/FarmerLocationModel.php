<?php
require_once("BaseModel.php");

class FarmerLocationModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
    }

    function getFarmerLocationLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(location_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_farmer_location 
        WHERE location_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }  

    function getFarmerLocationBy($code){
        $sql = "SELECT *
        FROM tb_farmer_location 
        WHERE farmer_code = '$code'
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

    function getFarmerLocationByCode($code){
        $sql = " SELECT * 
        FROM tb_farmer_location 
        WHERE location_code = '$code' 
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

    function updateFarmerLocationByCode($code,$data = []){
        $sql = " UPDATE tb_farmer_location SET     
        location_lat = '".static::$db->real_escape_string($data['location_lat'])."', 
        location_long = '".static::$db->real_escape_string($data['location_long'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE location_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertFarmerLocation($data = []){
        $data['location_code']=mysqli_real_escape_string(static::$db,$data['location_code']);
        $data['farmer_code']=mysqli_real_escape_string(static::$db,$data['farmer_code']);
        $data['location_lat']=mysqli_real_escape_string(static::$db,$data['location_lat']);
        $data['location_long']=mysqli_real_escape_string(static::$db,$data['location_long']);

        $sql = " INSERT INTO tb_farmer_location ( 
            location_code,
            farmer_code,
            location_lat, 
            location_long,
            addby,
            adddate 
            )  VALUES ('".  
            $data['location_code']."','".
            $data['farmer_code']."','".
            $data['location_lat']."','".
            $data['location_long']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteFarmerLocationByCode($code){
        $sql = " DELETE FROM tb_farmer_location WHERE location_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteFarmerLocationBy($code){
        $sql = " DELETE FROM tb_farmer_location WHERE farmer_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>