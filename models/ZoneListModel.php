<?php
require_once("BaseModel.php");

class ZoneListModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->zone_listname, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getZoneListLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(zone_list_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_zone_list 
        WHERE zone_list_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getZoneListBy($name = ''){
        $sql = "SELECT *
        FROM tb_zone_list 
        WHERE zone_list_name LIKE ('%$name%') 
        ORDER BY zone_list_name
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

    function getZoneListByCode($code){
        $sql = " SELECT * 
        FROM tb_zone_list 
        WHERE zone_list_code = '$code' 
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

    function updateZoneListByCode($code,$data = []){
        $sql = " UPDATE tb_zone_list SET     
        zone_list_prefix = '".static::$db->real_escape_string($data['zone_list_prefix'])."', 
        zone_list_name = '".static::$db->real_escape_string($data['zone_list_name'])."', 
        zone_list_lastname = '".static::$db->real_escape_string($data['zone_list_lastname'])."', 
        zone_list_mobile = '".static::$db->real_escape_string($data['zone_list_mobile'])."', 
        zone_list_address = '".static::$db->real_escape_string($data['zone_list_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        zone_list_zipcode = '".static::$db->real_escape_string($data['zone_list_zipcode'])."', 
        zone_list_image = '".static::$db->real_escape_string($data['zone_list_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        house_regis_image = '".static::$db->real_escape_string($data['house_regis_image'])."', 
        account_image = '".static::$db->real_escape_string($data['account_image'])."', 
        zone_list_status_code = '".static::$db->real_escape_string($data['zone_list_status_code'])."' 
        WHERE zone_list_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertZoneList($data = []){
        $data['zone_list_name']=mysqli_real_escape_string(static::$db,$data['zone_list_name']);
        $data['zone_list_lastname']=mysqli_real_escape_string(static::$db,$data['zone_list_lastname']);
        $data['zone_list_mobile']=mysqli_real_escape_string(static::$db,$data['zone_list_mobile']);
        $data['zone_list_image']=mysqli_real_escape_string(static::$db,$data['zone_list_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['house_regis_image']=mysqli_real_escape_string(static::$db,$data['house_regis_image']);
        $data['account_image']=mysqli_real_escape_string(static::$db,$data['account_image']);
        $data['zone_list_address']=mysqli_real_escape_string(static::$db,$data['zone_list_address']);
        $data['zone_list_zipcode']=mysqli_real_escape_string(static::$db,$data['zone_list_zipcode']);

        $sql = " INSERT INTO tb_zone_list ( 
            zone_list_code,
            zone_list_prefix,
            zone_list_name, 
            zone_list_lastname,
            zone_list_mobile,
            zone_list_address,
            province_id,
            amphur_id,
            district_id,
            zone_list_zipcode,
            zone_list_image,
            id_card_image,
            house_regis_image,
            account_image,
            zone_list_status_code 
            )  VALUES ('".  
            $data['zone_list_code']."','".
            $data['zone_list_prefix']."','".
            $data['zone_list_name']."','".
            $data['zone_list_lastname']."','".
            $data['zone_list_mobile']."','".
            $data['zone_list_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['zone_list_zipcode']."','".
            $data['zone_list_image']."','".
            $data['id_card_image']."','".
            $data['house_regis_image']."','".
            $data['account_image']."','".
            $data['zone_list_status_code']."')
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['zone_list_code'];
        }else {
            return '';
        }
    }

    function deleteZoneListByCode($code){
        $sql = " DELETE FROM tb_zone_list WHERE zone_list_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>