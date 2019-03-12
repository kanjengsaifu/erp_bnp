<?php
require_once("BaseModel.php");

class ZoneSongsermModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getZoneSongsermLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(zone_songserm_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_zone_songserm 
        WHERE zone_songserm_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getZoneSongsermBy($code){
        $sql = "SELECT zone_songserm_code, tb_songserm.songserm_code, songserm_prefix, CONCAT(songserm_name,' ',songserm_lastname) as name,
        songserm_mobile, songserm_status_name, songserm_position_name
        FROM tb_zone_songserm 
        LEFT JOIN tb_songserm ON tb_zone_songserm.songserm_code = tb_songserm.songserm_code 
        LEFT JOIN tb_songserm_status ON tb_songserm.songserm_status_code = tb_songserm_status.songserm_status_code 
        LEFT JOIN tb_songserm_position ON tb_songserm.songserm_position_code = tb_songserm_position.songserm_position_code 
        WHERE zone_code = '$code'
        ORDER BY tb_songserm.songserm_position_code DESC,CONCAT(songserm_name,' ',songserm_lastname) 
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

    function getZoneSongsermByCode($code){
        $sql = " SELECT * 
        FROM tb_zone_songserm 
        WHERE zone_songserm_code = '$code' 
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

    function insertZoneSongserm($data = []){
        $data['zone_songserm_code']=mysqli_real_escape_string(static::$db,$data['zone_songserm_code']);
        $data['zone_code']=mysqli_real_escape_string(static::$db,$data['zone_code']);
        $data['songserm_code']=mysqli_real_escape_string(static::$db,$data['songserm_code']);

        $sql = " INSERT INTO tb_zone_songserm ( 
            zone_songserm_code,
            zone_code,
            songserm_code,
            addby,
            adddate
            )  VALUES ('".  
            $data['zone_songserm_code']."','".
            $data['zone_code']."','".
            $data['songserm_code']."','".
            $data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteZoneSongsermByCode($code){
        $sql = " DELETE FROM tb_zone_songserm WHERE zone_songserm_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>