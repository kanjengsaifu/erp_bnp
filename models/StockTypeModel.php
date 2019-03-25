<?php
require_once("BaseModel.php");

class StockTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockTypeBy(){
        $sql = "SELECT * 
        FROM tb_stock_type
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
    
    function getStockTypeByCode($stock_type_code){
        $sql = "SELECT * FROM tb_stock_type WHERE stock_type_code = $stock_type_code ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateStockTypeByCode($id,$data = []){
        $sql = " UPDATE tb_stock_type SET 
        stock_type_code = '".$data['stock_type_code']."' , 
        stock_type_name = '".$data['stock_type_name']."'  
        WHERE stock_type_code = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertStockType($data = []){
        $sql = "INSERT INTO tb_stock_type (
            stock_type_code, 
            stock_type_name 
            ) VALUES (  
            '".$data['stock_type_code']."', 
            '".$data['stock_type_name']."' 
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_code(static::$db);
        }else {
            return 0;
        }
    }

    function deleteStockTypeByCode($id){
        $sql = " DELETE FROM tb_stock_type WHERE stock_type_code = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }
}
?>