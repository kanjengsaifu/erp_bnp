<?php
require_once("BaseModel.php");

class PurchaseOrderModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getPurchaseOrderLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_order_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0')) AS lastcode 
        FROM tb_purchase_order 
        WHERE purchase_order_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getPurchaseOrderBy($date_start = "",$date_end = "",$supplier_code = "",$keyword = "",$user_code = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') AND purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";
        }else if ($date_start != ""){
            $str_date = "AND purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') ";    
        }else if ($date_end != ""){
            $str_date = "AND purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";  
        }

        if($user_code != ""){
            $str_user = "AND employee_code = '$user_code' ";
        }

        if($supplier_code != ""){
            $str_supplier = "AND tb.supplier_code = '$supplier_code' ";
        }

        $sql = " SELECT purchase_order_code, tb.supplier_code,
        purchase_order_code, purchase_order_date, purchase_revise_code, purchase_revise_no,
        purchase_order_status, purchase_approve_status, purchase_credit_term, purchase_order_cancelled, purchase_delivery_by,
        IFNULL((
            SELECT IF(MAX(tb_purchase_order.purchase_revise_no) = tb.purchase_revise_no,0,1)
            FROM tb_purchase_order 
            WHERE purchase_revise_code = tb.purchase_revise_code 
        ),0) as count_revise,
        IFNULL(CONCAT(tb_employee.user_name,' ',tb_employee.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb_purchase_approve.user_name,' ',tb_purchase_approve.user_lastname),'-') as accept_name, 
        IFNULL(supplier_name_en,'-') as supplier_name
        FROM tb_purchase_order as tb 
        LEFT JOIN tb_user as tb_employee ON tb.employee_code = tb_employee.user_code 
        LEFT JOIN tb_user as tb_purchase_approve ON tb.purchase_approve_by = tb_purchase_approve.user_code 
        LEFT JOIN tb_supplier ON tb.supplier_code = tb_supplier.supplier_code 
        WHERE (
            CONCAT(tb_employee.user_name,' ',tb_employee.user_lastname) LIKE ('%$keyword%')
            OR CONCAT(tb_purchase_approve.user_name,' ',tb_purchase_approve.user_lastname) LIKE ('%$keyword%')
            OR purchase_order_code LIKE ('%$keyword%')
        )
        $str_supplier
        $str_user
        GROUP BY purchase_order_code
        ORDER BY purchase_order_code DESC
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

    function getPurchaseOrderInvoiceBy($purchase_order_code) {
        $sql = "SELECT * 
        FROM tb_invoice_supplier
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_code = tb_invoice_supplier_list.invoice_supplier_code
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code 
        WHERE tb_purchase_order_list.purchase_order_code = '$purchase_order_code' 
        GROUP BY tb_invoice_supplier.invoice_supplier_code
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

    function getPurchaseOrderInvoiceProductBy($purchase_order_list_code) {
        $sql = " SELECT 
        tb_invoice_supplier.invoice_supplier_code,
        tb_invoice_supplier.invoice_code_receive,
        tb_invoice_supplier.supplier_code,
        tb_invoice_supplier.invoice_supplier_total_price,
        tb_invoice_supplier.invoice_supplier_vat_price,
        tb_invoice_supplier.invoice_supplier_net_price,
        invoice_supplier_list_qty,
        invoice_supplier_list_price,
        tb_product.product_code,
        tb_product.product_name
        FROM `tb_invoice_supplier`
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_code = tb_invoice_supplier_list.invoice_supplier_code
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code 
        LEFT JOIN tb_product ON tb_purchase_order_list.product_code = tb_product.product_code 
        WHERE tb_purchase_order_list.purchase_order_list_code = '$purchase_order_list_code'
        GROUP BY tb_invoice_supplier.invoice_supplier_code 
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

    function getPurchaseOrderListBy($date_start = "",$date_end = "",$supplier_code = "",$keyword = "") {
        $str_supplier = "";
        $str_date = "";

        if($date_start != "" && $date_end != ""){
            $str_date = " purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') AND purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";
        }else if ($date_start != ""){
            $str_date = " purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') ";    
        }else if ($date_end != ""){
            $str_date = " purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";  
        }

        if($supplier_code != ""){
            $str_supplier = "AND tb_purchase_order.supplier_code = '$supplier_code' ";
        }

        $sql = "SELECT 
        tb_purchase_order.purchase_order_code,
        tb_purchase_order.supplier_code,
        tb_purchase_order.employee_code,
        tb_purchase_order.purchase_order_code,
        purchase_order_status,
        tb_purchase_order.purchase_order_date,
        tb_purchase_order_list.purchase_order_list_code,
        tb_purchase_order_list.product_code,
        tb_purchase_order_list.purchase_list_qty,
        tb_purchase_order_list.purchase_purchase_order_list_price,
        tb_supplier.supplier_code,
        tb_supplier.supplier_code,
        tb_supplier.supplier_name_th,
        tb_supplier.supplier_name_en,
        tb_product.product_code,
        tb_product.product_name,
        tb_invoice_supplier_list.invoice_supplier_list_qty
        FROM `tb_purchase_order`
        LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_code = tb_purchase_order_list.purchase_order_code
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code
        LEFT JOIN tb_product ON tb_purchase_order_list.product_code = tb_product.product_code
        LEFT JOIN tb_invoice_supplier_list ON tb_product.product_code = tb_invoice_supplier_list.product_code
        WHERE tb_purchase_order.purchase_order_code LIKE ('%$keyword%') AND
            $str_date
            $str_supplier
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

    function getPurchaseOrderExport($purchase_order_code = "",$supplier_code = "",$date_start = "",$date_end = "",$keyword=""){
        $str_code = "";
        $str_supplier = "";
        $str_date = ""; 

        if($purchase_order_code == ""){

            if($date_start != "" && $date_end != ""){
                $str_date = "AND purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') AND purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";
            }else if ($date_start != ""){
                $str_date = "AND purchase_order_date >= STR_TO_DATE('$date_start','%d-%m-%Y') ";    
            }else if ($date_end != ""){
                $str_date = "AND purchase_order_date <= STR_TO_DATE('$date_end','%d-%m-%Y') ";  
            }

            if($supplier_code != ""){
                $str_supplier = "AND tb_purchase_order.supplier_code = '$supplier_code' ";
            }

        }else{
            $str_code = "AND tb_purchase_order.purchase_order_code = '$purchase_order_code' ";
        }

        $sql = " SELECT *
        FROM tb_purchase_order_list 
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code 
        LEFT JOIN tb_supplier ON  tb_purchase_order.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_product ON tb_purchase_order_list.product_code = tb_product.product_code 
        WHERE  purchase_order_code LIKE '%$keyword%' 
        $str_code  
        $str_date 
        $str_supplier 
        ORDER BY purchase_order_code , purchase_order_list_code DESC 
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

    function getPurchaseOrderByKeyword($keyword = ""){
        $sql = " SELECT *
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_code = tb1.user_code 
        LEFT JOIN tb_user as tb3 ON tb.purchase_approve_by = tb3.user_code 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_code = tb2.supplier_code 
        WHERE  purchase_order_code LIKE ('%$keyword%')   
        ORDER BY purchase_order_code DESC 
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

    function getPurchaseOrderByCode($code){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code 
        LEFT JOIN tb_user ON tb_purchase_order.employee_code = tb_user.user_code 
        LEFT JOIN tb_user_position ON tb_user.user_position_code = tb_user_position.user_position_code 
        WHERE purchase_order_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function generatePurchaseOrderListBySupplierCode($supplier_code, $data_pr = [], $search = ""){
        $str_pr="'".$data_pr."'";
        if(is_array($data_pr) && count($data_pr) > 0){ 
            $str_pr ="";
            for($i=0; $i<count($data_pr); $i++){
                $str_pr .= "'".$data_pr[$i]."'";
                if($i + 1 < count($data_pr)){
                    $str_pr .= ",";
                }
            }
        }

        $sql = "SELECT purchase_request_list_code, 
        tb_purchase_request_list.product_code, 
        product_name, 
        stock_group_code, 
        purchase_request_list_qty, 
        IFNULL(product_buyprice,0) as purchase_order_list_price, 
        CONCAT('PR : ',tb_purchase_request.purchase_request_code) as purchase_order_list_remark 
        FROM tb_purchase_request 
        LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_code = tb_purchase_request_list.purchase_request_code 
        LEFT JOIN tb_product ON tb_purchase_request_list.product_code = tb_product.product_code 
        LEFT JOIN tb_product_supplier ON (tb_purchase_request_list.product_code = tb_product_supplier.product_code AND tb_purchase_request_list.supplier_code = tb_product_supplier.supplier_code) 
        WHERE tb_purchase_request_list.supplier_code = '$supplier_code'
        AND purchase_order_list_code = '' 
        AND purchase_request_list_code NOT IN ($str_pr) 
        AND ( 
                tb_purchase_request.purchase_request_code LIKE ('%$search%') 
                OR tb_purchase_request_list.product_code LIKE ('%$search%') 
                OR product_name LIKE ('%$search%')
            )  
        AND purchase_approve_status = 'Approve' 
        GROUP BY purchase_request_list_code 
        ORDER BY purchase_request_list_code ASC
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

    function cancelPurchaseOrderByCode($code){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '1', 
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
        employee_code = '".$data['employee_code']."', 
        purchase_credit_term = '".$data['purchase_credit_term']."', 
        purchase_delivery_by = '".$data['purchase_delivery_by']."',  
        purchase_order_date = '".$data['purchase_order_date']."', 
        purchase_order_status = '".$data['purchase_order_status']."', 
        purchase_order_remark = '".static::$db->real_escape_string($data['purchase_order_remark'])."',  
        purchase_order_total_price = '".$data['purchase_order_total_price']."', 
        purchase_order_vat = '".$data['purchase_order_vat']."', 
        purchase_order_vat_price = '".$data['purchase_order_vat_price']."', 
        purchase_order_net_price = '".$data['purchase_order_net_price']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE purchase_order_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderApproveByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        purchase_approve_status = '".$data['purchase_approve_status']."', 
        purchase_approve_by = '".$data['purchase_approve_by']."', 
        purchase_approve_date = NOW(), 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderRequestByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        purchase_approve_status = '".$data['purchase_approve_status']."', 
        purchase_approve_by = '".$data['purchase_approve_by']."', 
        purchase_approve_date = '', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderStatusByCode($code,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        updateby = '".$data['updateby']."',
        lastupdate = NOW() 
        WHERE purchase_order_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function getSupplierOrder(){
        $sql = "SELECT supplier_code, supplier_name_en , supplier_name_th 
            FROM tb_supplier 
            WHERE supplier_code IN ( 
                SELECT DISTINCT supplier_code 
                FROM tb_purchase_request_list 
                LEFT JOIN tb_purchase_request ON tb_purchase_request_list.purchase_request_code = tb_purchase_request.purchase_request_code                   
                WHERE purchase_order_list_code = '' AND request_cancelled = 0 AND purchase_approve_status = 'Approve' 
            )
            GROUP BY supplier_code 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
        }
        return $data;
    }

    function insertPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_purchase_order ( 
            purchase_order_code,
            supplier_code,
            employee_code,
            purchase_order_date,
            purchase_order_status,
            purchase_order_remark, 
            purchase_order_total_price,
            purchase_order_vat,
            purchase_order_vat_type,
            purchase_order_vat_price,
            purchase_order_net_price,
            purchase_delivery_by, 
            purchase_credit_term,
            purchase_approve_status,
            purchase_approve_by,
            purchase_approve_date,
            purchase_revise_code,
            purchase_revise_no,
            addby,
            adddate) 
        VALUES ('". 
            $data['purchase_order_code']."','".
            $data['supplier_code']."','".
            $data['employee_code']."','".
            $data['purchase_order_date']."','".
            "New','".
            static::$db->real_escape_string($data['purchase_order_remark'])."','". 
            $data['purchase_order_total_price']."','".
            $data['purchase_order_vat']."','".
            $data['purchase_order_vat_type']."','".
            $data['purchase_order_vat_price']."','".
            $data['purchase_order_net_price']."','".
            $data['purchase_delivery_by']."','". 
            $data['purchase_credit_term']."','".
            $data['purchase_approve_status']."','".
            $data['purchase_approve_by']."','".
            $data['purchase_approve_date']."','".
            $data['purchase_revise_code']."','".
            $data['purchase_revise_no']."','".
            $data['addby']."',".
            "NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deletePurchaseOrderByCode($code){
        $sql = " UPDATE tb_purchase_request_list SET 
        purchase_order_list_code = '' 
        WHERE purchase_order_list_code (
            SELECT purchase_order_list_code 
            FROM tb_purchase_order_list 
            WHERE purchase_order_code = '$code'
            ) 
        ";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function getPurchaseOrderCodeByInvoiceSupplierCode($code){
        $sql = "SELECT GROUP_CONCAT( DISTINCT tb_purchase_order.purchase_order_code) As purchase_order_code 
        FROM tb_invoice_supplier_list
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code
        WHERE invoice_supplier_code = '$code'
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }
}
?>