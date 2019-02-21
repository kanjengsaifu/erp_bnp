<?php
require_once("BaseModel.php");

class AmphurModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAmphurBy($name = ''){
        $sql = "SELECT * 
        FROM tb_amphur 
        WHERE amphur_name LIKE ('%$name%') 
        AND province_id='19'
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

    

    function getAmphurByID($id){
        $sql = " SELECT * 
        FROM tb_amphur 
        WHERE amphur_id = '$id' 
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
    function getAmphurByProvinceID($id){
        $sql = " SELECT * 
        FROM tb_amphur  
        WHERE province_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    } 
    
    function checkAmphurBy($name,$id=''){
        $sql = " SELECT * 
        FROM tb_amphur 
        WHERE amphur_name = '$name'  
        ";
        if($id!=''){
            $sql .= " AND amphur_id != '$id'";
        }

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    } 

    function insertAmphur($data=[]){
        $sql = " INSERT INTO tb_amphur(
            amphur_name,
            amphur_icon,
            amphur_title,
            amphur_detail,
            amphur_img 
            ) VALUES (
                '".$data['amphur_name']."',
                '".$data['amphur_icon']."',
                '".$data['amphur_title']."',
                '".$data['amphur_detail']."',
                '".$data['amphur_img']."'
            )";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id(static::$db); 
            return $id;
        }else {
            return false;
        }
    }

    function updateAmphurByID($id,$data = []){
        $sql = " UPDATE tb_amphur SET 
        amphur_name = '".$data['amphur_name']."', 
        amphur_icon = '".$data['amphur_icon']."', 
        amphur_title = '".$data['amphur_title']."', 
        amphur_detail = '".$data['amphur_detail']."', 
        amphur_img = '".$data['amphur_img']."' 
        WHERE amphur_id = '$id' 
        ";
         if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }


    function deleteAmphurByID($id){
        $sql = " DELETE FROM tb_amphur WHERE amphur_id = '$id' ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }
}
?>