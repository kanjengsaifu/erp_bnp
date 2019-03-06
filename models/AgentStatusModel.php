<?php
require_once("BaseModel.php");

class AgentStatusModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->agentname, $this->password, $this->db_name);
        }
    }

    function getAgentStatusBy($name = ''){
        $sql = "SELECT * FROM tb_agent_status WHERE  agent_status_name LIKE ('%$name%') 
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

    function getAgentStatusByID($id){
        $sql = " SELECT * 
        FROM tb_agent_status 
        WHERE agent_status_id = '$id' 
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

    function updateAgentStatusByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_agent_status 
        WHERE agent_status_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }

    function deleteAgentStatusByID($id){
        $sql = " DELETE FROM tb_agent_status WHERE agent_status_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>