<?php
require_once("BaseModel.php");

class CompanyModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCompanyLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(company_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_company 
        WHERE company_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getCompanyBy($company_name = ''){
        $sql = " SELECT *   
        FROM tb_company  
        WHERE company_name_th LIKE ('%$company_name%') OR company_name_en LIKE ('%$company_name%') 
        ORDER BY company_name_th  
        ";

        if ($result = mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCompanyByCode($code){
        $sql = " SELECT * 
        FROM tb_company  
        WHERE company_code = '$code' 
        ";

        if ($result = mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
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
           return $code;
        }else {
            return false;
        }


    }

    function insertCompany($data = []){
        $sql = " INSERT INTO tb_company (
            company_code,
            company_name_th,
            company_name_en,
            company_address_1,
            company_address_2,
            company_address_3,
            company_tax,
            company_tel,
            company_fax,
            company_email,
            company_branch,
            company_image,
            company_vat_type,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['company_code']."', 
            '".$data['company_name_th']."', 
            '".$data['company_name_en']."', 
            '".$data['company_address_1']."',  
            '".$data['company_address_2']."', 
            '".$data['company_address_3']."',
            '".$data['company_tax']."', 
            '".$data['company_tel']."', 
            '".$data['company_fax']."', 
            '".$data['company_email']."', 
            '".$data['company_branch']."', 
            '".$data['company_image']."', 
            '".$data['company_vat_type']."', 
            '".$data['updateby']."', 
            NOW()  
        ); 
        ";

        if (mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
           return $data['company_code'];
        }else {
            return false;
        }

    }


    function deleteCompanyByCode($code){
        $sql = " DELETE FROM tb_company WHERE company_code = '$code' ";
        mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>