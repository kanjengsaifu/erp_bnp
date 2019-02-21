<?php
require_once("BaseModel.php");

class ProvinceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProvinceBy($name = ''){
        $sql = "SELECT * 
        FROM tb_province 
        WHERE province_name LIKE ('%$name%')  
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

    

    function getProvinceByID($id){
        $sql = " SELECT * 
        FROM tb_province 
        WHERE province_id = '$id' 
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
    function checkProvinceBy($name,$id=''){
        $sql = " SELECT * 
        FROM tb_province 
        WHERE province_name = '$name'  
        ";
        if($id!=''){
            $sql .= " AND province_id != '$id'";
        }
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

    function insertProvince($data=[]){
        $sql = " INSERT INTO tb_province(
            province_name,
            province_icon,
            province_title,
            province_detail,
            province_img 
            ) VALUES (
                '".$data['province_name']."',
                '".$data['province_icon']."',
                '".$data['province_title']."',
                '".$data['province_detail']."',
                '".$data['province_img']."'
            )";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id(static::$db); 
            return $id;
        }else {
            return false;
        }
    }

    function updateProvinceByID($id,$data = []){
        $sql = " UPDATE tb_province SET 
        province_name = '".$data['province_name']."', 
        province_icon = '".$data['province_icon']."', 
        province_title = '".$data['province_title']."', 
        province_detail = '".$data['province_detail']."', 
        province_img = '".$data['province_img']."' 
        WHERE province_id = '$id' 
        ";
         if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }


    function deleteProvinceByID($id){
        $sql = " DELETE FROM tb_province WHERE province_id = '$id' ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }
}
?>