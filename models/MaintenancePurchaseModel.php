<?php
require_once("BaseModel.php");

class MaintenancePurchaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }
 
    function runMaintenance(){
        $sql = "TRUNCATE TABLE tb_journal_purchase ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "TRUNCATE TABLE tb_journal_purchase_list ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        //ดึงหัวเอกสารการรับสินค้าเข้า
        $sql = "SELECT * 
            FROM tb_invoice_supplier 
            LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_code = tb_supplier.supplier_code 
            WHERE invoice_supplier_begin = '0' 
            ORDER BY STR_TO_DATE(receive_date,'%d-%m-%Y %H:%i:%s') , invoice_supplier_code_gen 
        ";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 

            for($i = 0 ; $i < count($data) ; $i++){
                // ดึงรายการรับสินค้าในเอกสารนั้น -----------------------------------------------------------------
                $sql = "SELECT *
                FROM tb_invoice_supplier_list
                LEFT JOIN tb_product ON tb_invoice_supplier_list.product_code = tb_product.product_code 
                WHERE invoice_supplier_code = '".$data[$i]['invoice_supplier_code']."' 
                GROUP BY invoice_supplier_list_code
                ORDER BY list_no
                ";

                $data_sub = []; 
                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }
               
                $journal_list = [];
               //คำนวนต้นทุนในกรณีเป็นบริษัทภายในประเทศ ----------------------------------------------------------
                if( $data[$i]['supplier_domestic'] == "ภายในประเทศ"){
                    $total = 0;
                    $vat_price = 0;
                    $net_price = 0;

                    //วนรอบอัพเดทรายการสินค้า ---------------------------------
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){
                        $data_sub[$i_sub]['list_price'] = round($data_sub[$i_sub]['list_price'],2);
                        $data_sub[$i_sub]['list_cost'] = $data_sub[$i_sub]['list_price'];
                        $data_sub[$i_sub]['list_total'] = round($data_sub[$i_sub]['list_qty'] * $data_sub[$i_sub]['list_price'],2);
                        $total += $data_sub[$i_sub]['list_total'];

                        $sql = " UPDATE tb_invoice_supplier_list SET 
                        product_code = '".$data_sub[$i_sub]['product_code']."', 
                        stock_group_code = '".$data_sub[$i_sub]['stock_group_code']."', 
                        list_product_name = '".$data_sub[$i_sub]['list_product_name']."',  
                        list_product_detail = '".$data_sub[$i_sub]['list_product_detail']."', 
                        list_qty = '".$data_sub[$i_sub]['list_qty']."', 
                        list_price = '".$data_sub[$i_sub]['list_price']."', 
                        list_total = '".$data_sub[$i_sub]['list_total']."', 
                        list_remark = '".$data_sub[$i_sub]['list_remark']."', 
                        list_cost = '".$data_sub[$i_sub]['list_cost']."', 
                        purchase_order_list_code = '".$data_sub[$i_sub]['purchase_order_list_code']."' 
                        WHERE invoice_supplier_list_code = '".$data_sub[$i_sub]['invoice_supplier_list_code']."' 
                        "; 

                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_code'] == $data_sub[$i_sub]['buy_account_code']){
                                $has_account = true;
                                $journal_list[$ii]['list_total'] += $data_sub[$i_sub]['list_total'];
                                break;
                            }
                        }

                        if($has_account == false){
                            $journal_list[] = array (
                                "account_code"=>$data_sub[$i_sub]['buy_account_code'], 
                                "list_total"=>$data_sub[$i_sub]['list_total'] 
                            ); 
                        } 
                    }
                
                    //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                    $vat_price = $total * $data[$i]['vat']/100;
                    $net_price = $total + $vat_price;

                    $data[$i]['cost_total'] = round($total,2);
                    $data[$i]['total_price'] = round($total,2);
                    $data[$i]['vat_price'] = round($vat_price,2);
                    $data[$i]['net_price'] = round($net_price,2);

                    $sql = "UPDATE tb_invoice_supplier SET 
                    invoice_code_receive = '".static::$db->real_escape_string($data[$i]['invoice_code_receive'])."', 
                    supplier_code = '".$data[$i]['supplier_code']."', 
                    employee_code = '".$data[$i]['employee_code']."', 
                    total_price = '".$data[$i]['total_price']."', 
                    cost_total = '".$data[$i]['cost_total']."', 
                    vat = '".$data[$i]['vat']."', 
                    vat_price = '".$data[$i]['vat_price']."', 
                    net_price = '".$data[$i]['net_price']."', 
                    craete_date = '".static::$db->real_escape_string($data[$i]['craete_date'])."', 
                    receive_date = '".static::$db->real_escape_string($data[$i]['receive_date'])."', 
                    supplier_name = '".static::$db->real_escape_string($data[$i]['supplier_name'])."', 
                    supplier_address = '".static::$db->real_escape_string($data[$i]['supplier_address'])."', 
                    supplier_tax = '".static::$db->real_escape_string($data[$i]['supplier_tax'])."', 
                    supplier_branch = '".static::$db->real_escape_string($data[$i]['supplier_branch'])."', 
                    supplier_term = '".static::$db->real_escape_string($data[$i]['supplier_term'])."', 
                    due_date = '".static::$db->real_escape_string($data[$i]['due_date'])."',  
                    due_day = '".static::$db->real_escape_string($data[$i]['due_day'])."',  
                    invoice_supplier_begin = '".$data[$i]['invoice_supplier_begin']."', 
                    freight = '".$data[$i]['freight']."', 
                    vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                    vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                    total_price_non = '".$data[$i]['total_price_non']."', 
                    vat_price_non = '".$data[$i]['vat_price_non']."', 
                    invoice_supplier_total_non = '".$data[$i]['invoice_supplier_total_non']."', 
                    description = '".static::$db->real_escape_string($data[$i]['description'])."', 
                    remark = '".static::$db->real_escape_string($data[$i]['remark'])."', 
                    updateby = '".$data[$i]['updateby']."', 
                    lastupdate = NOW()
                    WHERE invoice_supplier_code = '".$data[$i]['invoice_supplier_code']."' 
                    ";
            
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //account setting id = 9 ภาษีซื้อ  --> [1154-00] ภาษีซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_code = tb_account.account_code  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_code = tb_account_group.account_group_code  
                    WHERE tb_account_setting.account_setting_code = '9' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_vat_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_vat_purchase  = $row;
                        }
                        $result->close();
                    } 
                        
                    //account setting id = 26 ซื้อสินค้า --> [5130-01] ซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_code = tb_account.account_code  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_code = tb_account_group.account_group_code  
                    WHERE tb_account_setting.account_setting_code = '26' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_purchase  = $row;
                        }
                        $result->close();
                    }  
                    $account_supplier = $data[$i]['account_code'];

                    $this->updateJournal($data[$i],$journal_list, $account_supplier, $account_vat_purchase['account_code'],$account_purchase['account_code']);

                }
            }
        } 
    } 

    function updateJournal($data,$journal_list, $account_supplier, $account_vat_purchase,$account_purchase){

        //----------------------------- สร้างสมุดรายวันซื้อ ----------------------------------------  
        $journal_purchase_name = "ซื้อเชื่อจาก ".$data['supplier_name']." [".$data['invoice_supplier_code']."] "; 

        $sql = " SELECT * 
        FROM tb_journal_purchase 
        WHERE invoice_supplier_code = '".$data['invoice_supplier_code']."' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
        }

        if($journal['journal_purchase_code'] != ""){
            $journal_purchase_code = $journal['journal_purchase_code'];

            $sql = " UPDATE tb_journal_purchase SET 
            journal_purchase_code = '".$data['invoice_supplier_code']."', 
            journal_purchase_date = '".$data['receive_date']."', 
            journal_purchase_name = '".$journal_purchase_name."', 
            updateby = '".$data['updateby']."', 
            lastupdate = NOW() 
            WHERE journal_purchase_code = '".$journal_purchase_code."' 
            ";
    
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            $sql = " DELETE FROM tb_journal_purchase_list WHERE journal_purchase_code = '$journal_purchase_code' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        }else{
            $sql = " INSERT INTO tb_journal_purchase (
                invoice_supplier_code,
                journal_purchase_code, 
                journal_purchase_date,
                journal_purchase_name,
                addby,
                adddate) 
            VALUES ('".
                $data['invoice_supplier_code']."','".
                $data['invoice_supplier_code_gen']."','".
                $data['receive_date']."','".
                $journal_purchase_name."','".
                $data['addby']."',".
                "NOW()
            )";
        
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $journal_purchase_code = mysqli_insert_code(static::$db);
            }
        }
        //----------------------------- สิ้นสุด สร้างสมุดรายวันซื้อ ----------------------------------------

        if($journal_purchase_code != ""){ 
            //---------------------------- เพิ่มรายการเจ้าหนี้ --------------------------------------------
            $journal_purchase_list_debit = 0;
            $journal_purchase_list_credit = 0;

            if((float)filter_var( $data['net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                $journal_purchase_list_debit = (float)filter_var( $data['net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_purchase_list_credit = 0;
            }else{
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = (float)filter_var( $data['net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } 

            $sql = " INSERT INTO tb_journal_purchase_list (
                journal_purchase_code,
                account_code,
                journal_purchase_list_name,
                journal_purchase_list_debit,
                journal_purchase_list_credit,
                addby,
                adddate
            ) VALUES (
                '".$journal_purchase_code."',  
                '".$account_supplier."', 
                '".$journal_purchase_name."', 
                '".$journal_purchase_list_debit."',
                '".$journal_purchase_list_credit."',
                '".$data['addby']."', 
                NOW()
            )";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            
            //---------------------------- สิ้นสุด เพิ่มรายการเจ้าหนี้ --------------------------------------------
            

            //---------------------------- เพิ่มรายการซื้อเชื่อ --------------------------------------------
            for($i = 0; $i < count($journal_list) ; $i++){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;
                
                if($journal_list[$i]['account_code'] == 0){
                    $account_code = $account_purchase;
                }else{
                    $account_code = $journal_list[$i]['account_code'];
                }
            
                if((float)filter_var( $journal_list[$i]['list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $journal_purchase_list_debit = round((float)filter_var( $journal_list[$i]['list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                    $journal_purchase_list_credit = 0;
                }else{
                    $journal_purchase_list_debit = 0;
                    $journal_purchase_list_credit = round((float)filter_var( $journal_list[$i]['list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                } 

                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_code,
                    account_code,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate
                ) VALUES (
                    '".$journal_purchase_code."',  
                    '".$account_code."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW()
                )";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการซื้อเชื่อ --------------------------------------------


            //---------------------------- เพิ่มรายการภาษีซื้อ --------------------------------------------
            if((float)filter_var( $data['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;

                if((float)filter_var( $data['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                    $journal_purchase_list_credit = (float)filter_var( $data['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }else{
                    $journal_purchase_list_debit = (float)filter_var( $data['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }

                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_code,
                    journal_invoice_supplier_code,
                    account_code,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate
                ) VALUES (
                    '".$journal_purchase_code."',  
                    '". $data['invoice_supplier_code']."', 
                    '".$account_vat_purchase."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW()
                )";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการภาษีซื้อ --------------------------------------------
        }
    }
}
?>