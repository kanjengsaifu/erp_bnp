<?php
require_once("BaseModel.php");

class ZoneCallCenterModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getZoneCallCenterLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(zone_call_center_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_zone_call_center 
        WHERE zone_call_center_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getZoneCallCenterBy(){
        $sql = "SELECT *
        FROM tb_zone_call_center 
        LEFT JOIN tb_user ON tb_zone_call_center.user_code = tb_user.user_code
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

    function getZoneCallCenterByCode($code){
        $sql = " SELECT * 
        FROM tb_zone_call_center 
        LEFT JOIN tb_user ON tb_zone_call_center.user_code = tb_user.user_code
        WHERE zone_call_center_code = '$code' 
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

    function getZoneCallCenterByZone($code){
        $sql = "SELECT zone_call_center_code, user_prefix, CONCAT(user_name,' ',user_lastname) as name
        FROM tb_zone_call_center 
        LEFT JOIN tb_user ON tb_zone_call_center.user_code = tb_user.user_code
        WHERE zone_code = '$code'
        ORDER BY user_name
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

    function insertZoneCallCenter($data = []){
        $data['zone_call_center_code']=mysqli_real_escape_string(static::$db,$data['zone_call_center_code']);
        $data['zone_code']=mysqli_real_escape_string(static::$db,$data['zone_code']);
        $data['user_code']=mysqli_real_escape_string(static::$db,$data['user_code']);

        $sql = " INSERT INTO tb_zone_call_center ( 
        zone_call_center_code,
        zone_code,
        user_code, 
        addby,
        adddate 
        ) VALUES ('".  
        $data['zone_call_center_code']."','".
        $data['zone_code']."','".
        $data['user_code']."','".
        $data['addby']."',
        NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteZoneCallCenterByCode($code){
        $sql = " DELETE FROM tb_zone_call_center WHERE zone_call_center_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>