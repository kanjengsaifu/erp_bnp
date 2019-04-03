<?php
require_once("BaseModel.php");

class ContractorModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
    }

    function getContractorLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(contractor_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0')) AS lastcode 
        FROM tb_contractor 
        WHERE contractor_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getContractorBy($name = '', $mobile  = ''){
        $sql = "SELECT contractor_code, contractor_prefix, CONCAT(contractor_name,' ',contractor_lastname) as name,
        contractor_mobile, contractor_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_contractor 
        LEFT JOIN tb_village ON tb_contractor.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) LIKE ('%$name%') 
        AND contractor_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) 
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

    function getContractorByUserCode($user_code){
        $sql = "SELECT tb_contractor.contractor_code AS code,  CONCAT(contractor_prefix,' ',contractor_name,' ',contractor_lastname) as name 
        FROM tb_contractor  
        INNER JOIN tb_zone_contractor ON tb_contractor.contractor_code = tb_zone_contractor.contractor_code 
        INNER JOIN tb_zone_call_center ON tb_zone_contractor.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) 
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

    function getContractorByCode($code){
        $sql = " SELECT * , CONCAT(contractor_name,' ',contractor_lastname) as name
        FROM tb_contractor 
        LEFT JOIN tb_status ON tb_contractor.status_code = tb_status.status_code 
        LEFT JOIN tb_village ON tb_contractor.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE contractor_code = '$code' 
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

    function getContractorByStatus($code){
        $sql = " SELECT contractor_code, contractor_prefix, CONCAT(contractor_name,' ',contractor_lastname) as name,
        contractor_mobile, contractor_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_contractor 
        LEFT JOIN tb_village ON tb_contractor.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE status_code = '$code' 
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

    function countContractorByStatus($code){
        $sql = " SELECT COUNT(contractor_code) AS total
        FROM tb_contractor 
        WHERE status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function getContractorNotInZone($code){
        $sql = "SELECT contractor_code, contractor_prefix, CONCAT(contractor_name,' ',contractor_lastname) as name,
        contractor_mobile, contractor_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_contractor 
        LEFT JOIN tb_village ON tb_contractor.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE status_code != '00'
        AND contractor_code NOT IN (
            SELECT contractor_code
            FROM tb_zone_contractor 
            WHERE zone_code = '$code'
            GROUP BY contractor_code
        )
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

    function approveContractorByCode($code){
        $sql = " UPDATE tb_contractor SET 
        status_code = '01' 
        WHERE contractor_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateContractorByCode($code,$data = []){
        $sql = " UPDATE tb_contractor SET     
        contractor_prefix = '".static::$db->real_escape_string($data['contractor_prefix'])."', 
        contractor_name = '".static::$db->real_escape_string($data['contractor_name'])."', 
        contractor_lastname = '".static::$db->real_escape_string($data['contractor_lastname'])."', 
        contractor_address = '".static::$db->real_escape_string($data['contractor_address'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        contractor_mobile = '".static::$db->real_escape_string($data['contractor_mobile'])."', 
        contractor_line = '".static::$db->real_escape_string($data['contractor_line'])."', 
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        house_regis_image = '".static::$db->real_escape_string($data['house_regis_image'])."', 
        account_image = '".static::$db->real_escape_string($data['account_image'])."', 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        contractor_signature = '".$data['contractor_signature']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE contractor_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertContractor($data = []){
        $data['contractor_code']=mysqli_real_escape_string(static::$db,$data['contractor_code']);
        $data['contractor_name']=mysqli_real_escape_string(static::$db,$data['contractor_name']);
        $data['contractor_lastname']=mysqli_real_escape_string(static::$db,$data['contractor_lastname']);
        $data['contractor_address']=mysqli_real_escape_string(static::$db,$data['contractor_address']);
        $data['contractor_mobile']=mysqli_real_escape_string(static::$db,$data['contractor_mobile']);
        $data['contractor_line']=mysqli_real_escape_string(static::$db,$data['contractor_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['house_regis_image']=mysqli_real_escape_string(static::$db,$data['house_regis_image']);
        $data['account_image']=mysqli_real_escape_string(static::$db,$data['account_image']);

        $sql = " INSERT INTO tb_contractor ( 
            contractor_code,
            status_code,
            contractor_prefix,
            contractor_name, 
            contractor_lastname,
            contractor_address,
            village_id,
            contractor_mobile,
            contractor_line,
            profile_image,
            id_card_image,
            house_regis_image,
            account_image,
            contractor_signature,
            addby,
            adddate
            )  VALUES ('".  
            $data['contractor_code']."','".
            $data['status_code']."','".
            $data['contractor_prefix']."','".
            $data['contractor_name']."','".
            $data['contractor_lastname']."','".
            $data['contractor_address']."','".
            $data['village_id']."','".
            $data['contractor_mobile']."','".
            $data['contractor_line']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['house_regis_image']."','".
            $data['account_image']."','".
            $data['contractor_signature']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteContractorByCode($code){
        $sql = " DELETE FROM tb_contractor WHERE contractor_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>