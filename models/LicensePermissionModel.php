<?php
require_once("BaseModel.php");

class LicensePermissionModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getLicensePermissionLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(license_permission_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_license_permission 
        WHERE license_permission_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getLicensePermissionBy($name = ''){
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

    function getLicensePermissionByCode($code){
        $sql = " SELECT * 
        FROM tb_license_permission 
        WHERE license_code = '$code' 
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

    function getLicensePermissionByUserCode($code){
        $sql = "SELECT tb_license_permission.menu_code, menu_name, menu_name_en, 
        permission_view , permission_add, permission_edit, permission_approve, permission_cancel, permission_delete
        FROM tb_user 
        LEFT JOIN tb_license ON tb_user.license_code = tb_license.license_code
        LEFT JOIN tb_license_permission ON tb_license.license_code = tb_license_permission.license_code  
        LEFT JOIN tb_menu ON tb_license_permission.menu_code = tb_menu.menu_code  
        WHERE user_code = '$code' 
        ";   
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
    
    function getLicensePermissionByLicenseCode($code,$menu_code=''){
        $sql = " SELECT * 
        FROM tb_license_permission 
        WHERE license_code = '$code' 
        "; 
        if($menu_code!=''){
            $sql .= " AND menu_code = '$menu_code' ";
        }

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    } 

    function insertLicensePermission($data=[]){
        $sql = " INSERT INTO tb_license_permission(
            license_permission_code,
            license_code,
            menu_code,
            permission_view,
            permission_add,
            permission_edit,
            permission_approve,
            permission_cancel, 
            permission_delete 
            ) VALUES (
            '".$data['license_permission_code']."',
            '".$data['license_code']."',
            '".$data['menu_code']."',
            '".$data['permission_view']."',
            '".$data['permission_add']."',
            '".$data['permission_edit']."',
            '".$data['permission_approve']."',
            '".$data['permission_cancel']."',
            '".$data['permission_delete']."'
            )";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            return $data['license_code'];
        }else {
            return false;
        }
    }

    function updateLicensePermissionByCode($code,$data = []){
        $sql = " UPDATE tb_license_permission SET 
        license_code = '".$data['license_code']."',
        menu_code = '".$data['menu_code']."',
        permission_view = '".$data['permission_view']."',
        permission_add = '".$data['permission_add']."',
        permission_edit = '".$data['permission_edit']."',
        permission_approve = '".$data['permission_approve']."',
        permission_cancel = '".$data['permission_cancel']."',
        permission_delete = '".$data['permission_delete']."' 
        WHERE license_permission_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteLicensePermissionByCode($code){
        $sql = " DELETE FROM tb_license_permission WHERE license_code = '$code' ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>