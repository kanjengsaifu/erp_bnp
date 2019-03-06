<?php

require_once("BaseModel.php");
class ProductMaterialModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProductMaterialLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_material_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_product_material 
        WHERE product_material_code LIKE ('$code%') 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProductMaterialBy($product_material_name = ''){
        $sql = " SELECT *    
        FROM tb_product_material 
        WHERE product_material_name LIKE ('%$product_material_name%') 
        ORDER BY product_material_name  
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

    function getProductMaterialByProductCode($product_code = ''){
        $sql = " SELECT *    
        FROM tb_product_material 
        LEFT JOIN tb_material ON tb_product_material.material_code = tb_material.material_code 
        WHERE product_code ='$product_code'  
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

    function getProductMaterialByMaterialCode($material_code = '' ,$search = ''){
        $str_search ='';
        if($search != ''){
            $str_search =" AND CONCAT(tb_product.product_code,tb_product.product_name) LIKE ('%$search%') ";
        }
        $sql = " SELECT *    
        FROM tb_product_material 
        LEFT JOIN tb_material ON tb_product_material.material_code = tb_material.material_code 
        LEFT JOIN tb_product ON tb_product_material.product_code = tb_product.product_code 
        WHERE tb_product_material.material_code ='$material_code'  
        $str_search
        ORDER BY product_name 
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

    function getProductMaterialByCode($code){
        $sql = " SELECT * 
        FROM tb_product_material 
        WHERE product_material_code = '$code' 
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

    function getProductMaterialPriceByCode($product_code,$material_code){
        $sql = " SELECT * 
        FROM tb_product_material 
        WHERE product_code = '$product_code' AND material_code = '$material_code' 
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

    function updateProductMaterialPriceByCode($data = []){
        $sql = " UPDATE tb_product_material SET      
        product_material_buyprice = '".$data['product_material_buyprice']."' 
        WHERE material_code = '".$data['material_code']."' AND product_code = '".$data['product_code']."' 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateProductMaterialByCode($code,$data = []){
        $sql = " UPDATE tb_product_material SET     
        material_code = '".$data['material_code']."',  
        product_material_amount = '".$data['product_material_amount']."'   
        WHERE product_material_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductMaterial($data = []){
        $sql = " INSERT INTO tb_product_material (
            product_material_code,
            product_code,
            material_code,
            product_material_amount 
        ) VALUES (
            '".$data['product_material_code']."', 
            '".$data['product_code']."', 
            '".$data['material_code']."',  
            '".$data['product_material_amount']."' 
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductMaterialByCode($code){
        $sql = " DELETE FROM tb_product_material WHERE product_material_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>