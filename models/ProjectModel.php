<?php

require_once("BaseModel.php");
class ProjectModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProjectLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(project_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_project 
        WHERE project_code LIKE ('$code%') 
        "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProjectBy($project_name = ''){
        $sql = " SELECT project_code, project_name, project_description   
        FROM tb_project 
        WHERE project_name LIKE ('%$project_name%') 
        ORDER BY project_name  
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

    function getProjectByCode($code){
        $sql = " SELECT * 
        FROM tb_project 
        WHERE project_code = '$code' 
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

    function updateProjectByCode($code,$data = []){
        $sql = " UPDATE tb_project SET     
        project_name = '".static::$db->real_escape_string($data['project_name'])."', 
        project_logo = '".static::$db->real_escape_string($data['project_logo'])."', 
        project_price_per_rai = '".$data['project_price_per_rai']."', 
        project_description = '".static::$db->real_escape_string($data['project_description'])."'  
        WHERE project_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProject($data = []){
        $sql = " INSERT INTO tb_project (
            project_code,
            project_name,
            project_logo,
            project_price_per_rai,
            project_description
        ) VALUES (
            '".$data['project_code']."', 
            '".static::$db->real_escape_string($data['project_name'])."', 
            '".static::$db->real_escape_string($data['project_logo'])."', 
            '".$data['project_price_per_rai']."', 
            '".static::$db->real_escape_string($data['project_description'])."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProjectByCode($code){
        $sql = " DELETE FROM tb_project WHERE project_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>