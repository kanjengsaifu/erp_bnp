<?php
require_once("BaseModel.php");

class AgentLocationModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
    }

    function getAgentLocationLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(location_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_agent_location 
        WHERE location_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }  

    function getAgentLocationBy($code){
        $sql = "SELECT *
        FROM tb_agent_location 
        WHERE agent_code = '$code'
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

    function getAgentLocationByCode($code){
        $sql = " SELECT * 
        FROM tb_agent_location 
        WHERE location_code = '$code' 
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

    function updateAgentLocationByCode($code,$data = []){
        $sql = " UPDATE tb_agent_location SET     
        location_lat = '".static::$db->real_escape_string($data['location_lat'])."', 
        location_long = '".static::$db->real_escape_string($data['location_long'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE location_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertAgentLocation($data = []){
        $data['location_code']=mysqli_real_escape_string(static::$db,$data['location_code']);
        $data['agent_code']=mysqli_real_escape_string(static::$db,$data['agent_code']);
        $data['location_lat']=mysqli_real_escape_string(static::$db,$data['location_lat']);
        $data['location_long']=mysqli_real_escape_string(static::$db,$data['location_long']);

        $sql = " INSERT INTO tb_agent_location ( 
            location_code,
            agent_code,
            location_lat, 
            location_long,
            addby,
            adddate 
            )  VALUES ('".  
            $data['location_code']."','".
            $data['agent_code']."','".
            $data['location_lat']."','".
            $data['location_long']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteAgentLocationByCode($code){
        $sql = " DELETE FROM tb_agent_location WHERE location_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteAgentLocationBy($code){
        $sql = " DELETE FROM tb_agent_location WHERE agent_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>