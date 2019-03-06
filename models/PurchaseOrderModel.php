<?php
require_once("BaseModel.php");

class PurchaseOrderModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_order_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_purchase_order 
        WHERE purchase_order_code LIKE ('$code%') 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getPurchaseOrderBy($branch_code,$date_start = "",$date_end = "",$supplier_code = "",$keyword = "",$user_code = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_code != ""){
            $str_user = "AND user_code = '$user_code' ";
        }

        if($supplier_code != ""){
            $str_supplier = "AND tb2.supplier_code = '$supplier_code' ";
        }


        $sql = " SELECT purchase_order_code,  
        purchase_order_code, 
        purchase_order_date,  
        purchase_order_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        purchase_order_credit_term, 
        purchase_order_delivery_date, 
        purchase_order_cancelled,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name, 
        purchase_order_delivery_by 
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.user_code = tb1.user_code 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_code = tb2.supplier_code 
        WHERE tb.branch_code = '$branch_code' 
        AND ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  purchase_order_code LIKE ('%$keyword%') 
            ) 
        $str_supplier 
        $str_date 
        $str_user  
        GROUP BY purchase_order_code
        ORDER BY purchase_order_code DESC 
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

    function getPurchaseOrderViewByCode($code){
        $sql = " SELECT *   
        FROM tb_purchase_order 
        LEFT JOIN tb_user ON tb_purchase_order.user_code = tb_user.user_code 
        LEFT JOIN tb_user_position ON tb_user.user_position_code = tb_user_position.user_position_code 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code  
        WHERE purchase_order_code = '$code' 
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
  
    function getPurchaseOrderByCode($code){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_user ON tb_purchase_order.user_code = tb_user.user_code 
        WHERE purchase_order_code = '$code' 
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

    function getPurchaseOrderByCode($code){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_user ON tb_purchase_order.user_code = tb_user.user_id 
        WHERE purchase_order_code = '$code' 
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

     

   
    function cancelPurchaseOrderByCode($code){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_code = '$code' 
        ";
        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelPurchaseOrderByCode($code){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_order SET  
        supplier_code = '".$data['supplier_code']."', 
        user_code = '".$data['user_code']."',  
        purchase_order_credit_term = '".$data['purchase_order_credit_term']."', 
        purchase_order_delivery_date = '".$data['purchase_order_delivery_date']."',  
        purchase_order_delivery_by = '".$data['purchase_order_delivery_by']."', 
        purchase_order_date = '".$data['purchase_order_date']."', 
        purchase_order_remark = '".$data['purchase_order_remark']."', 
        purchase_order_status = '".$data['purchase_order_status']."', 
        purchase_order_total_price = '".$data['purchase_order_total_price']."', 
        purchase_order_vat_type = '".$data['purchase_order_vat_type']."', 
        purchase_order_vat = '".$data['purchase_order_vat']."', 
        purchase_order_vat_price = '".$data['purchase_order_vat_price']."', 
        purchase_order_net_price = '".$data['purchase_order_net_price']."',
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_code = '$code' 
        ";
        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    } 
 

    function updatePurchaseOrderStatusByCode($id,$data = []){
        if ($data['updateby'] != ""){
            $str = "updateby = '".$data['updateby']."', ";
        }
        
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        $str 
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }   

    function generatePurchaseOrderListBySupplierId( $supplier_code /*รหัสผู้ขาย*/ ){

       
    }

    function insertPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_purchase_order ( 
            purchase_order_code,
            supplier_code,
            user_code,  
            purchase_order_status, 
            purchase_order_credit_term,
            purchase_order_delivery_date,
            purchase_order_delivery_by,
            purchase_order_date,
            purchase_order_remark,
            purchase_order_total_price,
            purchase_order_vat_type,
            purchase_order_vat,
            purchase_order_vat_price,
            purchase_order_net_price,
            branch_code,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES (
            '".$data['purchase_order_code']."',
            '".$data['supplier_code']."',
            '".$data['user_code']."',
            '".$data['purchase_order_status']."',
            '".$data['purchase_order_credit_term']."',
            '".$data['purchase_order_delivery_date']."',
            '".$data['purchase_order_delivery_by']."',
            '".$data['purchase_order_date']."',
            '".$data['purchase_order_remark']."',
            '".$data['purchase_order_total_price']."',
            '".$data['purchase_order_vat_type']."',
            '".$data['purchase_order_vat']."',
            '".$data['purchase_order_vat_price']."',
            '".$data['purchase_order_net_price']."',
            '".$data['branch_code']."',
            '".$data['addby']."',
            NOW(),
            '".$data['addby']."',
            NOW()
        )
        ";

            // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['purchase_order_code'];
        }else {
            return false;
        }

    }


    function deletePurchaseOrderByCode($code){ 

        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        

    }
}
?>