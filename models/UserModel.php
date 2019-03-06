<?php
require_once("BaseModel.php");

class UserModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getUserLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(user_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_user 
        WHERE user_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getLogin($username, $password){
        $username = static::$db->real_escape_string($username);
        $password = static::$db->real_escape_string($password);

        if ($result = mysqli_query(static::$db,"SELECT * 
        FROM tb_user LEFT JOIN tb_license ON tb_user.license_code = tb_license.license_code 
        WHERE user_username = '$username' 
        AND user_password = '$password' ", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getUserBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT user_code, user_code, user_profile_img , CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name , user_mobile, user_email, user_position_name, user_status_name  
        FROM tb_user LEFT JOIN tb_user_position ON tb_user.user_position_code = tb_user_position.user_position_code 
        LEFT JOIN tb_user_status ON tb_user.user_status_code = tb_user_status.user_status_code 
        WHERE CONCAT(tb_user.user_name,' ',tb_user.user_lastname) LIKE ('%$name%') 
        AND user_position_name LIKE ('%$position%') 
        AND user_email LIKE ('%$email%') 
        AND user_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_user.user_name,' ',tb_user.user_lastname) 
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

    function getUserByCode($code){
        $sql = " SELECT * 
        FROM tb_user 
        WHERE user_code = '$code' 
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


    function getUserByUserPositionCode($code){
        $sql = " SELECT user_code , CONCAT(user_prefix,' ',user_name,' ',user_lastname ) AS user_name 
        FROM tb_user 
        WHERE user_position_code = '$code' 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
 

    function updateUserByCode($code,$data = []){
        $sql = " UPDATE tb_user SET     
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."', 
        user_position_code = '".static::$db->real_escape_string($data['user_position_code'])."',
        license_code = '".static::$db->real_escape_string($data['license_code'])."', 
        user_status_code = '".static::$db->real_escape_string($data['user_status_code'])."' 
        WHERE user_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateUserProfileByCode($code,$data = []){
        $sql = " UPDATE tb_user SET 
        
        user_image = '".static::$db->real_escape_string($data['user_image'])."',  
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."'
        WHERE user_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateUserProfileNoIMGByCode($code,$data = []){
        $sql = " UPDATE tb_user SET 
        
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."'
        WHERE user_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateUserSignatureByCode($code,$data = []){
        $sql = " UPDATE tb_user SET 
        user_signature = '".$data['user_signature']."' 
        WHERE user_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updatePlayerIDByCode($code,$user_player_code){
        $sql = " UPDATE tb_user SET 
        user_player_code = '".$user_player_code."' 
        WHERE user_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertUser($data = []){
        $sql = " INSERT INTO tb_user ( 
            user_code,
            user_prefix,
            user_name, 
            user_lastname,
            user_mobile,
            user_email,
            user_username,
            user_password,
            user_address,
            province_id,
            amphur_id,
            district_id,
            user_zipcode,
            user_position_code,
            license_code,
            user_status_code 
            )  VALUES ('".  
            $data['user_code']."','".
            static::$db->real_escape_string($data['user_prefix'])."','".
            static::$db->real_escape_string($data['user_name'])."','".
            static::$db->real_escape_string($data['user_lastname'])."','".
            static::$db->real_escape_string($data['user_mobile'])."','".
            static::$db->real_escape_string($data['user_email'])."','".
            static::$db->real_escape_string($data['user_username'])."','".
            static::$db->real_escape_string($data['user_password'])."','".
            static::$db->real_escape_string($data['user_address'])."','".
            static::$db->real_escape_string($data['province_id'])."','".
            static::$db->real_escape_string($data['amphur_id'])."','".
            static::$db->real_escape_string($data['district_id'])."','".
            static::$db->real_escape_string($data['user_zipcode'])."','".
            static::$db->real_escape_string($data['user_position_code'])."','".
            static::$db->real_escape_string($data['license_code'])."','".
            static::$db->real_escape_string($data['user_status_code'])."' 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['user_code'];
        }else {
            return '';
        }
    }

    function deleteUserByCode($code){
        $sql = " DELETE FROM tb_user WHERE user_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>