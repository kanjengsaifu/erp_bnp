<?php

require_once("BaseModel.php");
class ProjectProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getProjectProductLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(project_product_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_project_product 
        WHERE project_product_code LIKE ('$code%') 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getProjectProductBy($project_product_name = ''){
        $sql = " SELECT *    
        FROM tb_project_product 
        WHERE project_product_name LIKE ('%$project_product_name%') 
        ORDER BY project_product_name  
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

    function getProjectProductByProjectCode($project_code = ''){
        $sql = " SELECT * ,CONCAT(product_type_name,' ',product_brand_name,' ',product_name) AS name   
        FROM tb_project_product 
        LEFT JOIN tb_product ON tb_project_product.product_code = tb_product.product_code 
        LEFT JOIN tb_product_type ON tb_product.product_type_code = tb_product_type.product_type_code  
        LEFT JOIN tb_product_brand ON tb_product.product_brand_code = tb_product_brand.product_brand_code  
        WHERE project_code ='$project_code'  
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

    function getProjectProductByProductCode($product_code = '' ,$search = ''){
        $str_search ='';
        if($search != ''){
            $str_search =" AND CONCAT(tb_project.project_code,tb_project.project_name) LIKE ('%$search%') ";
        }
        $sql = " SELECT *    
        FROM tb_project_product 
        LEFT JOIN tb_product ON tb_project_product.product_code = tb_product.product_code 
        LEFT JOIN tb_project ON tb_project_product.project_code = tb_project.project_code 
        WHERE tb_project_product.product_code ='$product_code'  
        $str_search
        ORDER BY project_name 
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

    function getProjectProductByCode($code){
        $sql = " SELECT * 
        FROM tb_project_product 
        WHERE project_product_code = '$code' 
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

    function getProjectProductPriceByCode($project_code,$product_code){
        $sql = " SELECT * 
        FROM tb_project_product 
        WHERE project_code = '$project_code' AND product_code = '$product_code' 
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

    function updateProjectProductPriceByCode($data = []){
        $sql = " UPDATE tb_project_product SET      
        project_product_buyprice = '".$data['project_product_buyprice']."' 
        WHERE product_code = '".$data['product_code']."' AND project_code = '".$data['project_code']."' 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateProjectProductByCode($code,$data = []){
        $sql = " UPDATE tb_project_product SET     
        product_code = '".$data['product_code']."',  
        project_product_amount = '".$data['project_product_amount']."'   
        WHERE project_product_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProjectProduct($data = []){
        $sql = " INSERT INTO tb_project_product (
            project_product_code,
            project_code,
            product_code,
            project_product_amount 
        ) VALUES (
            '".$data['project_product_code']."', 
            '".$data['project_code']."', 
            '".$data['product_code']."',  
            '".$data['project_product_amount']."' 
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProjectProductByCode($code){
        $sql = " DELETE FROM tb_project_product WHERE project_product_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>