<?php
require_once("BaseModel.php");

class ContractorStatusModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->contractorname, $this->password, $this->db_name);
        }
    }

    function getContractorStatusBy($name = ''){
        $sql = "SELECT * FROM tb_contractor_status WHERE  contractor_status_name LIKE ('%$name%') 
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

    function getContractorStatusByID($id){
        $sql = " SELECT * 
        FROM tb_contractor_status 
        WHERE contractor_status_id = '$id' 
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

    function updateContractorStatusByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_contractor_status 
        WHERE contractor_status_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteContractorStatusByID($id){
        $sql = " DELETE FROM tb_contractor_status WHERE contractor_status_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>