<?php
require_once("BaseModel.php");  
require_once("StockModel.php"); 

class InvoiceSupplierModel extends BaseModel{ 

    private $stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->stock =  new StockModel;
    }

    function getInvoiceSupplierLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_invoice_supplier 
        WHERE invoice_supplier_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getInvoiceSupplierBy($date_start = "",$date_end = "",$supplier_code = "",$keyword = "",$user_code = "",$begin = '0' ){
        $str_supplier = "";
        $str_date = "";
        $str_user = ""; 

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_code != ""){
            $str_user = "AND employee_id = '$user_code' ";
        }

        if($supplier_code != ""){
            $str_supplier = "AND tb2.supplier_code = '$supplier_code' ";
        }

        $sql = "SELECT invoice_supplier_code,  
        invoice_supplier_code_receive,
        invoice_supplier_date, 
        invoice_supplier_date_recieve,  
        invoice_supplier_total_price, 
        invoice_supplier_vat_price, 
        invoice_supplier_net_price,   
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        invoice_supplier_due, 
        invoice_supplier_name,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name  
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user as tb1 ON tb_invoice_supplier.user_code = tb1.user_code 
        LEFT JOIN tb_supplier as tb2 ON tb_invoice_supplier.supplier_code = tb2.supplier_code  
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_supplier_code LIKE ('%$keyword%')  
        ) 
        AND invoice_supplier_begin = '$begin'  
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY  invoice_supplier_code ASC 
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

    function getInvoiceSupplierByPO($purchase_order_code,$date_start = "",$date_end = "",$supplier_code = "",$keyword = "",$user_code = "",$begin = '0'){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";   

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_code != ""){
            $str_user = "AND employee_id = '$user_code' ";
        }

        if($supplier_code != ""){
            $str_supplier = "AND tb2.supplier_code = '$supplier_code' ";
        } 

        $sql = "SELECT tb_invoice_supplier.invoice_supplier_code,  
        invoice_supplier_code_receive,
        invoice_supplier_date, 
        invoice_supplier_date_recieve,  
        invoice_supplier_total_price, 
        invoice_supplier_vat_price, 
        invoice_supplier_net_price,   
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        invoice_supplier_due, 
        invoice_supplier_name,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name  
        FROM tb_invoice_supplier 
        INNER JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_code = tb_invoice_supplier_list.invoice_supplier_code
        INNER JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code
        LEFT JOIN tb_user as tb1 ON tb_invoice_supplier.user_code = tb1.user_code 
        LEFT JOIN tb_supplier as tb2 ON tb_invoice_supplier.supplier_code = tb2.supplier_code  
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  tb_invoice_supplier.invoice_supplier_code LIKE ('%$keyword%')  
        ) 
        AND tb_purchase_order_list.purchase_order_code = '$purchase_order_code' 
        AND invoice_supplier_begin = '$begin'  
        $str_supplier 
        $str_date 
        $str_user  
        GROUP BY tb_invoice_supplier.invoice_supplier_code 
        ORDER BY  tb_invoice_supplier.invoice_supplier_code ASC 
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

    function getInvoiceSupplierByCode($id){
        $sql = "SELECT tb_invoice_supplier.* ,tb_user.*
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user ON tb_invoice_supplier.user_code = tb_user.user_code 
        WHERE invoice_supplier_code = '$id' 
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

    function getInvoiceSupplierByCode($invoice_supplier_code){
        $sql = "SELECT * 
        FROM tb_invoice_supplier  
        WHERE invoice_supplier_code = '$invoice_supplier_code' 
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

    function getInvoiceSupplierViewByCode($code){
        $sql = " SELECT *   
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user ON tb_invoice_supplier.user_code = tb_user.user_code 
        LEFT JOIN tb_user_position ON tb_user.user_position_code = tb_user_position.user_position_code 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_code = tb_supplier.supplier_code          
        WHERE invoice_supplier_code = '$code' 
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

    function getPurchaseOrder($type = "ภายในประเทศ",$keyword = ""){
        $sql = "SELECT tb_purchase_order.purchase_order_code , purchase_order_code, tb_purchase_order.supplier_code, supplier_name_en, supplier_name_th 
                FROM tb_purchase_order 
                LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code
                LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_code = tb_purchase_order_list.purchase_order_code
                WHERE purchase_order_list_code IN ( 
                    SELECT tb_purchase_order_list.purchase_order_list_code 
                    FROM tb_purchase_order_list  
                    LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code 
                    GROUP BY tb_purchase_order_list.purchase_order_list_code 
                    HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                ) 
                AND purchase_order_status = 'Confirm' 
                AND supplier_domestic = '$type' 
                AND purchase_order_code LIKE('%$keyword%') 
                GROUP BY tb_purchase_order.purchase_order_code 
        ";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
        }
        return $data;
    }

    function checkPurchaseOrder($purchase_order_code = ""){

        $sql = "SELECT COUNT(*) AS recieve_status
                FROM tb_purchase_order 
                LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code
                LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_code = tb_purchase_order_list.purchase_order_code
                WHERE purchase_order_list_code IN ( 
                    SELECT tb_purchase_order_list.purchase_order_list_code 
                    FROM tb_purchase_order_list  
                    LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code 
                    GROUP BY tb_purchase_order_list.purchase_order_list_code 
                    HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                ) 
                AND tb_purchase_order.purchase_order_code = '$purchase_order_code' 
                GROUP BY tb_purchase_order.purchase_order_code 
        ";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
        }
        return $row['recieve_status'];
    }

    function getPurchaseOrderByCode($type = "ภายในประเทศ",$keyword = ""){
        $sql = "SELECT tb_purchase_order.purchase_order_code , purchase_order_code, tb_purchase_order.supplier_code, supplier_name_en, supplier_name_th 
                FROM tb_purchase_order 
                LEFT JOIN tb_supplier ON tb_purchase_order.supplier_code = tb_supplier.supplier_code
                LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_code = tb_purchase_order_list.purchase_order_code
                WHERE purchase_order_list_code IN ( 
                    SELECT tb_purchase_order_list.purchase_order_list_code 
                    FROM tb_purchase_order_list  
                    LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code 
                    GROUP BY tb_purchase_order_list.purchase_order_list_code 
                    HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                ) 
                AND purchase_order_status = 'Confirm' 
                AND supplier_domestic = '$type' 
                AND purchase_order_code = '$keyword' 
                GROUP BY tb_purchase_order.purchase_order_code 
        ";

        $data;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
        }
        return $data;
    }

    function getSupplierOrder($type = "ภายในประเทศ"){
        $sql = "SELECT tb_supplier.supplier_code, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_code IN ( 
                    SELECT DISTINCT supplier_code 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_code = tb_purchase_order_list.purchase_order_code
                    WHERE purchase_order_list_code IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_code 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code 
                        GROUP BY tb_purchase_order_list.purchase_order_list_code 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm'
                ) 
                AND supplier_domestic = '$type' 
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
        }
        return $data;
    }

    function getInvoiceSupplierLastCode($id,$digit){
        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_code_gen,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_supplier_lastcode 
        FROM tb_invoice_supplier
        WHERE invoice_supplier_code_gen LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_supplier_lastcode'];
        }
    }

    function generateInvoiceSupplierListBySupplierId($supplier_code, $data = [], $search = "", $purchase_order_code){
        $str ="'0'";

        if(is_array($data)){  
            for($i=0; $i < count($data) ;$i++){
                if($i==0){
                    $str ="";
                }
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

        $sql_customer = "SELECT tb2.material_code, 
        tb2.purchase_order_list_code,    
        material_name,  
        IFNULL(purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_supplier_list_qty) 
            FROM tb_invoice_supplier_list 
            WHERE purchase_order_list_code = tb2.purchase_order_list_code 
        ),0) ,0) as invoice_supplier_list_qty, 
        purchase_order_list_price,
        purchase_order_list_price_sum,
        purchase_order_list_price as invoice_supplier_list_price, 
        '0' as invoice_supplier_list_total,
        '0' as invoice_supplier_list_cost, 
        CONCAT('PO : ',tb_purchase_order.purchase_order_code) as invoice_supplier_list_remark 
        FROM tb_purchase_order 
        LEFT JOIN tb_purchase_order_list as tb2 ON tb_purchase_order.purchase_order_code = tb2.purchase_order_code  
        LEFT JOIN tb_material ON tb2.material_code = tb_material.material_code  
        WHERE tb_purchase_order.supplier_code = '$supplier_code' 
        AND tb_purchase_order.purchase_order_code = '$purchase_order_code' 
        AND tb2.purchase_order_list_code NOT IN ($str) 
        AND tb2.purchase_order_list_code IN ( 
            SELECT tb_purchase_order_list.purchase_order_list_code 
            FROM tb_purchase_order_list  
            LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_code = tb_invoice_supplier_list.purchase_order_list_code 
            GROUP BY tb_purchase_order_list.purchase_order_list_code 
            HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
        ) 
        AND CONCAT(tb2.material_code,material_name) LIKE ('%$search%')  
        ORDER BY tb_purchase_order.purchase_order_code , purchase_order_list_no  ";  
 
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    function updateInvoiceSupplierByCode($code,$data = []){
        $sql = " UPDATE tb_invoice_supplier SET  
        supplier_code = '".$data['supplier_code']."',  
        invoice_supplier_code_receive = '".static::$db->real_escape_string($data['invoice_supplier_code_receive'])."', 
        invoice_supplier_total_price = '".$data['invoice_supplier_total_price']."', 
        invoice_supplier_vat = '".$data['invoice_supplier_vat']."', 
        invoice_supplier_vat_price = '".$data['invoice_supplier_vat_price']."', 
        invoice_supplier_net_price = '".$data['invoice_supplier_net_price']."', 
        invoice_supplier_date = '".static::$db->real_escape_string($data['invoice_supplier_date'])."', 
        invoice_supplier_date_recieve = '".static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."', 
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."',  
        invoice_supplier_due = '".static::$db->real_escape_string($data['invoice_supplier_due'])."',  
        invoice_supplier_due_day = '".static::$db->real_escape_string($data['invoice_supplier_due_day'])."',  
        invoice_supplier_begin = '".$data['invoice_supplier_begin']."',   
        invoice_supplier_remark = '".static::$db->real_escape_string($data['invoice_supplier_remark'])."',  
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE invoice_supplier_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertInvoiceSupplier($data = []){
        $sql = " INSERT INTO tb_invoice_supplier ( 
            invoice_supplier_code,
            supplier_code,
            user_code,
            invoice_supplier_code_receive,
            invoice_supplier_total_price,
            invoice_supplier_vat,
            invoice_supplier_vat_price,
            invoice_supplier_net_price,
            invoice_supplier_date,
            invoice_supplier_date_recieve,
            invoice_supplier_name,
            invoice_supplier_address,
            invoice_supplier_tax,
            invoice_supplier_branch, 
            invoice_supplier_due, 
            invoice_supplier_due_day, 
            invoice_supplier_begin,  
            invoice_supplier_remark, 
            branch_code,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('". 
        $data['invoice_supplier_code']."','".
        $data['supplier_code']."','".
        $data['user_code']."','".
        static::$db->real_escape_string($data['invoice_supplier_code_receive'])."','".
        $data['invoice_supplier_total_price']."','".
        $data['invoice_supplier_vat']."','".
        $data['invoice_supplier_vat_price']."','".
        $data['invoice_supplier_net_price']."','".
        static::$db->real_escape_string($data['invoice_supplier_date'])."','".
        static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."','".
        static::$db->real_escape_string($data['invoice_supplier_name'])."','".
        static::$db->real_escape_string($data['invoice_supplier_address'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tax'])."','".
        static::$db->real_escape_string($data['invoice_supplier_branch'])."','". 
        static::$db->real_escape_string($data['invoice_supplier_due'])."','".  
        static::$db->real_escape_string($data['invoice_supplier_due_day'])."','".  
        $data['invoice_supplier_begin']."','".   
        static::$db->real_escape_string($data['invoice_supplier_remark'])."','". 
        $data['branch_code']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return $data['invoice_supplier_code'];
        }else {
            return 0;
        }

    }

    function updateSupplierByInvoiceCode($id,$data = []){
        $sql = " UPDATE tb_invoice_supplier SET 
        supplier_code = '".$data['supplier_code']."',  
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."', 
        invoice_supplier_term = '".static::$db->real_escape_string($data['invoice_supplier_term'])."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_supplier_code = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function deleteInvoiceSupplierByCode($code){
        $sql = "SELECT invoice_supplier_list_code, tb_invoice_supplier_list.material_code , invoice_supplier_list_qty 
                FROM  tb_invoice_supplier_list 
                LEFT JOIN tb_material ON tb_invoice_supplier_list.material_code = tb_material.material_code  
                WHERE invoice_supplier_code = '$code' ";   
                     
         $data_clear=[];

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data_clear [] = $row;
            }
            $result->close();
        }

        for($i = 0 ; $i < count($data_clear); $i++){ 
            $this->stock->removePurchase($data_clear[$i]); 
        }
 

        $sql = " DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_supplier WHERE invoice_supplier_code = '$code' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

    function getPurchaseOrderByInvoiceSupplierId($invoice_supplier_code){

        $sql = "SELECT tb_purchase_order.purchase_order_code
                FROM  tb_invoice_supplier_list
                LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code    
                LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code
                WHERE invoice_supplier_code  = '$invoice_supplier_code' 
                GROUP BY purchase_order_code 
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
}
?>