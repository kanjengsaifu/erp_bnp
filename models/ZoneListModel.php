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
        WHERE village_id LIKE ('%$name%') 
        ORDER BY village_id
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
        LEFT JOIN tb_village ON tb_zone_list.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
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
        $sql = "SELECT tb_zone_list.*, CONCAT(agent_name,' ',agent_lastname) as agent_name, CONCAT(dealer_name,' ',dealer_lastname) as dealer_name,
        PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_zone_list 
        LEFT JOIN tb_agent ON tb_zone_list.agent_code  = tb_agent.agent_code  
        LEFT JOIN tb_dealer ON tb_zone_list.dealer_code = tb_dealer.dealer_code 
        LEFT JOIN tb_village ON tb_zone_list.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
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
        $sql = "UPDATE tb_zone_list SET
        zone_list_code = '".$data['zone_list_code']."', 
        village_id = '".$data['village_id']."', 
        agent_code = '".$data['agent_code']."', 
        dealer_code = '".$data['dealer_code']."', 
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
        $sql = "INSERT INTO tb_zone_list ( 
        zone_list_code,
        zone_code,
        village_id, 
        agent_code,
        dealer_code,
        addby,
        adddate 
        )  VALUES ('".  
        $data['zone_list_code']."','".
        $data['zone_code']."','".
        $data['village_id']."','".
        $data['agent_code']."','".
        $data['dealer_code']."','".
        $data['addby']."',
        NOW()
        )";

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