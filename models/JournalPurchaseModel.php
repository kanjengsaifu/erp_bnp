<?php
require_once("BaseModel.php");

class JournalPurchaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalPurchaseLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_purchase_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_journal_purchase 
        WHERE journal_purchase_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getJournalPurchaseBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){
        $str_date = "";

        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0') ";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql = " SELECT tb_journal_purchase.journal_purchase_code,  
        journal_purchase_code, 
        journal_purchase_date,
        journal_purchase_name,
        tb_invoice_supplier.invoice_supplier_code, 
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase  
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_code = tb_journal_purchase.journal_purchase_code
        LEFT JOIN tb_invoice_supplier ON tb_journal_purchase.invoice_supplier_code = tb_invoice_supplier.invoice_supplier_code 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_purchase.journal_purchase_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY tb_journal_purchase.journal_purchase_code 
        ORDER BY journal_purchase_code $sort 
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

    function getJournalPurchaseByInvoiceSupplierCode($invoice_supplier_code){
        $sql = " SELECT * 
        FROM tb_journal_purchase 
        WHERE invoice_supplier_code = '$invoice_supplier_code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getJournalPurchaseByCode($code){
        $sql = " SELECT * 
        FROM tb_journal_purchase 
        WHERE journal_purchase_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function getJournalPurchaseByKeyword(){
        $sql = " SELECT journal_purchase_code, journal_purchase_name 
        FROM tb_journal_purchase  
        WHERE journal_purchase_code LIKE ('%$keyword%')  OR  journal_purchase_name LIKE ('%$keyword%') 
        ORDER BY journal_purchase_code DESC 
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

    function getJournalPurchaseViewByCode($code){
        $sql = " SELECT journal_purchase_code, 
        journal_purchase_date,
        journal_purchase_name,  
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_purchase 
        LEFT JOIN tb_user as tb1 ON tb_journal_purchase.addby = tb1.user_code 
        LEFT JOIN tb_user as tb2 ON tb_journal_purchase.updateby = tb2.user_code 
        WHERE journal_purchase_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function updateJournalPurchaseByCode($code,$data = []){
        $sql = " UPDATE tb_journal_purchase SET 
        journal_purchase_code = '".$data['journal_purchase_code']."', 
        journal_purchase_date = '".$data['journal_purchase_date']."', 
        journal_purchase_name = '".static::$db->real_escape_string($data['journal_purchase_name'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_purchase_code = $code 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertJournalPurchase($data = []){
        $sql = " INSERT INTO tb_journal_purchase (
            invoice_supplier_code,
            journal_purchase_code, 
            journal_purchase_date,
            journal_purchase_name,
            addby,
            adddate) 
        VALUES ('".
        $data['invoice_supplier_code']."','".
        $data['journal_purchase_code']."','".
        $data['journal_purchase_date']."','".
        static::$db->real_escape_string($data['journal_purchase_name'])."','".
        $data['addby']."',".
        "NOW()
        }";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteJournalPurchaseByCode($code){
        $sql = " DELETE FROM tb_journal_purchase WHERE journal_purchase_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_purchase_list WHERE journal_purchase_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    

    function deleteJournalPurchaseByInvoiceSupplierCode($invoice_supplier_code){
        $sql = "DELETE FROM tb_journal_purchase_list 
        WHERE journal_purchase_code IN (
            SELECT journal_purchase_code 
            FROM tb_journal_purchase 
            WHERE invoice_supplier_code = '$invoice_supplier_code'
        )";
        
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "DELETE FROM tb_journal_purchase WHERE  invoice_supplier_code = '$invoice_supplier_code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>