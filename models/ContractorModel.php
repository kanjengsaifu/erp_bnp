<?php
require_once("BaseModel.php");

class ContractorModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->contractorname, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getContractorLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(contractor_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
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
        contractor_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_contractor 
        LEFT JOIN tb_district ON tb_contractor.district_id = tb_district.DISTRICT_ID 
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

    function getContractorByCode($code){
        $sql = " SELECT * 
        FROM tb_contractor 
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
        contractor_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_contractor 
        LEFT JOIN tb_district ON tb_contractor.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE contractor_status_code = '$code' 
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
        WHERE contractor_status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function updateContractorByCode($code,$data = []){
        $sql = " UPDATE tb_contractor SET     
        contractor_prefix = '".static::$db->real_escape_string($data['contractor_prefix'])."', 
        contractor_name = '".static::$db->real_escape_string($data['contractor_name'])."', 
        contractor_lastname = '".static::$db->real_escape_string($data['contractor_lastname'])."', 
        contractor_mobile = '".static::$db->real_escape_string($data['contractor_mobile'])."', 
        contractor_address = '".static::$db->real_escape_string($data['contractor_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        contractor_zipcode = '".static::$db->real_escape_string($data['contractor_zipcode'])."', 
        contractor_image = '".static::$db->real_escape_string($data['contractor_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        house_regis_image = '".static::$db->real_escape_string($data['house_regis_image'])."', 
        account_image = '".static::$db->real_escape_string($data['account_image'])."', 
        contractor_status_code = '".static::$db->real_escape_string($data['contractor_status_code'])."' 
        WHERE contractor_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateContractorSignatureByCode($code,$data = []){
        $sql = " UPDATE tb_contractor SET 
        contractor_signature = '".$data['contractor_signature']."' 
        WHERE contractor_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertContractor($data = []){
        $data['contractor_name']=mysqli_real_escape_string(static::$db,$data['contractor_name']);
        $data['contractor_lastname']=mysqli_real_escape_string(static::$db,$data['contractor_lastname']);
        $data['contractor_mobile']=mysqli_real_escape_string(static::$db,$data['contractor_mobile']);
        $data['contractor_image']=mysqli_real_escape_string(static::$db,$data['contractor_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['house_regis_image']=mysqli_real_escape_string(static::$db,$data['house_regis_image']);
        $data['account_image']=mysqli_real_escape_string(static::$db,$data['account_image']);
        $data['contractor_address']=mysqli_real_escape_string(static::$db,$data['contractor_address']);
        $data['contractor_zipcode']=mysqli_real_escape_string(static::$db,$data['contractor_zipcode']);

        $sql = " INSERT INTO tb_contractor ( 
            contractor_code,
            contractor_prefix,
            contractor_name, 
            contractor_lastname,
            contractor_mobile,
            contractor_address,
            province_id,
            amphur_id,
            district_id,
            contractor_zipcode,
            contractor_image,
            id_card_image,
            house_regis_image,
            account_image,
            contractor_status_code 
            )  VALUES ('".  
            $data['contractor_code']."','".
            $data['contractor_prefix']."','".
            $data['contractor_name']."','".
            $data['contractor_lastname']."','".
            $data['contractor_mobile']."','".
            $data['contractor_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['contractor_zipcode']."','".
            $data['contractor_image']."','".
            $data['id_card_image']."','".
            $data['house_regis_image']."','".
            $data['account_image']."','".
            $data['contractor_status_code']."')
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['contractor_code'];
        }else {
            return '';
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