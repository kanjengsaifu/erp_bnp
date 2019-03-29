<?php
require_once("BaseModel.php");

class AccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountBy($account){
        $sql = "SELECT * 
        FROM tb_account  
        WHERE account_control = '$account' 
        ORDER BY account_code 
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

    function getAccountAll(){
        $sql = "SELECT * 
        FROM tb_account
        ORDER BY account_code 
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

    function getAccountNode(){
        $sql = "SELECT * 
        FROM tb_account 
        WHERE tb_account.account_type = '0' 
        ORDER BY account_code ";
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getAccountByCode($code){
        $sql = " SELECT 
            tb_account.account_code , 
            tb_account.account_code , 
            tb_account.account_name_th , 
            tb_account.account_name_en , 
            tb_account.account_control , 
            tb_account.account_level , 
            tb_account.account_group , 
            tb_account.account_type , 
            tb1.account_code as control_code 
        FROM tb_account 
        LEFT JOIN tb_account as tb1 ON tb_account.account_control = tb1.account_code  
        WHERE tb_account.account_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateAccountByCode($code,$data = []){
        $sql = " UPDATE tb_account SET 
        account_name_th = '".$data['account_name_th']."', 
        account_name_en = '".$data['account_name_en']."', 
        account_control = '".$data['account_control']."', 
        account_level = '".$data['account_level']."', 
        account_group = '".$data['account_group']."', 
        account_type = '".$data['account_type']."'  
        WHERE account_code = $code 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updateBeginByCode($code,$data = []){
        $sql = " UPDATE tb_account SET 
        account_debit_begin = '".$data['account_debit_begin']."', 
        account_credit_begin = '".$data['account_credit_begin']."'  
        WHERE account_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertAccount($data = []){
        $sql = " INSERT INTO tb_account (
            account_code , 
            account_name_th, 
            account_name_en,
            account_control,
            account_level,
            account_group,
            account_type 
        ) VALUES (
            '".$data['account_code']."', 
            '".$data['account_name_th']."', 
            '".$data['account_name_en']."', 
            '".$data['account_control']."', 
            '".$data['account_level']."', 
            '".$data['account_group']."', 
            '".$data['account_type']."' 
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteAccountByCode($code){
        $sql = " DELETE FROM tb_account WHERE account_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>