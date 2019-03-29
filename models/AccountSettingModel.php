<?php
require_once("BaseModel.php");

class AccountSettingModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountSettingBy(){
        $sql = "SELECT * 
        FROM tb_account_setting  
        LEFT JOIN tb_account ON tb_account_setting.account_code = tb_account.account_code  
        LEFT JOIN tb_account_group ON tb_account_setting.account_group_code = tb_account_group.account_group_code  
        ORDER BY tb_account_setting.account_group_code , account_setting_code
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

    function getAccountSettingByAccountGroupCode($code){
        $sql = "SELECT * 
        FROM tb_account_setting  
        LEFT JOIN tb_account ON tb_account_setting.account_code = tb_account.account_code  
        LEFT JOIN tb_account_group ON tb_account_setting.account_group_code = tb_account_group.account_group_code  
        WHERE tb_account_setting.account_group_code = '$code' 
        ORDER BY account_group_code , account_setting_code
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

    function getAccountSettingByCode($code){
        $sql = " SELECT *
        FROM tb_account_setting 
        LEFT JOIN tb_account ON tb_account_setting.account_code = tb_account.account_code  
        LEFT JOIN tb_account_group ON tb_account_setting.account_group_code = tb_account_group.account_group_code  
        WHERE account_setting_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateAccountSettingByCode($code,$data = []){
        $sql = " UPDATE tb_account_setting SET 
        account_setting_name = '".$data['account_setting_name']."', 
        account_group_code = '".$data['account_group_code']."', 
        account_code = '".$data['account_code']."'  
        WHERE account_setting_code = '$code' 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updateAccountCodeByCode($code,$data = []){
        $sql = " UPDATE tb_account_setting SET  
        account_code = '".$data['account_code']."'  
        WHERE account_setting_code = '$code'
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertAccountSetting($data = []){
        $sql = " INSERT INTO tb_account_setting (
            account_setting_code,
            account_setting_name,
            account_group_code , 
            account_code 
        ) VALUES (
            '".$data['account_setting_code']."', 
            '".$data['account_setting_name']."', 
            '".$data['account_group_code']."', 
            '".$data['account_code']."'  
        )";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteAccountSettingByCode($code){
        $sql = " DELETE FROM tb_account_setting WHERE account_setting_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>