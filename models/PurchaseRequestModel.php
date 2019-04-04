<?php
require_once("BaseModel.php");

class PurchaseRequestModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseRequestLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code',LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_request_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_purchase_request 
        WHERE purchase_request_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getPurchaseRequestBy($date_start = "",$date_end = "",$keyword = "",$user_code = ""){
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND purchase_request_date >= STR_TO_DATE('$date_start','%d-%m-%Y') AND purchase_request_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";
        }else if ($date_start != ""){
            $str_date = "AND purchase_request_date >= STR_TO_DATE('$date_start','%d-%m-%Y') ";    
        }else if ($date_end != ""){
            $str_date = "AND purchase_request_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";  
        }

        if($user_code != ""){
            $str_user = "AND tb.employee_code  = '$user_code' ";
        }

        $sql = " SELECT purchase_request_code, 
        tb.employee_code , 
        purchase_request_date, 
        purchase_request_revise_code,
        IFNULL((
            SELECT IF(MAX(tb_purchase_request.purchase_request_revise_no) = tb.purchase_request_revise_no,0,1)
            FROM tb_purchase_request 
            WHERE purchase_request_revise_code = tb.purchase_request_revise_code 
        ),0) as count_revise,
        purchase_request_revise_no,
        purchase_request_code, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        purchase_request_approve_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        purchase_request_cancelled, 
        purchase_request_remark 
        FROM tb_purchase_request as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_code = tb1.user_code 
        LEFT JOIN tb_user as tb2 ON tb.purchase_request_approve_by = tb2.user_code 
        WHERE ( CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR purchase_request_code LIKE ('%$keyword%') 
        ) $str_date $str_user  
        ORDER BY purchase_request_date DESC 
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

    function getPurchaseOrderByPurchaseRequestCode($purchase_request_code){
        $sql =  "SELECT purchase_order_code
        FROM tb_purchase_order AS tb
        WHERE purchase_order_revise_code IN (
            SELECT purchase_order_revise_code
            FROM tb_purchase_request_list
            LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code    
            LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_revise_code
            WHERE purchase_request_code = '$purchase_request_code' AND tb_purchase_order.purchase_order_revise_code = tb.purchase_order_revise_code
            GROUP BY purchase_order_revise_code
        )  AND purchase_order_revise_no = (
            SELECT MAX(purchase_order_revise_no)
            FROM tb_purchase_request_list
            LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code    
            LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_revise_code
            WHERE purchase_request_code = '$purchase_request_code' AND tb_purchase_order.purchase_order_revise_code = tb.purchase_order_revise_code
            GROUP BY purchase_order_revise_code
        )";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getInvoiceSuppliertByPurchaseRequestCode($purchase_request_code){
        $sql =  "SELECT tb_invoice_supplier_list.invoice_supplier_code
                FROM tb_purchase_request_list 
                LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code
                LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code
                LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_code = tb_invoice_supplier.invoice_supplier_code
                WHERE purchase_request_code = '$purchase_request_code' 
                GROUP BY invoice_supplier_code
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


    function getPurchaseOrderByPurchaseRequestListCode($purchase_request_list_code){
        $sql =  "SELECT tb_purchase_order.purchase_order_code,purchase_order_code
                FROM  tb_purchase_request_list
                LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code    
                LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code
                WHERE purchase_request_list_code = '$purchase_request_list_code' 
                GROUP BY tb_purchase_order_list.purchase_order_code
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

    function getInvoiceSuppliertByPurchaseRequestListCode($purchase_request_list_code){
        $sql =  "   SELECT tb_invoice_supplier_list.invoice_supplier_code
                    FROM tb_purchase_request_list 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code
                    LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code
                    LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_code = tb_invoice_supplier.invoice_supplier_code
                    WHERE purchase_request_list_code = '$purchase_request_list_code' 
                    GROUP BY invoice_supplier_code
                
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

    function getPurchaseRequestLitsBy($date_start = "",$date_end = "",$keyword = "",$user_code = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND purchase_request_date >= STR_TO_DATE('$date_start','%d-%m-%Y') AND purchase_request_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";
        }else if ($date_start != ""){
            $str_date = "AND purchase_request_date >= STR_TO_DATE('$date_start','%d-%m-%Y') ";    
        }else if ($date_end != ""){
            $str_date = "AND purchase_request_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";  
        }

        if($user_code != ""){
            $str_user = "AND tb.employee_code  = '$user_code' ";
        }

        $sql =  "SELECT tb_purchase_request.purchase_request_code,purchase_request_list_code,purchase_request_date,purchase_request_code,purchase_request_remark,list_qty,product_name,product_code,purchase_order_code,tb_invoice_supplier.invoice_supplier_code
                FROM tb_purchase_request                    
                LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_code = tb_purchase_request_list.purchase_request_code
                LEFT JOIN tb_product ON tb_purchase_request_list.product_code = tb_product.product_code
                LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code
                LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code
                LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code
                LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_code = tb_invoice_supplier.invoice_supplier_code
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

    function getPurchaseRequestByCode($code){
        $sql = " SELECT tb_purchase_request.* , 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as request_name, user_position_name
        FROM tb_purchase_request 
        LEFT JOIN tb_user ON tb_purchase_request.employee_code = tb_user.user_code 
        LEFT JOIN tb_user_position ON tb_user.user_position_code = tb_user_position.user_position_code 
        WHERE purchase_request_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function cancelPurchaseRequestByCode($code){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function uncancelPurchaseRequestByCode($code){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseRequestByCode($code,$data = []){
        $data['purchase_request_remark']=mysqli_real_escape_string(static::$db,$data['purchase_request_remark']);

        $sql = " UPDATE tb_purchase_request SET  
        employee_code = '".$data['employee_code']."', 
        purchase_request_date = '".$data['purchase_request_date']."', 
        purchase_request_remark = '".$data['purchase_request_remark']."', 
        purchase_request_alert = '".$data['purchase_request_alert']."', 
        purchase_request_approve_status = 'Waiting', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseRequestApproveByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_approve_status = '".$data['purchase_request_approve_status']."', 
        purchase_request_approve_by = '".$data['purchase_request_approve_by']."', 
        purchase_request_approve_date = NOW(), 
        purchase_request_status = 'Approved'
        WHERE purchase_request_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertPurchaseRequest($data = []){
        $data['purchase_request_remark']=mysqli_real_escape_string(static::$db,$data['purchase_request_remark']);

        $sql = " INSERT INTO tb_purchase_request ( 
            purchase_request_code,
            employee_code,
            purchase_request_date,
            purchase_request_remark,
            purchase_request_alert,
            purchase_request_approve_status,
            purchase_request_revise_code,
            purchase_request_revise_no,
            addby,
            adddate) 
            VALUES ('". 
            $data['purchase_request_code']."','".
            $data['employee_code']."','".
            $data['purchase_request_date']."','".
            $data['purchase_request_remark']."','".
            $data['purchase_request_alert']."','".
            "Waiting','".
            $data['purchase_request_revise_code']."','".
            $data['purchase_request_revise_no']."','".
            $data['addby']."',".
            "NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deletePurchaseRequestByCode($code){
        $sql = " DELETE FROM tb_purchase_request WHERE purchase_request_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_request_list WHERE purchase_request_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>