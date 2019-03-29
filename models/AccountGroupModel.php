<?php
require_once("BaseModel.php");

class AccountGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountGroupBy(){
        $sql = "SELECT * 
        FROM tb_account_group    
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

    function getAccountGroupByCode($code){
        $sql = " SELECT *
        FROM tb_account_group   
        WHERE account_group_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateAccountGroupByCode($code,$data = []){
        $sql = " UPDATE tb_account_group SET 
        account_group_name = '".$data['account_group_name']."'   
        WHERE account_group_code = $code 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertAccountGroup($data = []){
        $sql = " INSERT INTO tb_account_group (
            account_group_code,
            account_group_name 
        ) VALUES (
            '".$data['account_group_code']."',
            '".$data['account_group_name']."'
        )";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteAccountByCode($code){
        $sql = " DELETE FROM tb_account_group WHERE account_group_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>