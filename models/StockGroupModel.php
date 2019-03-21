<?php
require_once("BaseModel.php");

class StockGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockGroupLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(stock_type_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_stock_group 
        WHERE stock_type_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getStockGroupBy(){
        $sql = "SELECT * 
        FROM tb_stock_group 
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

    function getStockGroupByType($type){
        $sql = "SELECT * 
        FROM tb_stock_group 
        WHERE stock_type_code = '$type' 
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

    function getStockGroupByProductCode($product_code = ""){
        $sql = "SELECT * 
        FROM tb_stock_group 
        LEFT JOIN tb_stock_type ON tb_stock_group.stock_type_code = tb_stock_type.stock_type_code 
        LEFT JOIN tb_stock_report ON tb_stock_group.stock_group_code = tb_stock_report.stock_group_code 
        WHERE tb_stock_report.product_code = '$product_code' 
        AND stock_report_qty > 0 
        GROUP BY tb_stock_group.stock_group_code ";
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getStockGroupByCode($code){
        $sql = "SELECT * 
        FROM tb_stock_group 
        WHERE stock_group_code = '$code'
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getQtyBy($code,$product_code){
        $sql = "SELECT * 
        FROM tb_stock_report 
        WHERE stock_group_code = '$code' AND product_code = '$product_code'
        "; 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }


    function updateTableName($stock_group_code,$table_name){
        $sql = " UPDATE tb_stock_group SET 
        table_name = '".$table_name."' 
        WHERE stock_group_code = $stock_group_code 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function setPrimaryByCode($stock_type_code,$stock_group_code){
        $sql = " UPDATE tb_stock_group SET 
        stock_group_primary = '0' 
        WHERE stock_type_code = '$stock_type_code' 
        ";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_stock_group SET 
        stock_group_primary = '1'  
        WHERE stock_group_code = '$stock_group_code'  
        ";
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateStockGroupByCode($code,$data = []){
        $data['stock_group_name']=mysqli_real_escape_string(static::$db,$data['stock_group_name']);
        $data['stock_group_description']=mysqli_real_escape_string(static::$db,$data['stock_group_description']);
        $data['stock_group_day']=mysqli_real_escape_string(static::$db,$data['stock_group_day']);

        $sql = " UPDATE tb_stock_group SET 
        stock_group_name = '".$data['stock_group_name']."',  
        stock_group_description = '".$data['stock_group_description']."', 
        stock_group_day = '".$data['stock_group_day']."',
        notification = '".$data['notification']."', 
        admin_code = '".$data['admin_code']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE stock_group_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertStockGroup($data = []){
        $data['stock_group_code']=mysqli_real_escape_string(static::$db,$data['stock_group_code']);
        $data['stock_group_name']=mysqli_real_escape_string(static::$db,$data['stock_group_name']);
        $data['stock_group_description']=mysqli_real_escape_string(static::$db,$data['stock_group_description']);
        $data['stock_group_day']=mysqli_real_escape_string(static::$db,$data['stock_group_day']);

        $sql = "INSERT INTO tb_stock_group ( 
            stock_group_code,  
            stock_type_code,
            stock_group_name,  
            stock_group_description, 
            stock_group_day, 
            notification, 
            admin_code, 
            addby,
            adddate
        ) VALUES (
            '".$data['stock_group_code']."', 
            '".$data['stock_type_code']."', 
            '".$data['stock_group_name']."', 
            '".$data['stock_group_description']."', 
            '".$data['stock_group_day']."', 
            '".$data['notification']."', 
            '".$data['admin_code']."', 
            '".$data['addby']."', 
            NOW()  
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else{
            return false;
        }
    }

    function deleteStockGroupByCode($id){
        $sql = " DELETE FROM tb_stock_group WHERE stock_group_code = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }
}
?>