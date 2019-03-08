<?php

require_once("BaseModel.php");
class ProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProductLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_product 
        WHERE product_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProductBy($supplier_code = '', $keyword  = ''){
        
        if($supplier_code != ""){
            $supplier = "AND tb_product_supplier.supplier_code = '$supplier_code' ";
        }  
        
        if($keyword != ""){
            $sts_keyword = " AND (product_name LIKE ('%$keyword%') OR tb_product.product_code LIKE ('%$keyword%') ) ";
        }

        
        $sql = " SELECT tb_product.* ,CONCAT(product_type_name,' ',product_brand_name,' ',product_name) AS name 
        FROM tb_product  
        LEFT JOIN tb_product_supplier ON tb_product.product_code = tb_product_supplier.product_code  
        LEFT JOIN tb_product_type ON tb_product.product_type_code = tb_product_type.product_type_code  
        LEFT JOIN tb_product_brand ON tb_product.product_brand_code = tb_product_brand.product_brand_code  
        WHERE 1 
        $supplier
        $sts_keyword 
        GROUP BY tb_product.product_code
        ORDER BY tb_product.product_code  
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

    function getProductByProjectCode($project_code = ''){

        $str_project_code="";

        if($project_code!=''){
            $str_project_code = " AND tb_product.product_code NOT IN ( SELECT product_code 
            FROM tb_project_product 
            WHERE project_code = '$project_code'  ) ";
        }
         
        $sql = " SELECT tb_product.* ,CONCAT(product_type_name,' ',product_brand_name,' ',product_name) AS name 
        FROM tb_product   
        LEFT JOIN tb_product_supplier ON tb_product.product_code = tb_product_supplier.product_code  
        LEFT JOIN tb_product_type ON tb_product.product_type_code = tb_product_type.product_type_code  
        LEFT JOIN tb_product_brand ON tb_product.product_brand_code = tb_product_brand.product_brand_code  
        WHERE 1  
        $str_project_code
        GROUP BY tb_product.product_code
        ORDER BY tb_product.product_code  
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

    function getProductByCode($product_code){
        $sql = "SELECT * 
        FROM tb_product  
        LEFT JOIN tb_unit ON tb_product.unit_code = tb_unit.unit_code 
        WHERE tb_product.product_code = '$product_code' 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }
 

    function updateProductByCode($code,$data = []){
        $sql = " UPDATE tb_product SET     
        product_name = '".static::$db->real_escape_string($data['product_name'])."', 
        product_price = '".$data['product_price']."', 
        product_logo = '".static::$db->real_escape_string($data['product_logo'])."',  
        unit_code = '".$data['unit_code']."', 
        product_type_code = '".$data['product_type_code']."', 
        product_brand_code = '".$data['product_brand_code']."',  
        product_description = '".static::$db->real_escape_string($data['product_description'])."',  
        updateby = '".$data['updateby']."',  
        lastupdate = NOW()   
        WHERE product_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProduct($data = []){
        $sql = " INSERT INTO tb_product (
            product_code,
            product_name,
            product_price,
            product_logo, 
            unit_code, 
            product_type_code, 
            product_brand_code, 
            product_description,
            addby,
            adddate ,
            updateby,
            lastupdate 
        ) VALUES (
            '".$data['product_code']."', 
            '".static::$db->real_escape_string($data['product_name'])."', 
            '".$data['product_price']."', 
            '".static::$db->real_escape_string($data['product_logo'])."',  
            '".$data['unit_code']."', 
            '".$data['product_type_code']."', 
            '".$data['product_brand_code']."', 
            '".static::$db->real_escape_string($data['product_description'])."', 
            '".$data['addby']."',
            NOW() , 
            '".$data['addby']."',
            NOW() 
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return $data['product_code'];
        }else {
            return false;
        }

    }


    function deleteProductByCode($code){
        $sql = " DELETE FROM tb_product WHERE product_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_product_supplier WHERE product_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_product_material WHERE product_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>