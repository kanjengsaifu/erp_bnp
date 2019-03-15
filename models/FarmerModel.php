<?php
require_once("BaseModel.php");

class FarmerModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
    }

    function getFarmerLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(farmer_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_farmer 
        WHERE farmer_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getFarmerBy($name = '', $mobile  = ''){
        $sql = "SELECT farmer_code, farmer_prefix, CONCAT(farmer_name,' ',farmer_lastname) as name,
        farmer_mobile, farmer_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_farmer 
        LEFT JOIN tb_village ON tb_farmer.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) LIKE ('%$name%') 
        AND farmer_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) 
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

    function getFarmerByCode($code){
        $sql = " SELECT * 
        FROM tb_farmer 
        LEFT JOIN tb_village ON tb_farmer.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE farmer_code = '$code' 
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

    function updateFarmerByCode($code,$data = []){
        $sql = " UPDATE tb_farmer SET     
        farmer_prefix = '".static::$db->real_escape_string($data['farmer_prefix'])."', 
        farmer_name = '".static::$db->real_escape_string($data['farmer_name'])."', 
        farmer_lastname = '".static::$db->real_escape_string($data['farmer_lastname'])."', 
        farmer_address = '".static::$db->real_escape_string($data['farmer_address'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        farmer_mobile = '".static::$db->real_escape_string($data['farmer_mobile'])."', 
        farmer_line = '".static::$db->real_escape_string($data['farmer_line'])."', 
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE farmer_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertFarmer($data = []){
        $data['farmer_code']=mysqli_real_escape_string(static::$db,$data['farmer_code']);
        $data['farmer_name']=mysqli_real_escape_string(static::$db,$data['farmer_name']);
        $data['farmer_lastname']=mysqli_real_escape_string(static::$db,$data['farmer_lastname']);
        $data['farmer_address']=mysqli_real_escape_string(static::$db,$data['farmer_address']);
        $data['farmer_mobile']=mysqli_real_escape_string(static::$db,$data['farmer_mobile']);
        $data['farmer_line']=mysqli_real_escape_string(static::$db,$data['farmer_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);

        $sql = " INSERT INTO tb_farmer ( 
            farmer_code,
            farmer_prefix,
            farmer_name, 
            farmer_lastname,
            farmer_address,
            province_id,
            amphur_id,
            district_id,
            village_id,
            farmer_mobile,
            farmer_line,
            profile_image,
            addby,
            adddate 
            )  VALUES ('".  
            $data['farmer_code']."','".
            $data['farmer_prefix']."','".
            $data['farmer_name']."','".
            $data['farmer_lastname']."','".
            $data['farmer_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['village_id']."','".
            $data['farmer_mobile']."','".
            $data['farmer_line']."','".
            $data['profile_image']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteFarmerByCode($code){
        $sql = " DELETE FROM tb_farmer WHERE farmer_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>