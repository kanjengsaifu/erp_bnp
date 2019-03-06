<?php

require_once("BaseModel.php");
class MaterialSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getMaterialSupplierLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(material_supplier_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_material_supplier 
        WHERE material_supplier_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getMaterialSupplierBy($material_supplier_name = ''){
        $sql = " SELECT *    
        FROM tb_material_supplier 
        WHERE material_supplier_name LIKE ('%$material_supplier_name%') 
        ORDER BY material_supplier_name  
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

    function getMaterialSupplierByMaterialCode($material_code = ''){
        $sql = " SELECT *    
        FROM tb_material_supplier 
        LEFT JOIN tb_supplier ON tb_material_supplier.supplier_code = tb_supplier.supplier_code 
        WHERE material_code ='$material_code'  
        ORDER BY supplier_name_en 
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

    function getMaterialSupplierBySupplierCode($supplier_code = '' ,$search = ''){
        $str_search ='';
        if($search != ''){
            $str_search =" AND CONCAT(tb_material.material_code,tb_material.material_name) LIKE ('%$search%') ";
        }
        $sql = " SELECT *    
        FROM tb_material_supplier 
        LEFT JOIN tb_supplier ON tb_material_supplier.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_material ON tb_material_supplier.material_code = tb_material.material_code 
        WHERE tb_material_supplier.supplier_code ='$supplier_code'  
        $str_search
        ORDER BY material_name 
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

    function getMaterialSupplierByCode($code){
        $sql = " SELECT * 
        FROM tb_material_supplier 
        WHERE material_supplier_code = '$code' 
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

    function getMaterialSupplierPriceByCode($material_code,$supplier_code){
        $sql = " SELECT * 
        FROM tb_material_supplier 
        WHERE material_code = '$material_code' AND supplier_code = '$supplier_code' 
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

    function updateMaterialSupplierPriceByCode($data = []){
        $sql = " UPDATE tb_material_supplier SET      
        material_supplier_buyprice = '".$data['material_supplier_buyprice']."' 
        WHERE supplier_code = '".$data['supplier_code']."' AND material_code = '".$data['material_code']."' 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateMaterialSupplierByCode($code,$data = []){
        $sql = " UPDATE tb_material_supplier SET     
        supplier_code = '".$data['supplier_code']."', 
        material_supplier_buyprice = '".$data['material_supplier_buyprice']."', 
        material_supplier_lead_time = '".$data['material_supplier_lead_time']."'   
        WHERE material_supplier_code = '$code' 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertMaterialSupplier($data = []){
        $sql = " INSERT INTO tb_material_supplier (
            material_supplier_code,
            material_code,
            supplier_code,
            material_supplier_buyprice,
            material_supplier_lead_time
        ) VALUES (
            '".$data['material_supplier_code']."', 
            '".$data['material_code']."', 
            '".$data['supplier_code']."', 
            '".$data['material_supplier_buyprice']."', 
            '".$data['material_supplier_lead_time']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteMaterialSupplierByCode($code){
        $sql = " DELETE FROM tb_material_supplier WHERE material_supplier_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>