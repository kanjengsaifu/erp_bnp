<?php
require_once("BaseModel.php");

class AgentModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->agentname, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getAgentLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(agent_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_agent 
        WHERE agent_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getAgentBy($name = '', $mobile  = ''){
        $sql = "SELECT agent_code, agent_prefix, CONCAT(agent_name,' ',agent_lastname) as name,
        agent_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_agent 
        LEFT JOIN tb_district ON tb_agent.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname) LIKE ('%$name%') 
        AND agent_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname) 
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

    function getAgentByCode($code){
        $sql = " SELECT * 
        FROM tb_agent 
        WHERE agent_code = '$code' 
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

    function getAgentByStatus($code){
        $sql = " SELECT agent_code, agent_prefix, CONCAT(agent_name,' ',agent_lastname) as name,
        agent_mobile, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_agent 
        LEFT JOIN tb_district ON tb_agent.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE agent_status_code = '$code' 
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

    function countAgentByStatus($code){
        $sql = " SELECT COUNT(agent_code) AS total
        FROM tb_agent 
        WHERE agent_status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function updateAgentByCode($code,$data = []){
        $sql = " UPDATE tb_agent SET     
        agent_prefix = '".static::$db->real_escape_string($data['agent_prefix'])."', 
        agent_name = '".static::$db->real_escape_string($data['agent_name'])."', 
        agent_lastname = '".static::$db->real_escape_string($data['agent_lastname'])."', 
        agent_mobile = '".static::$db->real_escape_string($data['agent_mobile'])."', 
        agent_address = '".static::$db->real_escape_string($data['agent_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        agent_zipcode = '".static::$db->real_escape_string($data['agent_zipcode'])."', 
        agent_image = '".static::$db->real_escape_string($data['agent_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        house_regis_image = '".static::$db->real_escape_string($data['house_regis_image'])."', 
        account_image = '".static::$db->real_escape_string($data['account_image'])."', 
        agent_status_code = '".static::$db->real_escape_string($data['agent_status_code'])."' 
        WHERE agent_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateAgentSignatureByCode($code,$data = []){
        $sql = " UPDATE tb_agent SET 
        agent_signature = '".$data['agent_signature']."' 
        WHERE agent_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertAgent($data = []){
        $data['agent_name']=mysqli_real_escape_string(static::$db,$data['agent_name']);
        $data['agent_lastname']=mysqli_real_escape_string(static::$db,$data['agent_lastname']);
        $data['agent_mobile']=mysqli_real_escape_string(static::$db,$data['agent_mobile']);
        $data['agent_image']=mysqli_real_escape_string(static::$db,$data['agent_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['house_regis_image']=mysqli_real_escape_string(static::$db,$data['house_regis_image']);
        $data['account_image']=mysqli_real_escape_string(static::$db,$data['account_image']);
        $data['agent_address']=mysqli_real_escape_string(static::$db,$data['agent_address']);
        $data['agent_zipcode']=mysqli_real_escape_string(static::$db,$data['agent_zipcode']);

        $sql = " INSERT INTO tb_agent ( 
            agent_code,
            agent_prefix,
            agent_name, 
            agent_lastname,
            agent_mobile,
            agent_address,
            province_id,
            amphur_id,
            district_id,
            agent_zipcode,
            agent_image,
            id_card_image,
            house_regis_image,
            account_image,
            agent_status_code 
            )  VALUES ('".  
            $data['agent_code']."','".
            $data['agent_prefix']."','".
            $data['agent_name']."','".
            $data['agent_lastname']."','".
            $data['agent_mobile']."','".
            $data['agent_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['agent_zipcode']."','".
            $data['agent_image']."','".
            $data['id_card_image']."','".
            $data['house_regis_image']."','".
            $data['account_image']."','".
            $data['agent_status_code']."')
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['agent_code'];
        }else {
            return '';
        }
    }

    function deleteAgentByCode($code){
        $sql = " DELETE FROM tb_agent WHERE agent_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>