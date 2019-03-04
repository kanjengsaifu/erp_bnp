<?php

require_once("BaseModel.php");
class MaterialModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getMaterialLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(material_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_material 
        WHERE material_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getMaterialBy($supplier_code = '', $keyword  = ''){
        
        if($supplier_code != ""){
            $supplier = "AND tb_material_supplier.supplier_code = '$supplier_code' ";
        }  
        
        if($keyword != ""){
            $sts_keyword = " AND (material_name LIKE ('%$keyword%') OR tb_material.material_code LIKE ('%$keyword%') ) ";
        }

        
        $sql = " SELECT tb_material.* 
        FROM tb_material  
        LEFT JOIN tb_material_supplier ON tb_material.material_code = tb_material_supplier.material_code  
        WHERE 1 
        $supplier
        $sts_keyword 
        GROUP BY tb_material.material_code
        ORDER BY tb_material.material_code  
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

    function getMaterialByCode($material_code){
        $sql = "SELECT * 
        FROM tb_material  
        LEFT JOIN tb_material_unit ON tb_material.unit_code = tb_material_unit.unit_code 
        WHERE tb_material.material_code = '$material_code' 
        ";
        // return $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getMaterialByID($code){
        $sql = " SELECT * 
        FROM tb_material 
        WHERE material_code = '$code' 
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

    function updateMaterialByID($code,$data = []){
        $sql = " UPDATE tb_material SET     
        material_name = '".$data['material_name']."', 
        material_logo = '".$data['material_logo']."', 
        material_quantity_per_unit = '".$data['material_quantity_per_unit']."', 
        unit_code = '".$data['unit_code']."', 
        material_minimum_stock = '".$data['material_minimum_stock']."', 
        material_maximum_stock = '".$data['material_maximum_stock']."', 
        material_description = '".$data['material_description']."',  
        updateby = '".$data['updateby']."',  
        lastupdate = NOW()   
        WHERE material_code = '$code' 
        ";

        echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertMaterial($data = []){
        $sql = " INSERT INTO tb_material (
            material_code,
            material_name,
            material_logo,
            material_quantity_per_unit,
            unit_code,
            material_minimum_stock,
            material_maximum_stock,
            material_description,
            addby,
            adddate ,
            updateby,
            lastupdate 
        ) VALUES (
            '".$data['material_code']."', 
            '".$data['material_name']."', 
            '".$data['material_logo']."', 
            '".$data['material_quantity_per_unit']."', 
            '".$data['unit_code']."', 
            '".$data['material_minimum_stock']."', 
            '".$data['material_maximum_stock']."', 
            '".$data['material_description']."', 
            '".$data['addby']."',
            NOW() , 
            '".$data['addby']."',
            NOW() 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return $data['material_code'];
        }else {
            return false;
        }

    }


    function deleteMaterialByID($code){
        $sql = " DELETE FROM tb_material WHERE material_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>