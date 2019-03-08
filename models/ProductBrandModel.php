<?php

require_once("BaseModel.php");
class ProductBrandModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProductBrandLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_brand_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_product_brand 
        WHERE product_brand_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProductBrandBy($product_brand_name = ''){
        $sql = " SELECT product_brand_code, product_brand_name, product_brand_detail   
        FROM tb_product_brand 
        WHERE product_brand_name LIKE ('%$product_brand_name%') 
        ORDER BY product_brand_name  
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

    function getProductBrandByCode($code){
        $sql = " SELECT * 
        FROM tb_product_brand 
        WHERE product_brand_code = '$code' 
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

    function updateProductBrandByCode($code,$data = []){
        $sql = " UPDATE tb_product_brand SET     
        product_brand_name = '".static::$db->real_escape_string($data['product_brand_name'])."', 
        product_brand_detail = '".static::$db->real_escape_string($data['product_brand_detail'])."'  
        WHERE product_brand_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductBrand($data = []){
        $sql = " INSERT INTO tb_product_brand (
            product_brand_code,
            product_brand_name,
            product_brand_detail
        ) VALUES (
            '".$data['product_brand_code']."', 
            '".static::$db->real_escape_string($data['product_brand_name'])."', 
            '".static::$db->real_escape_string($data['product_brand_detail'])."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductBrandByCode($code){
        $sql = " DELETE FROM tb_product_brand WHERE product_brand_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>