<?php

require_once("BaseModel.php");
class MaterialUnitModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getMaterialUnitLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(unit_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_unit 
        WHERE unit_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getMaterialUnitBy($unit_name = ''){
        $sql = " SELECT unit_code, unit_name, unit_detail   
        FROM tb_unit 
        WHERE unit_name LIKE ('%$unit_name%') 
        ORDER BY unit_name  
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

    function getMaterialUnitByID($code){
        $sql = " SELECT * 
        FROM tb_unit 
        WHERE unit_code = '$code' 
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

    function updateMaterialUnitByID($code,$data = []){
        $sql = " UPDATE tb_unit SET     
        unit_name = '".$data['unit_name']."', 
        unit_detail = '".$data['unit_detail']."'  
        WHERE unit_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertMaterialUnit($data = []){
        $sql = " INSERT INTO tb_unit (
            unit_code,
            unit_name,
            unit_detail
        ) VALUES (
            '".$data['unit_code']."', 
            '".$data['unit_name']."', 
            '".$data['unit_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteMaterialUnitByID($code){
        $sql = " DELETE FROM tb_unit WHERE unit_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>