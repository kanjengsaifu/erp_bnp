<?php
require_once("BaseModel.php");

class ZoneContractorModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getZoneContractorLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(zone_contractor_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_zone_contractor 
        WHERE zone_contractor_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getZoneContractorBy($code){
        $sql = "SELECT zone_contractor_code, tb_contractor.contractor_code, contractor_prefix, CONCAT(contractor_name,' ',contractor_lastname) as name,
        contractor_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_zone_contractor
        LEFT JOIN tb_contractor ON tb_zone_contractor.contractor_code = tb_contractor.contractor_code 
        LEFT JOIN tb_district ON tb_contractor.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE zone_code = '$code'
        ORDER BY CONCAT(contractor_name,' ',contractor_lastname) 
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

    function getZoneContractorByCode($code){
        $sql = " SELECT * 
        FROM tb_zone_contractor 
        WHERE zone_contractor_code = '$code' 
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

    function insertZoneContractor($data = []){
        $data['zone_contractor_code']=mysqli_real_escape_string(static::$db,$data['zone_contractor_code']);
        $data['zone_code']=mysqli_real_escape_string(static::$db,$data['zone_code']);
        $data['contractor_code']=mysqli_real_escape_string(static::$db,$data['contractor_code']);

        $sql = " INSERT INTO tb_zone_contractor ( 
            zone_contractor_code,
            zone_code,
            contractor_code,
            addby,
            adddate
            )  VALUES ('".  
            $data['zone_contractor_code']."','".
            $data['zone_code']."','".
            $data['contractor_code']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteZoneContractorByCode($code){
        $sql = " DELETE FROM tb_zone_contractor WHERE zone_contractor_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>