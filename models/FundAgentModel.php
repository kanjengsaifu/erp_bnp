<?php
require_once("BaseModel.php");

class FundAgentModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getFundAgentLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(fund_agent_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_fund_agent 
        WHERE fund_agent_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getFundAgentBy($name = '', $mobile  = ''){
        $sql = "SELECT fund_agent_code, fund_agent_prefix, CONCAT(fund_agent_name,' ',fund_agent_lastname) as name,
        fund_agent_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_fund_agent 
        LEFT JOIN tb_district ON tb_fund_agent.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE CONCAT(tb_fund_agent.fund_agent_name,' ',tb_fund_agent.fund_agent_lastname) LIKE ('%$name%') 
        AND fund_agent_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_fund_agent.fund_agent_name,' ',tb_fund_agent.fund_agent_lastname) 
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
    
    function getFundAgentByUserCode($user_code){
        $sql = "SELECT tb_fund_agent.fund_agent_code AS code,  CONCAT(fund_agent_prefix,' ',fund_agent_name,' ',fund_agent_lastname) as name 
        FROM tb_fund_agent  
        INNER JOIN tb_zone_list_fund_agent ON tb_fund_agent.fund_agent_code = tb_zone_list_fund_agent.fund_agent_code 
        INNER JOIN tb_zone_list ON tb_zone_list_fund_agent.zone_list_code = tb_zone_list.zone_list_code 
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_fund_agent.fund_agent_name,' ',tb_fund_agent.fund_agent_lastname) 
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

    function getFundAgentByCode($code){
        $sql = " SELECT * 
        FROM tb_fund_agent 
        LEFT JOIN tb_status ON tb_fund_agent.status_code = tb_status.status_code 
        LEFT JOIN tb_district ON tb_fund_agent.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE fund_agent_code = '$code' 
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

    function getFundAgentByStatus($code){
        $sql = " SELECT fund_agent_code, fund_agent_prefix, CONCAT(fund_agent_name,' ',fund_agent_lastname) as name,
        fund_agent_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_fund_agent 
        LEFT JOIN tb_district ON tb_fund_agent.district_id = tb_district.DISTRICT_ID 
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

    function countFundAgentByStatus($code){
        $sql = " SELECT COUNT(fund_agent_code) AS total
        FROM tb_fund_agent 
        WHERE status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function getFundAgentByUsername($code,$user){
        $sql = "SELECT * 
        FROM tb_fund_agent 
        WHERE fund_agent_code != '$code' AND fund_agent_username = '$user' 
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

    function approveFundAgentByCode($code){
        $sql = " UPDATE tb_fund_agent SET 
        status_code = '01' 
        WHERE fund_agent_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateFundAgentByCode($code,$data = []){
        $sql = " UPDATE tb_fund_agent SET 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        fund_agent_prefix = '".static::$db->real_escape_string($data['fund_agent_prefix'])."', 
        fund_agent_name = '".static::$db->real_escape_string($data['fund_agent_name'])."', 
        fund_agent_lastname = '".static::$db->real_escape_string($data['fund_agent_lastname'])."', 
        fund_agent_address = '".static::$db->real_escape_string($data['fund_agent_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        fund_agent_zipcode = '".static::$db->real_escape_string($data['fund_agent_zipcode'])."',
        fund_agent_mobile = '".static::$db->real_escape_string($data['fund_agent_mobile'])."',  
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        fund_agent_username = '".static::$db->real_escape_string($data['fund_agent_username'])."', 
        fund_agent_password = '".static::$db->real_escape_string($data['fund_agent_password'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE fund_agent_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertFundAgent($data = []){
        $data['fund_agent_code']=mysqli_real_escape_string(static::$db,$data['fund_agent_code']);
        $data['fund_agent_name']=mysqli_real_escape_string(static::$db,$data['fund_agent_name']);
        $data['fund_agent_lastname']=mysqli_real_escape_string(static::$db,$data['fund_agent_lastname']);
        $data['fund_agent_address']=mysqli_real_escape_string(static::$db,$data['fund_agent_address']);
        $data['fund_agent_zipcode']=mysqli_real_escape_string(static::$db,$data['fund_agent_zipcode']);
        $data['fund_agent_mobile']=mysqli_real_escape_string(static::$db,$data['fund_agent_mobile']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['fund_agent_username']=mysqli_real_escape_string(static::$db,$data['fund_agent_username']);
        $data['fund_agent_password']=mysqli_real_escape_string(static::$db,$data['fund_agent_password']);

        $sql = " INSERT INTO tb_fund_agent ( 
            fund_agent_code,
            status_code,
            fund_agent_prefix,
            fund_agent_name, 
            fund_agent_lastname,
            fund_agent_address,
            province_id,
            amphur_id,
            district_id,
            fund_agent_zipcode,
            fund_agent_mobile,
            profile_image,
            id_card_image,
            fund_agent_username,
            fund_agent_password,
            addby,
            adddate
            )  VALUES ('".  
            $data['fund_agent_code']."','".
            $data['status_code']."','".
            $data['fund_agent_prefix']."','".
            $data['fund_agent_name']."','".
            $data['fund_agent_lastname']."','".
            $data['fund_agent_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['fund_agent_zipcode']."','".
            $data['fund_agent_mobile']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['fund_agent_username']."','".
            $data['fund_agent_password']."','".
            $data['addby']."',
            NOW()
        )";

        echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteFundAgentByCode($code){
        $sql = " DELETE FROM tb_fund_agent WHERE fund_agent_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>