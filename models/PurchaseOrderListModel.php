<?php
require_once("BaseModel.php");

class PurchaseOrderListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_order_list_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_purchase_order_list
        WHERE purchase_order_list_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getPurchaseOrderListBy($purchase_order_code){
        $sql = " SELECT tb.product_code,  
        product_name,   
        purchase_order_list_code,   
        IFNULL(( SELECT SUM(IFNULL(list_qty,0)) FROM tb_invoice_supplier_list WHERE purchase_order_list_code = tb.purchase_order_list_code),0) as list_recieve_qty , 
        stock_group_code, 
        list_qty, 
        list_price, 
        list_price_sum, 
        list_remark, 
        supplier_qty, 
        supplier_remark 
        FROM tb_purchase_order_list as tb 
        LEFT JOIN tb_product ON tb.product_code = tb_product.product_code  
        WHERE purchase_order_code = '$purchase_order_code' 
        ORDER BY list_no, purchase_order_list_code 
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

    function getPurchaseOrderListCodeByOther($purchase_order_code,$list_no){
        $sql ="SELECT * 
        FROM tb_purchase_order_list 
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code 
        WHERE purchase_order_code = '$purchase_order_code'"; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data[$list_no-1]['purchase_order_list_code'];
        }
    }

    function insertPurchaseOrderList($data = []){
        $sql = " INSERT INTO tb_purchase_order_list ( 
            purchase_order_list_code,
            purchase_order_code,
            product_code,
            stock_group_code,
            list_no,
            list_qty,
            list_price, 
            list_price_sum,
            list_remark,
            addby,
            adddate
        ) VALUES ( 
            '".$data['purchase_order_list_code']."', 
            '".$data['purchase_order_code']."', 
            '".$data['product_code']."', 
            '".$data['stock_group_code']."', 
            '".$data['list_no']."', 
            '".$data['list_qty']."', 
            '".$data['list_price']."', 
            '".$data['list_price_sum']."', 
            '".$data['list_remark']."',
            '".$data['addby']."', 
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderListByCode($data,$code){
        $sql = " UPDATE tb_purchase_order_list SET 
            supplier_qty = '".$data['supplier_qty']."',
            supplier_remark = '".$data['supplier_remark']."'
            WHERE purchase_order_list_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderListByCodeAdmin($data,$code){
        $sql = " UPDATE tb_purchase_order_list SET 
            list_qty = '".$data['list_qty']."',
            list_price = '".$data['list_price']."', 
            list_price_sum = '".$data['list_price_sum']."',
            list_remark = '".$data['list_remark']."'
            WHERE purchase_order_list_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updateInvoiceSupplierListCode($purchase_order_list_code,$invoice_supplier_list_code){
        $sql = " UPDATE tb_purchase_request_list SET 
            invoice_supplier_list_code = '$invoice_supplier_list_code' 
            WHERE purchase_order_list_code = '$purchase_order_list_code' 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
    
    function updateInvoiceCustomerListCode($purchase_order_list_code,$invoice_customer_list_code){
        $sql = " UPDATE tb_purchase_request_list 
            SET invoice_customer_list_code = '$invoice_customer_list_code' 
            WHERE purchase_order_list_code = '$purchase_order_list_code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deletePurchaseOrderListByCode($code){
        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_list_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deletePurchaseOrderListByPurchaseOrderCode($code){
        $sql = "UPDATE tb_purchase_request_list 
            SET purchase_order_list_code = ''
            WHERE purchase_order_list_code IN (
                SELECT purchase_order_list_code 
                FROM tb_purchase_order_list 
                WHERE purchase_order_code = '$code'
            )
        ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deletePurchaseOrderListByPurchaseOrderCodeNotIN($code,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= "'".$data[$i]."'";
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = "'".$data."'";
        }else{
            $str="'0'";
        }
  
        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$code' AND purchase_order_list_code NOT IN ($str) ";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>