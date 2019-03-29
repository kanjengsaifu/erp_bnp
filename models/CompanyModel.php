<?php
require_once("BaseModel.php");

class CompanyModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCompany(){
        $sql = " SELECT *   
        FROM tb_company  
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateCompanyByCode($code,$data = []){
        $sql = " UPDATE tb_company SET
        company_name_th = '".$data['company_name_th']."', 
        company_name_en = '".$data['company_name_en']."', 
        company_address_1 = '".$data['company_address_1']."', 
        company_address_2 = '".$data['company_address_2']."', 
        company_address_3 = '".$data['company_address_3']."', 
        company_tax = '".$data['company_tax']."', 
        company_tel = '".$data['company_tel']."', 
        company_fax = '".$data['company_fax']."', 
        company_email = '".$data['company_email']."', 
        company_branch = '".$data['company_branch']."', 
        company_image = '".$data['company_image']."', 
        company_image_rectangle = '".$data['company_image_rectangle']."', 
        company_vat_type = '".$data['company_vat_type']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE company_code = '$code' 
        ";

        if (mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>