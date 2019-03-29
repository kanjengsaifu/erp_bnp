<?php
require_once("BaseModel.php");

class PurchaseOrderListDetailModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListDetailBy($code = ""){
        if($code != ""){
            $str = "WHERE purchase_order_list_code = '$code'";
        }

        $sql = "SELECT purchase_order_list_detail_code, purchase_order_list_code, date_recieve, qty_recieve, remark_recieve
            FROM tb_purchase_order_list_detail 
            $str 
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

    function getPurchaseOrderListDetailByCode($code){
        $sql = "  SELECT * FROM tb_purchase_order_list_detail WHERE purchase_order_list_detail_code = '$code' ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updatePurchaseOrderListDetailByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_order_list_detail SET 
        purchase_order_list_code = '".$data['purchase_order_list_code']."' , 
        date_recieve = '".$data['date_recieve']."' , 
        qty_recieve = '".$data['qty_recieve']."' , 
        remark_recieve = '".static::$db->real_escape_string($data['remark_recieve'])."' 
        WHERE purchase_order_list_detail_code = '$code' 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertPurchaseOrderListDetail($data = []){
        $sql = " INSERT INTO tb_purchase_order_list_detail (
            purchase_order_list_code, 
            date_recieve, 
            qty_recieve, 
            remark_recieve 
        ) VALUES (  
            '".$data['purchase_order_list_code']."', 
            '".$data['date_recieve']."', 
            '".$data['qty_recieve']."', 
            '".static::$db->real_escape_string($data['remark_recieve'])."'  
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderId($purchase_order_list_detail_code,$purchase_order_list_code){
        $sql = " UPDATE tb_purchase_order_list_detail 
            SET purchase_order_list_code = '$purchase_order_list_code' 
            WHERE purchase_order_list_detail_code = '$purchase_order_list_detail_code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }


    function deletePurchaseOrderListDetailByCode($code){
        $sql = " DELETE FROM tb_purchase_order_list_detail WHERE purchase_order_list_detail_code = '$code' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

    function deletePurchaseOrderListDetailByCodeNotIN($code,$data){
        $str="'".$data."'";
        if(is_array($data) && count($data) > 0){ 
            $str ="";
            for($i=0; $i<count($data); $i++){
                $str .= "'".$data[$i]."'";
                if($i + 1 < count($data)){
                    $str .= ",";
                }
            }
        }
            
        $sql = "DELETE FROM tb_purchase_order_list_detail WHERE purchase_order_list_code = '$code' AND purchase_order_list_detail_code NOT IN ($str) ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }
}
?>