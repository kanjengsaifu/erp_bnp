<?php
require_once("BaseModel.php");

class FarmerModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getFarmerLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(farmer_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_farmer 
        WHERE farmer_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }  
    
    function getFarmerByUserCode($user_code){
        $sql = "SELECT tb_farmer.farmer_code AS code,  CONCAT(farmer_prefix,' ',farmer_name,' ',farmer_lastname) as name 
        FROM tb_farmer   
        INNER JOIN tb_zone_list ON tb_farmer.district_id = tb_zone_list.district_id 
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    } 
  
  
}
?>