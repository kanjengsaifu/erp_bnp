<?php
require_once("BaseModel.php");

class ProductSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductSupplierLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_supplier_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_product_supplier 
        WHERE product_supplier_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProductSupplierBy(){
        $sql = " SELECT *    
        FROM tb_product_supplier
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

    function getProductSupplierByProductCode($product_code = ''){
        $sql = " SELECT *    
        FROM tb_product_supplier 
        LEFT JOIN tb_supplier ON tb_product_supplier.supplier_code = tb_supplier.supplier_code 
        WHERE product_code ='$product_code'  
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

    function getProductSupplierBySupplierCode($supplier_code = '' ,$search = ''){
        $str_search ='';
        if($search != ''){
            $str_search =" AND CONCAT(tb_product.product_code,tb_product.product_name) LIKE ('%$search%') ";
        }
        $sql = " SELECT *    
        FROM tb_product_supplier 
        LEFT JOIN tb_supplier ON tb_product_supplier.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_product ON tb_product_supplier.product_code = tb_product.product_code 
        WHERE tb_product_supplier.supplier_code ='$supplier_code'  
        $str_search
        ORDER BY product_name 
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

    function getProductSupplierByCode($code){
        $sql = " SELECT * 
        FROM tb_product_supplier 
        WHERE product_supplier_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getProductSupplierPriceByCode($product_code,$supplier_code){
        $sql = " SELECT * 
        FROM tb_product_supplier 
        WHERE product_code = '$product_code' AND supplier_code = '$supplier_code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateProductSupplierPriceByCode($data = []){
        $sql = " UPDATE tb_product_supplier SET      
        product_buyprice = '".$data['product_buyprice']."',
        updateby = '".$data['updateby']."',
        lastupdate = NOW()
        WHERE supplier_code = '".$data['supplier_code']."' AND product_code = '".$data['product_code']."' 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updateProductSupplierByCode($code,$data = []){
        $sql = " UPDATE tb_product_supplier SET     
        supplier_code = '".$data['supplier_code']."', 
        product_buyprice = '".$data['product_buyprice']."', 
        updateby = '".$data['updateby']."',
        lastupdate = NOW()
        WHERE product_supplier_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertProductSupplier($data = []){
        $sql = " INSERT INTO tb_product_supplier (
            product_supplier_code,
            product_code,
            supplier_code,
            product_buyprice,
            addby,
            adddate
        ) VALUES (
            '".$data['product_supplier_code']."', 
            '".$data['product_code']."', 
            '".$data['supplier_code']."', 
            '".$data['product_buyprice']."', 
            '".$data['addby']."',
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteProductSupplierByCode($code){
        $sql = " DELETE FROM tb_product_supplier WHERE product_supplier_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>