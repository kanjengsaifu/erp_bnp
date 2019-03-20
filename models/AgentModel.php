<?php
require_once("BaseModel.php");

class AgentModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
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
        agent_mobile, agent_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_agent 
        LEFT JOIN tb_village ON tb_agent.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
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

    
    function getAgentByUserCode($user_code){
        $sql = "SELECT tb_agent.agent_code AS code,  CONCAT(agent_prefix,' ',agent_name,' ',agent_lastname) as name 
        FROM tb_agent  
        INNER JOIN tb_zone_list_dealer ON tb_dealer.dealer_code = tb_zone_list_dealer.dealer_code   
        INNER JOIN tb_zone_list ON tb_zone_list_dealer.zone_list_code = tb_zone_list.zone_list_code 
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname) 
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

    function getAgentByCode($code){
        $sql = " SELECT * 
        FROM tb_agent 
        LEFT JOIN tb_status ON tb_agent.status_code = tb_status.status_code 
        LEFT JOIN tb_village ON tb_agent.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
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
        agent_mobile, agent_line, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME
        FROM tb_agent 
        LEFT JOIN tb_village ON tb_agent.village_id = tb_village.VILLAGE_ID 
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

    function getAgentByUsername($code,$user){
        $sql = "SELECT * 
        FROM tb_agent 
        WHERE agent_code != '$code' AND agent_username = '$user' 
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

    function countAgentByStatus($code){
        $sql = " SELECT COUNT(agent_code) AS total
        FROM tb_agent 
        WHERE status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function getAgentByDistrict($id){
        $sql = " SELECT agent_code, agent_prefix, CONCAT(agent_name,' ',agent_lastname) as name
        FROM tb_agent 
        LEFT JOIN tb_village ON tb_agent.village_id = tb_village.VILLAGE_ID 
        WHERE DISTRICT_ID = '$id' 
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

    function approveAgentByCode($code){
        $sql = " UPDATE tb_agent SET 
        status_code = '01' 
        WHERE agent_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateAgentByCode($code,$data = []){
        $sql = " UPDATE tb_agent SET 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        agent_prefix = '".static::$db->real_escape_string($data['agent_prefix'])."', 
        agent_name = '".static::$db->real_escape_string($data['agent_name'])."', 
        agent_lastname = '".static::$db->real_escape_string($data['agent_lastname'])."', 
        agent_address = '".static::$db->real_escape_string($data['agent_address'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        agent_mobile = '".static::$db->real_escape_string($data['agent_mobile'])."',  
        agent_line = '".static::$db->real_escape_string($data['agent_line'])."',  
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        agent_username = '".static::$db->real_escape_string($data['agent_username'])."', 
        agent_password = '".static::$db->real_escape_string($data['agent_password'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE agent_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertAgent($data = []){
        $data['agent_code']=mysqli_real_escape_string(static::$db,$data['agent_code']);
        $data['agent_name']=mysqli_real_escape_string(static::$db,$data['agent_name']);
        $data['agent_lastname']=mysqli_real_escape_string(static::$db,$data['agent_lastname']);
        $data['agent_address']=mysqli_real_escape_string(static::$db,$data['agent_address']);
        $data['agent_mobile']=mysqli_real_escape_string(static::$db,$data['agent_mobile']);
        $data['agent_line']=mysqli_real_escape_string(static::$db,$data['agent_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['agent_username']=mysqli_real_escape_string(static::$db,$data['agent_username']);
        $data['agent_password']=mysqli_real_escape_string(static::$db,$data['agent_password']);

        $sql = " INSERT INTO tb_agent ( 
            agent_code,
            status_code,
            agent_prefix,
            agent_name, 
            agent_lastname,
            agent_address,
            village_id,
            agent_mobile,
            agent_line,
            profile_image,
            id_card_image,
            agent_username,
            agent_password,
            addby,
            adddate
            )  VALUES ('".  
            $data['agent_code']."','".
            $data['status_code']."','".
            $data['agent_prefix']."','".
            $data['agent_name']."','".
            $data['agent_lastname']."','".
            $data['agent_address']."','".
            $data['village_id']."','".
            $data['agent_mobile']."','".
            $data['agent_line']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['agent_username']."','".
            $data['agent_password']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
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