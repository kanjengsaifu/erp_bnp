<?php

require_once("BaseModel.php");
class ProductTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProductTypeLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_type_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_product_type 
        WHERE product_type_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProductTypeBy($product_type_name = ''){
        $sql = " SELECT product_type_code, product_type_name, product_type_detail   
        FROM tb_product_type 
        WHERE product_type_name LIKE ('%$product_type_name%') 
        ORDER BY product_type_name  
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

    function getProductTypeByCode($code){
        $sql = " SELECT * 
        FROM tb_product_type 
        WHERE product_type_code = '$code' 
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

    function updateProductTypeByCode($code,$data = []){
        $sql = " UPDATE tb_product_type SET     
        product_type_name = '".static::$db->real_escape_string($data['product_type_name'])."', 
        product_type_detail = '".static::$db->real_escape_string($data['product_type_detail'])."'  
        WHERE product_type_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductType($data = []){
        $sql = " INSERT INTO tb_product_type (
            product_type_code,
            product_type_name,
            product_type_detail
        ) VALUES (
            '".static::$db->real_escape_string($data['product_type_code'])."', 
            '".static::$db->real_escape_string($data['product_type_name'])."', 
            '".$data['product_type_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductTypeByCode($code){
        $sql = " DELETE FROM tb_product_type WHERE product_type_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>