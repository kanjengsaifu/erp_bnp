<?php

require_once("BaseModel.php");
class ProductCategoryModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProductCategoryLastCode($code,$digit){
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

    function getProductCategoryBy($product_category_name = ''){
        $sql = " SELECT product_category_code, product_category_name, stock_event 
        FROM tb_product_category 
        WHERE product_category_name LIKE ('%$product_category_name%') 
        ORDER BY product_category_name 
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

    function getProductCategoryByCode($code){
        $sql = " SELECT * 
        FROM tb_product_category 
        WHERE product_category_code = '$code' 
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

    function updateProductCategoryByCode($code,$data = []){
        $sql = " UPDATE tb_product_category SET     
        product_category_name = '".$data['product_category_name']."', 
        stock_event = '".$data['stock_event']."'  
        WHERE product_category_code = $code 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductCategory($data = []){
        $sql = " INSERT INTO tb_product_category (
            product_category_name,
            stock_event
        ) VALUES (
            '".$data['product_category_name']."', 
            '".$data['stock_event']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductCategoryByCode($code){
        $sql = " DELETE FROM tb_product_category WHERE product_category_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>