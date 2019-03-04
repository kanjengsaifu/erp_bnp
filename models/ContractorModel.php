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

    function getLogin($contractorname, $password){
        $contractorname = static::$db->real_escape_string($contractorname);
        $password = static::$db->real_escape_string($password);

        if ($result = mysqli_query(static::$db,"SELECT * 
        FROM tb_contractor LEFT JOIN tb_license ON tb_contractor.license_code = tb_license.license_code 
        WHERE contractor_contractorname = '$contractorname' 
        AND contractor_password = '$password' ", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getContractorBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT contractor_code, contractor_code, contractor_profile_img , CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) as name , contractor_mobile, contractor_email, contractor_position_name, contractor_status_name  
        FROM tb_contractor LEFT JOIN tb_contractor_position ON tb_contractor.contractor_position_code = tb_contractor_position.contractor_position_code 
        LEFT JOIN tb_contractor_status ON tb_contractor.contractor_status_code = tb_contractor_status.contractor_status_code 
        WHERE CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) LIKE ('%$name%') 
        AND contractor_position_name LIKE ('%$position%') 
        AND contractor_email LIKE ('%$email%') 
        AND contractor_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) 
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

    function getContractorByID($code){
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

    function getContractorByContractorPositionCode($code){
        $sql = " SELECT contractor_code , CONCAT(contractor_prefix,' ',contractor_name,' ',contractor_lastname ) AS contractor_name 
        FROM tb_contractor 
        WHERE contractor_position_code = '$code' 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
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

    function updateContractorByID($code,$data = []){
        $sql = " UPDATE tb_contractor SET     
        contractor_prefix = '".static::$db->real_escape_string($data['contractor_prefix'])."', 
        contractor_name = '".static::$db->real_escape_string($data['contractor_name'])."', 
        contractor_lastname = '".static::$db->real_escape_string($data['contractor_lastname'])."', 
        contractor_mobile = '".static::$db->real_escape_string($data['contractor_mobile'])."', 
        contractor_email = '".static::$db->real_escape_string($data['contractor_email'])."', 
        contractor_contractorname = '".static::$db->real_escape_string($data['contractor_contractorname'])."', 
        contractor_password = '".static::$db->real_escape_string($data['contractor_password'])."', 
        contractor_address = '".static::$db->real_escape_string($data['contractor_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        contractor_zipcode = '".static::$db->real_escape_string($data['contractor_zipcode'])."', 
        contractor_position_code = '".static::$db->real_escape_string($data['contractor_position_code'])."',
        license_code = '".static::$db->real_escape_string($data['license_code'])."', 
        contractor_status_code = '".static::$db->real_escape_string($data['contractor_status_code'])."' 
        WHERE contractor_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateContractorProfileByID($code,$data = []){
        $sql = " UPDATE tb_contractor SET 
        contractor_image = '".static::$db->real_escape_string($data['contractor_image'])."',  
        contractor_prefix = '".static::$db->real_escape_string($data['contractor_prefix'])."', 
        contractor_name = '".static::$db->real_escape_string($data['contractor_name'])."', 
        contractor_lastname = '".static::$db->real_escape_string($data['contractor_lastname'])."', 
        contractor_mobile = '".static::$db->real_escape_string($data['contractor_mobile'])."', 
        contractor_email = '".static::$db->real_escape_string($data['contractor_email'])."', 
        contractor_contractorname = '".static::$db->real_escape_string($data['contractor_contractorname'])."', 
        contractor_password = '".static::$db->real_escape_string($data['contractor_password'])."', 
        contractor_address = '".static::$db->real_escape_string($data['contractor_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        contractor_zipcode = '".static::$db->real_escape_string($data['contractor_zipcode'])."'
        WHERE contractor_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }
    
    function updateContractorProfileNoIMGByID($code,$data = []){
        $sql = " UPDATE tb_contractor SET 
        
        contractor_prefix = '".static::$db->real_escape_string($data['contractor_prefix'])."', 
        contractor_name = '".static::$db->real_escape_string($data['contractor_name'])."', 
        contractor_lastname = '".static::$db->real_escape_string($data['contractor_lastname'])."', 
        contractor_mobile = '".static::$db->real_escape_string($data['contractor_mobile'])."', 
        contractor_email = '".static::$db->real_escape_string($data['contractor_email'])."', 
        contractor_contractorname = '".static::$db->real_escape_string($data['contractor_contractorname'])."', 
        contractor_password = '".static::$db->real_escape_string($data['contractor_password'])."', 
        contractor_address = '".static::$db->real_escape_string($data['contractor_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        contractor_zipcode = '".static::$db->real_escape_string($data['contractor_zipcode'])."'
        WHERE contractor_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateContractorSignatureByID($code,$data = []){
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

    function updatePlayerIDByID($code,$contractor_player_code){
        $sql = " UPDATE tb_contractor SET 
        contractor_player_code = '".$contractor_player_code."' 
        WHERE contractor_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertContractor($data = []){
        $sql = " INSERT INTO tb_contractor ( 
            contractor_code,
            contractor_prefix,
            contractor_name, 
            contractor_lastname,
            contractor_mobile,
            contractor_email,
            contractor_contractorname,
            contractor_password,
            contractor_address,
            province_id,
            amphur_id,
            district_id,
            contractor_zipcode,
            contractor_position_code,
            license_code,
            contractor_status_code 
            )  VALUES ('".  
            $data['contractor_code']."','".
            $data['contractor_prefix']."','".
            $data['contractor_name']."','".
            $data['contractor_lastname']."','".
            $data['contractor_mobile']."','".
            $data['contractor_email']."','".
            $data['contractor_contractorname']."','".
            $data['contractor_password']."','".
            $data['contractor_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['contractor_zipcode']."','".
            $data['contractor_position_code']."','".
            $data['license_code']."','".
            $data['contractor_status_code']."' 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['contractor_code'];
        }else {
            return '';
        }
    }

    function deleteContractorByID($code){
        $sql = " DELETE FROM tb_contractor WHERE contractor_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>