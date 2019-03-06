<?php
require_once("BaseModel.php");

class SongsermStatusModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->songsermname, $this->password, $this->db_name);
        }
    }

    function getSongsermStatusBy($name = ''){
        $sql = "SELECT * FROM tb_songserm_status WHERE  songserm_status_name LIKE ('%$name%') 
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

    function getSongsermStatusByID($id){
        $sql = " SELECT * 
        FROM tb_songserm_status 
        WHERE songserm_status_id = '$id' 
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

    function updateSongsermStatusByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_songserm_status 
        WHERE songserm_status_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }

    function deleteSongsermStatusByID($id){
        $sql = " DELETE FROM tb_songserm_status WHERE songserm_status_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>