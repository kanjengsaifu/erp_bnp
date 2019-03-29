<?php
require_once("BaseModel.php");

class JournalPurchaseListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalPurchaseListBy($code){
        $sql = " SELECT 
        account_code,
        journal_purchase_list_code, 
        journal_purchase_list_no, 
        journal_purchase_list_name,
        journal_purchase_list_debit,
        journal_purchase_list_credit, 
        journal_cheque_code,
        journal_cheque_pay_code,
        journal_invoice_customer_code,
        journal_invoice_supplier_code,
        tb_journal_purchase_list.account_code, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_purchase_list LEFT JOIN tb_account ON tb_journal_purchase_list.account_code = tb_account.account_code 
        WHERE journal_purchase_code = '$code' 
        ORDER BY journal_purchase_list_no 
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

    function getJournalPurchaseListByAccountId($journal_purchase_code,$account_code){
        $sql = " SELECT *
        FROM tb_journal_purchase_list  
        WHERE journal_purchase_code = '$journal_purchase_code' AND account_code = '$account_code' 
        ORDER BY journal_purchase_list_no 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    } 

    function insertJournalPurchaseList($data = []){
        $sql = " INSERT INTO tb_journal_purchase_list (
            journal_purchase_code, 
            journal_purchase_list_no, 
            journal_cheque_code,
            journal_cheque_pay_code,
            journal_invoice_customer_code,
            journal_invoice_supplier_code,
            account_code,
            journal_purchase_list_name,
            journal_purchase_list_debit,
            journal_purchase_list_credit,
            addby,
            adddate
        ) VALUES (
            '".$data['journal_purchase_code']."',   
            '".$data['journal_purchase_list_no']."',   
            '".$data['journal_cheque_code']."', 
            '".$data['journal_cheque_pay_code']."', 
            '".$data['journal_invoice_customer_code']."', 
            '".$data['journal_invoice_supplier_code']."', 
            '".$data['account_code']."', 
            '".static::$db->real_escape_string($data['journal_purchase_list_name'])."', 
            '".$data['journal_purchase_list_debit']."',
            '".$data['journal_purchase_list_credit']."',
            '".$data['addby']."', 
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updateJournalPurchaseListById($data,$code){
        $sql = " UPDATE tb_journal_purchase_list 
            SET account_code = '".$data['account_code']."',  
            journal_purchase_list_no = '".$data['journal_purchase_list_no']."',
            journal_cheque_code = '".$data['journal_cheque_code']."',
            journal_cheque_pay_code = '".$data['journal_cheque_pay_code']."',
            journal_invoice_customer_code = '".$data['journal_invoice_customer_code']."',
            journal_invoice_supplier_code = '".$data['journal_invoice_supplier_code']."',
            journal_purchase_list_name = '".static::$db->real_escape_string($data['journal_purchase_list_name'])."',
            journal_purchase_list_debit = '".$data['journal_purchase_list_debit']."',
            journal_purchase_list_credit = '".$data['journal_purchase_list_credit']."' 
            WHERE journal_purchase_list_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteJournalPurchaseListByCode($code){
        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_list_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalPurchaseListByJournalPurchaseCode($code){
        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalPurchaseListByJournalPurchaseCodeNotIN($code,$data){
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

        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_code = '$code' AND journal_purchase_list_code NOT IN ($str) ";
 
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>