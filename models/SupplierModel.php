<?php
require_once("BaseModel.php");

class SupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getSupplierLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(supplier_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_supplier 
        WHERE supplier_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getSupplierBy(){
        $sql = " SELECT supplier_code, supplier_name_th, supplier_name_en , supplier_tax , supplier_tel, supplier_email   
        FROM tb_supplier  
        ORDER BY supplier_name_en 
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

    
    function checkSupplierBy($code){
        $str_code=""; 
        if($code!=""){
            $str_code = " AND supplier_code = '$code' ";
        } 
        $sql = " SELECT * 
        FROM tb_supplier 
        WHERE  1 
        $str_code   
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

 

    function getSupplierByCode($code){
        $sql = " SELECT * 
        FROM tb_supplier  
        WHERE supplier_code = '$code' 
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

    function getSupplierByPO($purchase_order_code){
        $sql = " SELECT tb_supplier.* 
        FROM tb_supplier 
        INNER JOIN tb_purchase_order ON tb_supplier.supplier_code = tb_purchase_order.supplier_code 
        WHERE purchase_order_code = '$purchase_order_code' 
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

    function updateSupplierByCode($code,$data = []){
        $sql = " UPDATE tb_supplier SET   
        supplier_name_th = '".$data['supplier_name_th']."', 
        supplier_name_en = '".$data['supplier_name_en']."',  
        supplier_tax = '".$data['supplier_tax']."', 
        supplier_address_1 = '".$data['supplier_address_1']."', 
        supplier_address_2 = '".$data['supplier_address_2']."', 
        supplier_address_3 = '".$data['supplier_address_3']."', 
        supplier_zipcode = '".$data['supplier_zipcode']."', 
        supplier_tel = '".$data['supplier_tel']."', 
        supplier_fax = '".$data['supplier_fax']."', 
        supplier_email = '".$data['supplier_email']."',  
        supplier_remark = '".$data['supplier_remark']."', 
        supplier_branch = '".$data['supplier_branch']."',  
        credit_day = '".$data['credit_day']."', 
        condition_pay = '".$data['condition_pay']."', 
        pay_limit = '".$data['pay_limit']."' , 
        account_id = '".$data['account_id']."', 
        vat_type = '".$data['vat_type']."', 
        vat = '".$data['vat']."',  
        supplier_logo = '".$data['supplier_logo']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE supplier_code = '$code' 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }


    }

    

    function insertSupplier($data = []){
        $sql = " INSERT INTO tb_supplier ( 
            supplier_code,
            supplier_name_th,
            supplier_name_en, 
            supplier_tax,
            supplier_address_1,
            supplier_address_2,
            supplier_address_3,
            supplier_zipcode,
            supplier_tel, 
            supplier_fax, 
            supplier_email,  
            supplier_remark, 
            supplier_branch,  
            credit_day, 
            condition_pay,  
            pay_limit, 
            account_id, 
            vat_type, 
            vat,   
            supplier_logo,
            addby,
            adddate
        ) VALUES ( 
            '".$data['supplier_code']."', 
            '".$data['supplier_name_th']."', 
            '".$data['supplier_name_en']."',  
            '".$data['supplier_tax']."', 
            '".$data['supplier_address_1']."', 
            '".$data['supplier_address_2']."', 
            '".$data['supplier_address_3']."', 
            '".$data['supplier_zipcode']."', 
            '".$data['supplier_tel']."', 
            '".$data['supplier_fax']."', 
            '".$data['supplier_email']."',   
            '".$data['supplier_remark']."', 
            '".$data['supplier_branch']."',  
            '".$data['credit_day']."', 
            '".$data['condition_pay']."',  
            '".$data['pay_limit']."', 
            '".$data['account_id']."', 
            '".$data['vat_type']."', 
            '".$data['vat']."',   
            '".$data['supplier_logo']."',    
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['supplier_code'];
        }else {
            return false;
        }

    }


    function deleteSupplierByCode($code){
        $sql = " DELETE FROM tb_supplier WHERE supplier_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>