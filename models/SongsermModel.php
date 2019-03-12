<?php
require_once("BaseModel.php");

class SongsermModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
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
        songserm_mobile, songserm_line, songserm_status_name, songserm_position_name
        FROM tb_songserm 
        LEFT JOIN tb_songserm_status ON tb_songserm.songserm_status_code = tb_songserm_status.songserm_status_code 
        LEFT JOIN tb_songserm_position ON tb_songserm.songserm_position_code = tb_songserm_position.songserm_position_code 
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
        LEFT JOIN tb_songserm_status ON tb_songserm.songserm_status_code = tb_songserm_status.songserm_status_code 
        LEFT JOIN tb_district ON tb_songserm.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
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

    function getSongsermByUsername($code,$user){
        $sql = "SELECT * 
        FROM tb_songserm 
        WHERE songserm_code != '$code' AND songserm_username = '$user' 
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

    function getSongsermNotInZone($code){
        $sql = "SELECT songserm_code, songserm_prefix, CONCAT(songserm_name,' ',songserm_lastname) as name,
        songserm_mobile, songserm_line, songserm_position_name
        FROM tb_songserm 
        LEFT JOIN tb_songserm_position ON tb_songserm.songserm_position_code = tb_songserm_position.songserm_position_code 
        LEFT JOIN tb_district ON tb_songserm.district_id = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE songserm_status_code != '00'
        AND songserm_code NOT IN (
            SELECT songserm_code
            FROM tb_zone_songserm 
            WHERE zone_code = '$code'
        )
        AND tb_songserm.songserm_position_code NOT IN(
            SELECT songserm_position_code
            FROM tb_zone_songserm 
            LEFT JOIN tb_songserm ON tb_zone_songserm.songserm_code = tb_songserm.songserm_code 
            WHERE zone_code = '$code' AND songserm_position_code = 'STP002'
            GROUP BY songserm_position_code
        )
        GROUP BY CONCAT(songserm_name,' ',songserm_lastname)
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
    
    function updateSongsermByCode($code,$data = []){
        $data['songserm_name']=mysqli_real_escape_string(static::$db,$data['songserm_name']);
        $data['songserm_lastname']=mysqli_real_escape_string(static::$db,$data['songserm_lastname']);
        $data['songserm_address']=mysqli_real_escape_string(static::$db,$data['songserm_address']);
        $data['songserm_zipcode']=mysqli_real_escape_string(static::$db,$data['songserm_zipcode']);
        $data['songserm_mobile']=mysqli_real_escape_string(static::$db,$data['songserm_mobile']);
        $data['songserm_line']=mysqli_real_escape_string(static::$db,$data['songserm_line']);
        $data['songserm_username']=mysqli_real_escape_string(static::$db,$data['songserm_username']);
        $data['songserm_password']=mysqli_real_escape_string(static::$db,$data['songserm_password']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);

        $sql = " UPDATE tb_songserm SET     
        songserm_position_code = '".$data['songserm_position_code']."',
        songserm_status_code = '".$data['songserm_status_code']."',
        songserm_prefix = '".$data['songserm_prefix']."', 
        songserm_name = '".$data['songserm_name']."', 
        songserm_lastname = '".$data['songserm_lastname']."', 
        songserm_address = '".$data['songserm_address']."', 
        province_id = '".$data['province_id']."', 
        amphur_id = '".$data['amphur_id']."', 
        district_id = '".$data['district_id']."', 
        songserm_zipcode = '".$data['songserm_zipcode']."', 
        songserm_mobile = '".$data['songserm_mobile']."', 
        songserm_line = '".$data['songserm_line']."', 
        songserm_username = '".$data['songserm_username']."', 
        songserm_password = '".$data['songserm_password']."',
        profile_image = '".$data['profile_image']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE songserm_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertSongserm($data = []){
        $data['songserm_code']=mysqli_real_escape_string(static::$db,$data['songserm_code']);
        $data['songserm_name']=mysqli_real_escape_string(static::$db,$data['songserm_name']);
        $data['songserm_lastname']=mysqli_real_escape_string(static::$db,$data['songserm_lastname']);
        $data['songserm_address']=mysqli_real_escape_string(static::$db,$data['songserm_address']);
        $data['songserm_zipcode']=mysqli_real_escape_string(static::$db,$data['songserm_zipcode']);
        $data['songserm_mobile']=mysqli_real_escape_string(static::$db,$data['songserm_mobile']);
        $data['songserm_line']=mysqli_real_escape_string(static::$db,$data['songserm_line']);
        $data['songserm_username']=mysqli_real_escape_string(static::$db,$data['songserm_username']);
        $data['songserm_password']=mysqli_real_escape_string(static::$db,$data['songserm_password']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);

        $sql = " INSERT INTO tb_songserm ( 
            songserm_code,
            songserm_position_code,
            songserm_status_code,
            songserm_prefix,
            songserm_name, 
            songserm_lastname,
            songserm_address,
            province_id,
            amphur_id,
            district_id,
            songserm_zipcode,
            songserm_mobile,
            songserm_line,
            songserm_username,
            songserm_password,
            profile_image,
            addby,
            adddate
            )  VALUES ('".  
            $data['songserm_code']."','".
            $data['songserm_position_code']."','".
            $data['songserm_status_code']."','".
            $data['songserm_prefix']."','".
            $data['songserm_name']."','".
            $data['songserm_lastname']."','".
            $data['songserm_address']."','".
            $data['province_id']."','".
            $data['amphur_id']."','".
            $data['district_id']."','".
            $data['songserm_zipcode']."','".
            $data['songserm_mobile']."','".
            $data['songserm_line']."','".
            $data['songserm_username']."','".
            $data['songserm_password']."','".
            $data['profile_image']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
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