<?php
require_once("BaseModel.php");

class SongsermModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->songsermname, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getSongsermLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(songserm_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_songserm 
        WHERE songserm_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getSongsermBy($name = '', $mobile  = ''){
        $sql = "SELECT songserm_code, songserm_prefix, CONCAT(tb_songserm.songserm_name,' ',tb_songserm.songserm_lastname) as name,
        songserm_mobile, songserm_status_name  
        FROM tb_songserm 
        LEFT JOIN tb_songserm_status ON tb_songserm.songserm_status_code = tb_songserm_status.songserm_status_code 
        WHERE CONCAT(tb_songserm.songserm_name,' ',tb_songserm.songserm_lastname) LIKE ('%$name%') 
        AND songserm_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_songserm.songserm_name,' ',tb_songserm.songserm_lastname) 
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

    function getSongsermByCode($code){
        $sql = " SELECT * 
        FROM tb_songserm 
        WHERE songserm_code = '$code' 
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

    function updateSongsermByCode($code,$data = []){
        $sql = " UPDATE tb_songserm SET     
        songserm_prefix = '".static::$db->real_escape_string($data['songserm_prefix'])."', 
        songserm_name = '".static::$db->real_escape_string($data['songserm_name'])."', 
        songserm_lastname = '".static::$db->real_escape_string($data['songserm_lastname'])."', 
        songserm_mobile = '".static::$db->real_escape_string($data['songserm_mobile'])."', 
        songserm_address = '".static::$db->real_escape_string($data['songserm_address'])."', 
        province_id = '".static::$db->real_escape_string($data['province_id'])."', 
        amphur_id = '".static::$db->real_escape_string($data['amphur_id'])."', 
        district_id = '".static::$db->real_escape_string($data['district_id'])."', 
        songserm_zipcode = '".static::$db->real_escape_string($data['songserm_zipcode'])."', 
        songserm_image = '".static::$db->real_escape_string($data['songserm_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        house_regis_image = '".static::$db->real_escape_string($data['house_regis_image'])."', 
        account_image = '".static::$db->real_escape_string($data['account_image'])."', 
        songserm_status_code = '".static::$db->real_escape_string($data['songserm_status_code'])."' 
        WHERE songserm_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateSongsermSignatureByCode($code,$data = []){
        $sql = " UPDATE tb_songserm SET 
        songserm_signature = '".$data['songserm_signature']."' 
        WHERE songserm_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertSongserm($data = []){
        $data['songserm_name']=mysqli_real_escape_string(static::$db,$data['songserm_name']);
        $data['songserm_lastname']=mysqli_real_escape_string(static::$db,$data['songserm_lastname']);
        $data['songserm_mobile']=mysqli_real_escape_string(static::$db,$data['songserm_mobile']);
        $data['songserm_image']=mysqli_real_escape_string(static::$db,$data['songserm_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['house_regis_image']=mysqli_real_escape_string(static::$db,$data['house_regis_image']);
        $data['account_image']=mysqli_real_escape_string(static::$db,$data['account_image']);
        $data['songserm_address']=mysqli_real_escape_string(static::$db,$data['songserm_address']);
        $data['songserm_zipcode']=mysqli_real_escape_string(static::$db,$data['songserm_zipcode']);

        $sql = " INSERT INTO tb_songserm ( 
            songserm_code,
            songserm_prefix,
            songserm_name, 
            songserm_lastname,
            songserm_mobile,
            songserm_address,
            province_id,
            amphur_id,
            district_id,
            songserm_zipcode,
            songserm_image,
            id_card_image,
            house_regis_image,
            account_image,
            songserm_status_code 
            )  VALUES ('".  
            $data['songserm_code']."','".
            $data['songserm_prefix']."','".
            $data['songserm_name']."','".
            $data['songserm_lastname']."','".
            $data['songserm_mobile']."','".
            $data['songserm_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['songserm_zipcode']."','".
            $data['songserm_image']."','".
            $data['id_card_image']."','".
            $data['house_regis_image']."','".
            $data['account_image']."','".
            $data['songserm_status_code']."')
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['songserm_code'];
        }else {
            return '';
        }
    }

    function deleteSongsermByCode($code){
        $sql = " DELETE FROM tb_songserm WHERE songserm_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>