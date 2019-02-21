<?php
require_once("BaseModel.php");

class PurchaseOrderListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_order_list_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_purchase_order_list
        WHERE purchase_order_list_code LIKE ('$code%') 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getPurchaseOrderListBy($purchase_order_code){
        $sql = " SELECT tb.material_code,  
        material_name,   
        tb.purchase_order_list_code,   
        IFNULL(( SELECT SUM(IFNULL(invoice_supplier_list_qty,0)) FROM tb_invoice_supplier_list WHERE  purchase_order_list_code = tb.purchase_order_list_code),0) as purchase_order_list_qty_recieve , 
        tb.stock_group_id, 
        purchase_order_list_qty, 
        purchase_order_list_price, 
        purchase_order_list_price_sum, 
        purchase_order_list_delivery_min,  
        purchase_order_list_delivery_max, 
        purchase_order_list_remark, 
        purchase_order_list_supplier_qty, 
        purchase_order_list_supplier_delivery_min,  
        purchase_order_list_supplier_delivery_max, 
        purchase_order_list_supplier_remark 
        FROM tb_purchase_order_list as tb 
        LEFT JOIN tb_material ON tb.material_code = tb_material.material_code  
        WHERE purchase_order_code = '$purchase_order_code' 
        ORDER BY purchase_order_list_no, tb.purchase_order_list_code 
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

    function getPurchaseOrderListIDByOther($purchase_order_code,$purchase_order_list_no){
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
            return $data[$purchase_order_list_no-1]['purchase_order_list_id'];
        }
    }


    function insertPurchaseOrderList($data = []){
        $sql = " INSERT INTO tb_purchase_order_list ( 
            purchase_order_list_code,
            purchase_order_code,
            purchase_order_list_no,
            material_code,
            stock_group_id,
            purchase_order_list_qty,
            purchase_order_list_price, 
            purchase_order_list_price_sum,
            purchase_order_list_delivery_min, 
            purchase_order_list_delivery_max,
            purchase_order_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES ( 
            '".$data['purchase_order_list_code']."', 
            '".$data['purchase_order_code']."', 
            '".$data['purchase_order_list_no']."', 
            '".$data['material_code']."', 
            '".$data['stock_group_id']."', 
            '".$data['purchase_order_list_qty']."', 
            '".$data['purchase_order_list_price']."', 
            '".$data['purchase_order_list_price_sum']."', 
            '".$data['purchase_order_list_delivery_min']."', 
            '".$data['purchase_order_list_delivery_max']."', 
            '".$data['purchase_order_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['addby']."', 
            NOW() 
        ); 
        ";

//echo $sql."<br><br>";
        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }

    function updatePurchaseOrderListById($data,$id){

        $sql = " UPDATE tb_purchase_order_list 
            SET purchase_order_list_supplier_qty = '".$data['purchase_order_list_supplier_qty']."',
            purchase_order_list_supplier_delivery_min = '".$data['purchase_order_list_supplier_delivery_min']."', 
            purchase_order_list_supplier_delivery_max = '".$data['purchase_order_list_supplier_delivery_max']."',
            purchase_order_list_supplier_remark = '".$data['purchase_order_list_supplier_remark']."'
            WHERE purchase_order_list_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderListByIdAdmin($data,$code){

        $sql = " UPDATE tb_purchase_order_list 
            SET material_code = '".$data['material_code']."', 
            stock_group_id = '".$data['stock_group_id']."',
            purchase_order_list_no = '".$data['purchase_order_list_no']."',
            purchase_order_list_qty = '".$data['purchase_order_list_qty']."',
            purchase_order_list_price = '".$data['purchase_order_list_price']."', 
            purchase_order_list_price_sum = '".$data['purchase_order_list_price_sum']."',
            purchase_order_list_delivery_min = '".$data['purchase_order_list_delivery_min']."', 
            purchase_order_list_delivery_max = '".$data['purchase_order_list_delivery_max']."',
            purchase_order_list_remark = '".$data['purchase_order_list_remark']."'
            WHERE purchase_order_list_code = '$code'
        ";

        //echo $sql."<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateInvoiceSupplierListID($purchase_order_list_id,$invoice_supplier_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET invoice_supplier_list_id = '$invoice_supplier_list_id' 
            WHERE purchase_order_list_id = '$purchase_order_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    
    function updateInvoiceCustomerListID($purchase_order_list_id,$invoice_customer_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET invoice_customer_list_id = '$invoice_customer_list_id' 
            WHERE purchase_order_list_id = '$purchase_order_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deletePurchaseOrderListByID($id){
        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


    function deletePurchaseOrderListByPurchaseOrderID($id){

        $sql = "UPDATE  tb_purchase_request_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_customer_purchase_order_list_detail SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_delivery_note_supplier_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_regrind_supplier_receive_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_standard_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_special_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_regrind_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_code = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);




        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }

    function deletePurchaseOrderListByPurchaseOrderIDNotIN($id,$data){
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
  

        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$id' AND purchase_order_list_code NOT IN ($str) ";
        // echo $sql;
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


    }
}
?>