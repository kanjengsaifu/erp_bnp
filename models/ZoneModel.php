<?php
require_once("BaseModel.php");

class ZoneModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getZoneLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(zone_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_zone 
        WHERE zone_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getZoneBy($name = ''){
        $sql = "SELECT * , (
            SELECT CONCAT(songserm_name,' ',songserm_lastname)
            FROM tb_zone_songserm 
            LEFT JOIN tb_songserm ON tb_zone_songserm.songserm_code = tb_songserm.songserm_code 
            WHERE tb_zone_songserm.zone_code = tb_zone.zone_code AND songserm_position_code = 'STP002'
            LIMIT 1
            ) AS zone_admin
        FROM tb_zone 
        WHERE zone_name LIKE ('%$name%') 
        ORDER BY zone_name
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

    function getZoneByCode($code){
        $sql = " SELECT * 
        FROM tb_zone 
        WHERE zone_code = '$code' 
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

    function updateZoneByCode($code,$data = []){
        $sql = " UPDATE tb_zone SET     
        zone_name = '".static::$db->real_escape_string($data['zone_name'])."', 
        zone_description = '".static::$db->real_escape_string($data['zone_description'])."', 
        updateby = '".$data['updateby']."',
        lastupdate = NOW() 
        WHERE zone_code = '".static::$db->real_escape_string($code)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }
    
    function insertZone($data = []){
        $data['zone_name']=mysqli_real_escape_string(static::$db,$data['zone_name']);
        $data['zone_description']=mysqli_real_escape_string(static::$db,$data['zone_description']);

        $sql = " INSERT INTO tb_zone ( 
            zone_code,
            zone_name, 
            zone_description,
            addby,
            adddate
            )  VALUES ('".  
            $data['zone_code']."','".
            $data['zone_name']."','".
            $data['zone_description']."','".
            $data['addby']."',
            NOW())
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteZoneByCode($code){
        $sql = " DELETE FROM tb_zone WHERE zone_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>