<?php
require_once("BaseModel.php");

class AddressModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProvinceBy(){
        $sql = "SELECT * 
        FROM tb_province 
        ORDER BY PROVINCE_NAME";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getProvinceInfoBy(){
        $sql = "SELECT tb_province.* , (
            SELECT COUNT(AMPHUR_ID) 
            FROM tb_amphur 
            WHERE tb_province.PROVINCE_ID = tb_amphur.PROVINCE_ID
        ) AS Amphur 
        FROM tb_province 
        ORDER BY PROVINCE_NAME";

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
        $sql = "SELECT tb_province.* , (
            SELECT COUNT(AMPHUR_ID) 
            FROM tb_amphur 
            WHERE tb_province.PROVINCE_ID = tb_amphur.PROVINCE_ID
        ) AS Amphur 
        FROM tb_province 
        WHERE PROVINCE_ID = '$id'
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

    function getAmphurByProviceID($id){
        $sql = "SELECT * 
        FROM tb_amphur 
        WHERE PROVINCE_ID = '$id'
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

    function getAmphurInfoByProviceID($id){
        $sql = "SELECT * , (
            SELECT COUNT(DISTRICT_ID) 
            FROM tb_district 
            WHERE tb_amphur.AMPHUR_ID = tb_district.AMPHUR_ID
        ) AS District 
        FROM tb_amphur 
        WHERE PROVINCE_ID = '$id'
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
        $sql = "SELECT tb_amphur.* , PROVINCE_NAME, (
            SELECT COUNT(DISTRICT_ID) 
            FROM tb_district 
            WHERE tb_amphur.AMPHUR_ID = tb_district.AMPHUR_ID
        ) AS District 
        FROM tb_amphur 
        LEFT JOIN tb_province ON tb_amphur.PROVINCE_ID = tb_province.PROVINCE_ID
        WHERE AMPHUR_ID = '$id'
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

    function getDistrictByAmphurID($id){
        $sql = "SELECT * 
        FROM tb_district
        WHERE AMPHUR_ID = '$id'
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

    function getDistrictInfoByAmphurID($id){
        $sql = "SELECT tb_district.* , (
            SELECT COUNT(VILLAGE_ID) 
            FROM tb_village 
            WHERE tb_village.DISTRICT_ID = tb_district.DISTRICT_ID
        ) AS Village 
        FROM tb_district
        WHERE AMPHUR_ID = '$id'
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

    function getDistrictByID($id){
        $sql = "SELECT tb_district.*, AMPHUR_NAME, PROVINCE_NAME, (
            SELECT COUNT(VILLAGE_ID) 
            FROM tb_village 
            WHERE tb_village.DISTRICT_ID = tb_district.DISTRICT_ID
        ) AS Village 
        FROM tb_district 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID
        WHERE DISTRICT_ID = '$id'
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

    function getVillageByDistrictID($id){
        $sql = "SELECT * 
        FROM tb_village 
        WHERE DISTRICT_ID = '$id'
        ORDER BY VILLAGE_NO,VILLAGE_NAME";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getVillageByID($id){
        $sql = "SELECT * 
        FROM tb_village 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID
        WHERE VILLAGE_ID = '$id'
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

    function getZipcodeByAmphurID($id){
        $sql = "SELECT *
        FROM tb_amphur
        WHERE AMPHUR_ID = '$id'
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

    function updateVillageByID($id,$data = []){
        $data['VILLAGE_CODE']=mysqli_real_escape_string(static::$db,$data['VILLAGE_CODE']);
        $data['VILLAGE_NO']=mysqli_real_escape_string(static::$db,$data['VILLAGE_NO']);
        $data['VILLAGE_NAME']=mysqli_real_escape_string(static::$db,$data['VILLAGE_NAME']);

        $sql = " UPDATE tb_village SET     
        VILLAGE_CODE = '".$data['VILLAGE_CODE']."',
        VILLAGE_NO = '".$data['VILLAGE_NO']."', 
        DISTRICT_ID = '".$data['DISTRICT_ID']."', 
        VILLAGE_NAME = '".$data['VILLAGE_NAME']."'
        WHERE VILLAGE_ID = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertVillage($data = []){
        $data['VILLAGE_CODE']=mysqli_real_escape_string(static::$db,$data['VILLAGE_CODE']);
        $data['VILLAGE_NO']=mysqli_real_escape_string(static::$db,$data['VILLAGE_NO']);
        $data['VILLAGE_NAME']=mysqli_real_escape_string(static::$db,$data['VILLAGE_NAME']);

        $sql = "INSERT INTO tb_village ( 
            VILLAGE_CODE,
            VILLAGE_NO, 
            DISTRICT_ID,
            VILLAGE_NAME
            )  VALUES ('".  
            $data['VILLAGE_CODE']."','".
            $data['VILLAGE_NO']."','".
            $data['DISTRICT_ID']."','".
            $data['VILLAGE_NAME']."'
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteVillageByID($id){
        $sql = " DELETE FROM tb_village WHERE VILLAGE_ID = '$id' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>