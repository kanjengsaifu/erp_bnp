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
        IFNULL(( SELECT SUM(IFNULL(invoice_supplier_list_qty,0)) FROM tb_invoice_supplier_list WHERE purchase_order_list_code = tb.purchase_order_list_code),0) as list_receive_qty , 
        stock_group_code, 
        purchase_order_list_qty, 
        purchase_order_list_price, 
        purchase_order_list_price_sum, 
        purchase_order_list_remark, 
        supplier_qty, 
        supplier_remark 
        FROM tb_purchase_order_list as tb 
        LEFT JOIN tb_product ON tb.product_code = tb_product.product_code  
        WHERE purchase_order_code = '$purchase_order_code' 
        ORDER BY purchase_order_list_no, purchase_order_list_code 
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

    function getPurchaseOrderListByCode($code){
        $sql = "SELECT * ,
        IFNULL((
            SELECT SUM(IFNULL(invoice_supplier_list_qty,0)) 
            FROM tb_invoice_supplier_list 
            WHERE purchase_order_list_code = tb.purchase_order_list_code),0) as list_receive_qty 
        FROM tb_purchase_order_list as tb
        WHERE purchase_order_list_code = '$code' 
        ";
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getPurchaseOrderListCodeByOther($purchase_order_code,$purchase_order_list_no){
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
            return $data[$purchase_order_list_no-1]['purchase_order_list_code'];
        }
    }

    function insertPurchaseOrderList($data = []){
        $sql = " INSERT INTO tb_purchase_order_list ( 
            purchase_order_list_code,
            purchase_order_code,
            product_code,
            stock_group_code,
            purchase_order_list_no,
            purchase_order_list_qty,
            purchase_order_list_price, 
            purchase_order_list_price_sum,
            purchase_order_list_remark,
            addby,
            adddate
        ) VALUES ( 
            '".$data['purchase_order_list_code']."', 
            '".$data['purchase_order_code']."', 
            '".$data['product_code']."', 
            '".$data['stock_group_code']."', 
            '".$data['purchase_order_list_no']."', 
            '".$data['purchase_order_list_qty']."', 
            '".$data['purchase_order_list_price']."', 
            '".$data['purchase_order_list_price_sum']."', 
            '".$data['purchase_order_list_remark']."',
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
            purchase_order_list_qty = '".$data['purchase_order_list_qty']."',
            purchase_order_list_price = '".$data['purchase_order_list_price']."', 
            purchase_order_list_price_sum = '".$data['purchase_order_list_price_sum']."',
            purchase_order_list_remark = '".$data['purchase_order_list_remark']."'
            WHERE purchase_order_list_code = '$code'
        ";

        echo $sql;

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
  
        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$code' AND purchase_order_list_code NOT IN ($str) ";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>