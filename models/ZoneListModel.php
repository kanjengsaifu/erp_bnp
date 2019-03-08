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
        WHERE village_name LIKE ('%$name%') 
        ORDER BY village_name
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

    function getZoneListByZone($code){
        $sql = "SELECT *
        FROM tb_zone_list 
        LEFT JOIN tb_district ON tb_zone_list.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE zone_code = '$code'
        ORDER BY PROVINCE_NAME
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

    function updateZoneListByCode($code,$data = []){
        $data['village_name']=mysqli_real_escape_string(static::$db,$data['village_name']);

        $sql = "UPDATE tb_zone_list SET
        village_name = '".$data['village_name']."', 
        province_id = '".$data['province_id']."', 
        amphur_id = '".$data['amphur_id']."', 
        district_id = '".$data['district_id']."', 
        updateby = '".$data['updateby']."',
        lastupdate = NOW() 
        WHERE zone_list_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertZoneList($data = []){
        $data['zone_code']=mysqli_real_escape_string(static::$db,$data['zone_code']);
        $data['village_name']=mysqli_real_escape_string(static::$db,$data['village_name']);

        $sql = " INSERT INTO tb_zone_list ( 
            zone_list_code,
            zone_code,
            village_name, 
            province_id,
            amphur_id,
            district_id,
            addby,
            adddate 
            )  VALUES ('".  
            $data['zone_list_code']."','".
            $data['zone_code']."','".
            $data['village_name']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['addby']."',
            NOW())
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
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