<?php

require_once("BaseModel.php");
class SatisfactionModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getSatisfactionLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(satisfaction_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_satisfaction 
        WHERE satisfaction_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }
    
    function getSatisfactionBy($user_code,$date_start="",$date_end=""){
        // echo $date_start;
        $str_date_start = "";
        $str_date_end = "";
        if($date_start!=""){
            $str_date_start = " AND satisfaction_date >= '$date_start' ";
        }
        if($date_end!=""){
            $str_date_end = " AND satisfaction_date <= '$date_end' ";
        }
        $sql = " SELECT * 
        FROM tb_satisfaction    
        LEFT JOIN 
        ((SELECT tb_contractor.contractor_code AS code,  CONCAT(contractor_prefix,' ',contractor_name,' ',contractor_lastname) as name , CONCAT('contractor') AS member_type 
        FROM tb_contractor  
        INNER JOIN tb_zone_contractor ON tb_contractor.contractor_code = tb_zone_contractor.contractor_code 
        INNER JOIN tb_zone_call_center ON tb_zone_contractor.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) ) 
        union 
        (SELECT tb_fund_agent.fund_agent_code AS code,  CONCAT(fund_agent_prefix,' ',fund_agent_name,' ',fund_agent_lastname) as name , CONCAT('fund_agent') AS member_type 
        FROM tb_fund_agent  
        INNER JOIN tb_zone_list ON tb_fund_agent.fund_agent_code = tb_zone_list.fund_agent_code  
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_fund_agent.fund_agent_name,' ',tb_fund_agent.fund_agent_lastname) ) 
        union 
        (SELECT tb_agent.agent_code AS code,  CONCAT(agent_prefix,' ',agent_name,' ',agent_lastname) as name , CONCAT('agent') AS member_type  
        FROM tb_agent  
        INNER JOIN tb_zone_list ON tb_agent.agent_code = tb_zone_list.agent_code    
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname)  )  
        union 
        (SELECT tb_farmer.farmer_code AS code,  CONCAT(farmer_prefix,' ',farmer_name,' ',farmer_lastname) as name , CONCAT('farmer') AS member_type  
        FROM tb_farmer   
        INNER JOIN tb_zone_list ON tb_farmer.district_id = tb_zone_list.district_id 
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) )) AS tb_member ON tb_satisfaction.member_code = tb_member.code 
        LEFT JOIN tb_contact_way ON tb_satisfaction.contact_way_code = tb_contact_way.contact_way_code 
        LEFT JOIN tb_contact_type ON tb_satisfaction.contact_type_code = tb_contact_type.contact_type_code 
        WHERE 1 
        $str_date_start
        $str_date_end
        ORDER BY satisfaction_code    
        
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

    function getSatisfactionByCode($code){
        $sql = " SELECT * 
        FROM tb_satisfaction 
        WHERE satisfaction_code = '$code' 
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

    function updateSatisfactionByCode($code,$data = []){
        $sql = " UPDATE tb_satisfaction SET     
        member_type = '".static::$db->real_escape_string($data['member_type'])."', 
        member_code = '".static::$db->real_escape_string($data['member_code'])."', 
        contact_way_code = '".static::$db->real_escape_string($data['contact_way_code'])."', 
        contact_type_code = '".static::$db->real_escape_string($data['contact_type_code'])."', 
        satisfaction_detail = '".static::$db->real_escape_string($data['satisfaction_detail'])."', 
        satisfaction_score = '".static::$db->real_escape_string($data['satisfaction_score'])."'  
        WHERE satisfaction_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return $data['satisfaction_code'];
        }else {
            return false;
        }


    }

    function insertSatisfaction($data = []){
        $sql = " INSERT INTO tb_satisfaction (
            satisfaction_code,
            member_type,
            member_code,
            contact_way_code,
            contact_type_code,
            satisfaction_detail,
            satisfaction_score, 
            satisfaction_date, 
            user_code
        ) VALUES (
            '".$data['satisfaction_code']."', 
            '".static::$db->real_escape_string($data['member_type'])."', 
            '".$data['member_code']."', 
            '".$data['contact_way_code']."', 
            '".$data['contact_type_code']."', 
            '".static::$db->real_escape_string($data['satisfaction_detail'])."', 
            '".$data['satisfaction_score']."', 
            NOW(), 
            '".$data['user_code']."' 
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return $data['satisfaction_code'];
        }else {
            return false;
        }

    }


    function deleteSatisfactionByCode($code){
        $sql = " DELETE FROM tb_satisfaction WHERE satisfaction_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>