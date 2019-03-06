<?php
require_once("BaseModel.php");

class LicenseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getLicenseLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(license_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_license 
        WHERE license_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getLicenseBy($name = ''){
        $sql = "SELECT * FROM tb_license WHERE license_name LIKE ('%$name%') 
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

    

    function getLicenseByCode($code){
        $sql = " SELECT * 
        FROM tb_license 
        WHERE license_code = '$code' 
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
    function checkLicenseBy($name,$code=''){
        $sql = " SELECT * 
        FROM tb_license 
        WHERE license_name = '$name'  
        ";
        if($code!=''){
            $sql .= " AND license_code != '$code'";
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

    function insertLicense($data=[]){
        $sql = " INSERT INTO tb_license(
            license_code,
            license_name
            ) VALUES (
                '".$data['license_code']."',
                '".$data['license_name']."'
                )";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            return $data['license_code'];
        }else {
            return false;
        }
    }

    function updateLicenseByCode($code,$data = []){
        $sql = " UPDATE tb_license SET 
        license_name = '".$data['license_name']."'
        WHERE license_code = '$code' 
        ";
         if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }


    function deleteLicenseByCode($code){
        $sql = " DELETE FROM tb_license WHERE license_code = '$code' ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return 1;
        }else {
            return 0;
        }
    }
}
?>