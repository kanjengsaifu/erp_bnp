<?php
require_once('../models/InvoiceSupplierModel.php');
require_once('../models/InvoiceSupplierListModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/JournalPurchaseModel.php');
require_once('../models/JournalPurchaseListModel.php');
require_once('../models/AccountSettingModel.php');

require_once('../functions/CodeGenerateFunction.func.php');
require_once('../models/MaintenancePurchaseModel.php');
require_once('../models/MaintenanceStockModel.php');

$path = "modules/invoice_supplier/views/";

$user_model = new UserModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_supplier_list_model = new InvoiceSupplierListModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$product_model = new ProductModel;
$stock_group_model = new StockGroupModel;
$journal_purchase_model = new JournalPurchaseModel;
$journal_purchase_list_model = new JournalPurchaseListModel;
$account_setting_model = new AccountSettingModel;

$code_generate = new CodeGenerate;
$maintenance_model = new MaintenancePurchaseModel;
$maintenance_stock_model = new MaintenanceStockModel;

$invoice_supplier_code = $_GET['code'];
$notification_code = $_GET['notification'];
$supplier_code = $_GET['supplier_code'];
$purchase_order_code = $_GET['purchase_order_code'];
$vat = 7;
$first_char = "RR";
$stock_group_code = 0;

if($license_purchase_page == "Medium" || $license_purchase_page == "High"){
    $lock_1 = "1";
}else{
    $lock_1 = "0";
}

if($license_account_page == "Medium" || $license_account_page == "High"){
    $lock_2 = "1";
}else{
    $lock_2 = "0";
}

if ($_GET['action'] == 'import-view' && $menu['invoice_supplier']['add']){
    $stock_groups = $stock_group_model->getStockGroupBy();
    $suppliers = $supplier_model->getSupplierBy();
    $users = $user_model->getUserBy();
    
    if($supplier_code > 0){
        $supplier = $supplier_model->getSupplierByCode($supplier_code);
        $invoice_supplier_lists = $invoice_supplier_model->generateInvoiceSupplierListBySupplierCode($supplier_code);
        $suppliers = $supplier_model->getSupplierBy();
    }
    
    $user = $user_model->getUserByCode($login_user['user_code']);
   
    $first_date = date("d")."-".date("m")."-".date("Y");
    require_once($path.'import.inc.php');
}else if ($_GET['action'] == 'insert' && $menu['invoice_supplier']['add']){
    $stock_groups = $stock_group_model->getStockGroupBy();
    $suppliers = $supplier_model->getSupplierBy();
    $condition = 'AND (permission_add OR permission_edit)';
    $users = $user_model->getUserByPermission('invoice_supplier',$condition);

    if($supplier_code != ''){
        $supplier = $supplier_model->getSupplierByCode($supplier_code);
        $invoice_supplier_lists = $invoice_supplier_model->generateInvoiceSupplierListBySupplierCode($supplier_code,"","","",$purchase_order_code,$invoice_supplier_code);
    }

    $code = "RR".date("y").date("m").date("d");
    $last_code = $invoice_supplier_model->getInvoiceSupplierLastCode($code,4);
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update' && $menu['invoice_supplier']['edit']){
    $stock_groups = $stock_group_model->getStockGroupBy();
    $users = $user_model->getUserBy();

    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByCode($invoice_supplier_code);

    $supplier = $supplier_model->getSupplierByCode($invoice_supplier['supplier_code']);
    $suppliers = $supplier_model->getSupplierBy();
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_code);
    $invoice_supplier_import_duty_lists = $invoice_supplier_import_duty_list_model->getInvoiceSupplierImportDutyListBy($invoice_supplier_code);
    $invoice_supplier_short_lists = $invoice_supplier_short_list_model->getInvoiceSupplierShortListBy($invoice_supplier_code);
    $invoice_supplier_freight_in_lists = $invoice_supplier_freight_in_list_model->getInvoiceSupplierFreightInListBy($invoice_supplier_code);

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy("","","","","","0",$lock_1,$lock_2,'ASC');

    for($i = 0 ; $i < count($invoice_suppliers) ; $i++){
        if($invoice_supplier_code == $invoice_suppliers[$i]['invoice_supplier_code']){ 
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code'];
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code_gen'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code_gen'];

        }
    }

    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'detail'){
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByCode($invoice_supplier_code);
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_code);
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy("","","","","","0",$lock_1,$lock_2,'ASC');

    for($i = 0 ; $i < count($invoice_suppliers) ; $i++){
        if($invoice_supplier_code == $invoice_suppliers[$i]['invoice_supplier_code']){ 
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code'];
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code_gen'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code_gen'];
        }
    }
    $purchase_orders = $invoice_supplier_model->getPurchaseOrderByInvoiceSupplierCode($invoice_supplier_code);
    require_once($path.'detail.inc.php');
}else if ($_GET['action'] == 'cost'){
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByCode($invoice_supplier_code);
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_code);
    $invoice_supplier_import_duty_lists = $invoice_supplier_import_duty_list_model->getInvoiceSupplierImportDutyListBy($invoice_supplier_code);
    $invoice_supplier_freight_in_lists = $invoice_supplier_freight_in_list_model->getInvoiceSupplierFreightInListBy($invoice_supplier_code);
     
    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy("","","","","","0",$lock_1,$lock_2,'ASC');

    for($i = 0 ; $i < count($invoice_suppliers) ; $i++){
        if($invoice_supplier_code == $invoice_suppliers[$i]['invoice_supplier_code']){ 
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code'];
            $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code_gen'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code'];
            $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code_gen'];
        }
    }
    require_once($path.'cost.inc.php');
}else if ($_GET['action'] == 'delete' && $menu['invoice_supplier']['delete']){
    $invoice_supplier_model->deleteInvoiceSupplierByCode($invoice_supplier_code);
    $invoice_supplier_short_list_model->deleteInvoiceSupplierShortListByInvoiceSupplierID($invoice_supplier_code);

    $journal_purchases = $journal_purchase_model->deleteJournalPurchaseByInvoiceSupplierID($invoice_supplier_code);

    ?>
        <script>window.location="index.php?app=invoice_supplier"</script>
    <?php

}else if ($_GET['action'] == 'add' && $menu['invoice_supplier']['add']){
    if(isset($_POST['invoice_supplier_code'])){

        $supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);

        $data = [];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];
        $data['total_price'] = (float)filter_var($_POST['total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['vat'] = (float)filter_var($_POST['vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['vat_price'] =(float)filter_var( $_POST['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['net_price'] = (float)filter_var($_POST['net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['craete_date'] = $_POST['craete_date'];
        $data['recieve_date'] = $_POST['recieve_date'];
        $data['supplier_name'] = $_POST['supplier_name'];
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_address'] = $_POST['supplier_address'];
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['term'] = $_POST['term'];
        $data['due_day'] = $_POST['due_day']; 
        $data['due_date'] = $_POST['due_date']; 
        $data['invoice_supplier_stock'] = $_POST['invoice_supplier_stock']; 
        $data['import_duty'] = (float)filter_var($_POST['import_duty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['freight_in'] = (float)filter_var($_POST['freight_in'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['addby'] = $login_user['user_code'];

        $invoice_supplier_code = $invoice_supplier_model->insertInvoiceSupplier($data);

        $invoice_supplier = $data;
        $invoice_supplier['invoice_supplier_code'] = $invoice_supplier_code;

        if($invoice_supplier_code > 0){ 
            $data = [];
            $product_code = $_POST['product_code'];
            $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
            $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
            $list_qty = $_POST['list_qty'];
            $invoice_supplier_list_duty = $_POST['invoice_supplier_list_duty'];
            $invoice_supplier_list_fix_type = $_POST['invoice_supplier_list_fix_type'];
            $invoice_supplier_list_import_duty = $_POST['invoice_supplier_list_import_duty'];
            $invoice_supplier_list_import_duty_total = $_POST['invoice_supplier_list_import_duty_total'];
            $invoice_supplier_list_freight_in = $_POST['invoice_supplier_list_freight_in'];
            $invoice_supplier_list_freight_in_total = $_POST['invoice_supplier_list_freight_in_total'];
            $invoice_supplier_list_currency_price = $_POST['invoice_supplier_list_currency_price'];
            $invoice_supplier_list_currency_total = $_POST['invoice_supplier_list_currency_total'];
            $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
            $invoice_supplier_list_cost_total = $_POST['invoice_supplier_list_cost_total'];
            $list_price = $_POST['list_price'];
            $list_total = $_POST['list_total'];
            $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
            $purchase_order_list_code = $_POST['purchase_order_list_code'];
            $stock_group_code = $_POST['stock_group_code'];
            
            $journal_list = [];

            if(is_array($product_code)){
                for($i=0; $i < count($product_code) ; $i++){
                    $data_sub = [];
                    $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                    $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                    $data_sub['invoice_supplier_list_no'] = $i;
                    $data_sub['product_code'] = $product_code[$i];
                    $data_sub['stock_date'] = $data['recieve_date'];
                    
                    $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                    $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                    $data_sub['list_qty'] = (float)filter_var($list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['list_price'] = (float)filter_var($list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['list_total'] = (float)filter_var($list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_duty'] = (float)filter_var($invoice_supplier_list_duty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_fix_type'] = $invoice_supplier_list_fix_type[$i];
                    $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                    $data_sub['purchase_order_list_code'] = $purchase_order_list_code[$i];

                    $data_sub['stock_group_code'] = $stock_group_code[$i];
                    $data_sub['addby'] = $login_user['user_code']; 
                    $data_sub['updateby'] = $login_user['user_code']; 

                    $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                    $product = $product_model->getProductByCode( $product_code[$i] );
                    $has_account = false;
                    for($ii = 0 ; $ii < count($journal_list); $ii++){
                        if($journal_list[$ii]['account_code'] == $product['buy_account_code']){
                            $has_account = true;
                            $journal_list[$ii]['list_total'] += $data_sub['list_total'];
                            break;
                        }
                    }

                    if($has_account == false){
                        $journal_list[] = array (
                            "account_code"=>$product['buy_account_code'], 
                            "list_total"=>$data_sub['list_total'] 
                        ); 
                    } 
                }
            }else if($product_code != ""){
                $data_sub = [];
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                $data_sub['product_code'] = $product_code;
                $data_sub['invoice_supplier_list_no'] = 0;
                $data_sub['stock_date'] = $data['recieve_date'];
                
                $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
                $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
                $data_sub['list_qty'] = (float)filter_var($list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['list_price'] = (float)filter_var($list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['list_total'] = (float)filter_var($list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_duty'] = (float)filter_var($invoice_supplier_list_duty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_fix_type'] = $invoice_supplier_list_fix_type;
                $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
                $data_sub['purchase_order_list_code'] = $purchase_order_list_code;
                $data_sub['stock_group_code'] = $stock_group_code;
                $data_sub['addby'] = $login_user['user_code']; 
                $data_sub['updateby'] = $login_user['user_code']; 
                //echo "----";
                $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);

                $product = $product_model->getProductByCode( $product_code );
                $journal_list[] = array (
                    "account_code"=>$product['buy_account_code'], 
                    "list_total"=>$data_sub['list_total'] 
                ); 
            }

            $account_vat_buy = $account_setting_model->getAccountSettingByCode(9);
            $account_buy = $account_setting_model->getAccountSettingByCode(26);
            $supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);
            $account_supplier = $supplier['account_code'];
            $maintenance_model->updateJournal($invoice_supplier,$journal_list, $account_supplier, $account_vat_buy['account_code'],$account_buy['account_code']);

            $invoice_supplier_freight_in_list_code = $_POST['invoice_supplier_freight_in_list_code'];
            $invoice_supplier_freight_in_list_name = $_POST['invoice_supplier_freight_in_list_name'];
            $invoice_supplier_freight_in_list_total = $_POST['invoice_supplier_freight_in_list_total'];
            for($i=0; $i < count($invoice_supplier_freight_in_list_code) ; $i++){
                $data_sub = [];
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
                $data_sub['invoice_supplier_freight_in_list_name'] = $invoice_supplier_freight_in_list_name[$i];
                $data_sub['invoice_supplier_freight_in_list_total'] = (float)filter_var($invoice_supplier_freight_in_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

                $code = $invoice_supplier_freight_in_list_model->insertInvoiceSupplierFreightInList($data_sub); 
            }

            $invoice_supplier_import_duty_list_code = $_POST['invoice_supplier_import_duty_list_code'];
            $invoice_supplier_import_duty_list_name = $_POST['invoice_supplier_import_duty_list_name'];
            $invoice_supplier_import_duty_list_total = $_POST['invoice_supplier_import_duty_list_total'];
            for($i=0; $i < count($invoice_supplier_import_duty_list_code) ; $i++){
                $data_sub = [];
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
                $data_sub['invoice_supplier_import_duty_list_name'] = $invoice_supplier_import_duty_list_name[$i];
                $data_sub['invoice_supplier_import_duty_list_total'] = (float)filter_var($invoice_supplier_import_duty_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

                $code = $invoice_supplier_import_duty_list_model->insertInvoiceSupplierImportDutyList($data_sub); 
            }
            $invoice_supplier_short_list_code = $_POST['invoice_supplier_short_list_code'];
            $invoice_supplier_short_list_name = $_POST['invoice_supplier_short_list_name'];
            $invoice_supplier_short_list_total_currency = $_POST['invoice_supplier_short_list_total_currency'];
            $invoice_supplier_short_list_exchange_rate = $_POST['invoice_supplier_short_list_exchange_rate'];
            $invoice_supplier_short_list_total = $_POST['invoice_supplier_short_list_total'];
            
            for($i=0; $i < count($invoice_supplier_short_list_code) ; $i++){
                $data_sub = [];
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
                $data_sub['invoice_supplier_short_list_name'] = $invoice_supplier_short_list_name[$i];
                $data_sub['invoice_supplier_short_list_total_currency'] = (float)filter_var($invoice_supplier_short_list_total_currency[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                $data_sub['invoice_supplier_short_list_exchange_rate'] = (float)filter_var($invoice_supplier_short_list_exchange_rate[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                $data_sub['invoice_supplier_short_list_total'] = (float)filter_var($invoice_supplier_short_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

                $code = $invoice_supplier_short_list_model->insertInvoiceSupplierShortList($data_sub); 
            }

            $maintenance_stock_model->runMaintenance($_POST['recieve_date']);
        ?>
            <script>
                window.location="index.php?app=invoice_supplier&action=update&code=<?php echo $invoice_supplier_code;?>";
            </script>
        <?php
        }else{
        ?>
            <script>window.history.back();</script>
        <?php
        }
    }else{
    ?>
        <script>window.history.back();</script>
    <?php
    }
}else if ($_GET['action'] == 'edit' && $menu['invoice_supplier']['edit']){
    if(isset($_POST['invoice_supplier_code'])){
        $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByCode($invoice_supplier_code);
        $data = [];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];
        $data['total_price'] = (float)filter_var($_POST['total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['cost_total'] = (float)filter_var($_POST['cost_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_currency_total'] = (float)filter_var($_POST['invoice_supplier_currency_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['vat'] = (float)filter_var($_POST['vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['vat_price'] =(float)filter_var( $_POST['vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['net_price'] = (float)filter_var($_POST['net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['craete_date'] = $_POST['craete_date'];
        $data['recieve_date'] = $_POST['recieve_date'];
        $data['supplier_name'] = $_POST['supplier_name'];
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_address'] = $_POST['supplier_address'];
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['term'] = $_POST['term'];
        $data['due_day'] = $_POST['due_day'];
        $data['due_date'] = $_POST['due_date'];
        $data['invoice_supplier_stock'] = $_POST['invoice_supplier_stock'];
        $data['import_duty'] = (float)filter_var($_POST['import_duty'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['freight_in'] = (float)filter_var($_POST['freight_in'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['updateby'] = $login_user['user_code']; 
       
        $product_code = $_POST['product_code'];
        $invoice_supplier_list_code = $_POST['invoice_supplier_list_code'];
        $old_cost = $_POST['old_cost'];
        $old_qty = $_POST['old_qty'];
        $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
        $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
        $list_qty = $_POST['list_qty'];
        $invoice_supplier_list_import_duty = $_POST['invoice_supplier_list_import_duty'];
        $invoice_supplier_list_import_duty_total = $_POST['invoice_supplier_list_import_duty_total'];
        $invoice_supplier_list_duty = $_POST['invoice_supplier_list_duty'];
        $invoice_supplier_list_fix_type = $_POST['invoice_supplier_list_fix_type'];
        $invoice_supplier_list_freight_in = $_POST['invoice_supplier_list_freight_in'];
        $invoice_supplier_list_freight_in_total = $_POST['invoice_supplier_list_freight_in_total'];
        $invoice_supplier_list_currency_price = $_POST['invoice_supplier_list_currency_price'];
        $invoice_supplier_list_currency_total = $_POST['invoice_supplier_list_currency_total'];
        $list_price = $_POST['list_price'];
        $list_total = $_POST['list_total'];
        $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
        $invoice_supplier_list_cost_total = $_POST['invoice_supplier_list_cost_total'];
        $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
        $purchase_order_list_code = $_POST['purchase_order_list_code'];
        $stock_group_code = $_POST['stock_group_code'];
        
        $invoice_supplier_list_model->deleteInvoiceSupplierListByInvoiceSupplierIDNotIN($invoice_supplier_code,$invoice_supplier_list_code);
    
        $journal_list = [];
        if(is_array($product_code)){
            for($i=0; $i < count($product_code) ; $i++){
                $data_sub = [];
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                $data_sub['invoice_supplier_list_no'] = $i;
                $data_sub['product_code'] = $product_code[$i];
                $data_sub['stock_date'] = $data['recieve_date'];
                $data_sub['old_cost'] = $old_cost[$i];
                $data_sub['old_qty'] = $old_qty[$i];
                $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                $data_sub['list_qty'] = (float)filter_var($list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['list_price'] = (float)filter_var($list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['list_total'] = (float)filter_var($list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
                $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];

                $data_sub['stock_group_code'] = $stock_group_code[$i];
                $data_sub['purchase_order_list_code'] = $purchase_order_list_code[$i];

                $data_sub['addby'] = $login_user['user_code']; 
                $data_sub['updateby'] = $login_user['user_code']; 
    
                if($invoice_supplier_list_code[$i] != '0' && $invoice_supplier_list_code[$i] != ''){
                    $invoice_supplier_list_model->updateInvoiceSupplierListByCode($data_sub,$invoice_supplier_list_code[$i]);
                }else{
                    $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                }

                $product = $product_model->getProductByCode( $product_code[$i] );
                $has_account = false;
                for($ii = 0 ; $ii < count($journal_list); $ii++){
                    if($journal_list[$ii]['account_code'] == $product['buy_account_code']){
                        $has_account = true;
                        $journal_list[$ii]['list_total'] += $data_sub['list_total'];
                        break;
                    }
                }

                if($has_account == false){
                    $journal_list[] = array (
                        "account_code"=>$product['buy_account_code'], 
                        "list_total"=>$data_sub['list_total'] 
                    ); 
                } 
            }
        }else if($product_code != ""){
            $data_sub = [];
            $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
            $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
            $data_sub['product_code'] = $product_code;
            $data_sub['stock_date'] = $data['recieve_date'];
            $data_sub['invoice_supplier_list_no'] = 0;
            $data_sub['old_cost'] = $old_cost;
            $data_sub['old_qty'] = $old_qty;
            $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
            $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
            $data_sub['list_qty'] = (float)filter_var($list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['list_price'] = (float)filter_var($list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['list_total'] = (float)filter_var($list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;

            $data_sub['stock_group_code'] = $stock_group_code;
            $data_sub['purchase_order_list_code'] = $purchase_order_list_code;
            $data_sub['addby'] = $login_user['user_code']; 
            $data_sub['updateby'] = $login_user['user_code']; 

            if($invoice_supplier_list_code != '0' && $invoice_supplier_list_code != ''){
                $invoice_supplier_list_model->updateInvoiceSupplierListByCode($data_sub,$invoice_supplier_list_code);
            }else{
                $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
            }

            $product = $product_model->getProductByCode( $product_code );
            $journal_list[] = array (
                "account_code"=>$product['buy_account_code'], 
                "list_total"=>$data_sub['list_total'] 
            ); 
        }

        $output = $invoice_supplier_model->updateInvoiceSupplierByCode($invoice_supplier_code,$data);

        $invoice_supplier = $data;
        $invoice_supplier['invoice_supplier_code'] = $invoice_supplier_code;

        $account_vat_buy = $account_setting_model->getAccountSettingByCode(9);
        $account_buy = $account_setting_model->getAccountSettingByCode(26);
        $supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);
        $account_supplier = $supplier['account_code'];
        $maintenance_model->updateJournal($invoice_supplier,$journal_list, $account_supplier, $account_vat_buy['account_code'],$account_buy['account_code']);

        $invoice_supplier_freight_in_list_code = $_POST['invoice_supplier_freight_in_list_code'];
        $invoice_supplier_freight_in_list_name = $_POST['invoice_supplier_freight_in_list_name'];
        $invoice_supplier_freight_in_list_total = $_POST['invoice_supplier_freight_in_list_total'];
        $invoice_supplier_freight_in_list_model->deleteInvoiceSupplierFreightInListByCodeNotIN($invoice_supplier_code,$invoice_supplier_freight_in_list_code);
        
        for($i=0; $i < count($invoice_supplier_freight_in_list_code) ; $i++){
            $data_sub = [];
            $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
            $data_sub['invoice_supplier_freight_in_list_name'] = $invoice_supplier_freight_in_list_name[$i];
            $data_sub['invoice_supplier_freight_in_list_total'] = (float)filter_var($invoice_supplier_freight_in_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

            if($invoice_supplier_freight_in_list_code[$i] != '0' && $invoice_supplier_freight_in_list_code[$i] != ''){
                $invoice_supplier_freight_in_list_model->updateInvoiceSupplierFreightInListByCode($data_sub,$invoice_supplier_freight_in_list_code[$i]);
            }else{
                $code = $invoice_supplier_freight_in_list_model->insertInvoiceSupplierFreightInList($data_sub);
            }
        }

        $invoice_supplier_import_duty_list_code = $_POST['invoice_supplier_import_duty_list_code'];
        $invoice_supplier_import_duty_list_name = $_POST['invoice_supplier_import_duty_list_name'];
        $invoice_supplier_import_duty_list_total = $_POST['invoice_supplier_import_duty_list_total'];
        $invoice_supplier_import_duty_list_model->deleteInvoiceSupplierImportDutyListByCodeNotIN($invoice_supplier_code,$invoice_supplier_import_duty_list_code);

        for($i=0; $i < count($invoice_supplier_import_duty_list_code) ; $i++){
            $data_sub = [];
            $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
            $data_sub['invoice_supplier_import_duty_list_name'] = $invoice_supplier_import_duty_list_name[$i];
            $data_sub['invoice_supplier_import_duty_list_total'] = (float)filter_var($invoice_supplier_import_duty_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

            //echo "****"; 
            if($invoice_supplier_import_duty_list_code[$i] != '0' && $invoice_supplier_import_duty_list_code[$i] != ''){
                $invoice_supplier_import_duty_list_model->updateInvoiceSupplierImportDutyListByCode($data_sub,$invoice_supplier_import_duty_list_code[$i]);
            }else{
                $code = $invoice_supplier_import_duty_list_model->insertInvoiceSupplierImportDutyList($data_sub);
            }
        }

        ////////////////  Short Credit  //////////////////////////
        $invoice_supplier_short_list_code = $_POST['invoice_supplier_short_list_code'];
        $invoice_supplier_short_list_name = $_POST['invoice_supplier_short_list_name'];
        $invoice_supplier_short_list_total_currency = $_POST['invoice_supplier_short_list_total_currency'];
        $invoice_supplier_short_list_exchange_rate = $_POST['invoice_supplier_short_list_exchange_rate'];
        $invoice_supplier_short_list_total = $_POST['invoice_supplier_short_list_total'];
        $invoice_supplier_short_list_model->deleteInvoiceSupplierShortListByCodeNotIN($invoice_supplier_code,$invoice_supplier_short_list_code);

        for($i=0; $i < count($invoice_supplier_short_list_code) ; $i++){
            $data_sub = [];
            $data_sub['invoice_supplier_code'] = $invoice_supplier_code; 
            $data_sub['invoice_supplier_short_list_name'] = $invoice_supplier_short_list_name[$i];
            $data_sub['invoice_supplier_short_list_total_currency'] = (float)filter_var($invoice_supplier_short_list_total_currency[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
            $data_sub['invoice_supplier_short_list_exchange_rate'] = (float)filter_var($invoice_supplier_short_list_exchange_rate[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
            $data_sub['invoice_supplier_short_list_total'] = (float)filter_var($invoice_supplier_short_list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

            //echo "****"; 
            if($invoice_supplier_short_list_code[$i] != '0' && $invoice_supplier_short_list_code[$i] != ''){
                $invoice_supplier_short_list_model->updateInvoiceSupplierShortListByCode($data_sub,$invoice_supplier_short_list_code[$i]);
            }else{
                $code = $invoice_supplier_short_list_model->insertInvoiceSupplierShortList($data_sub);
            }
        }

        if($output){
            $old_date = DateTime::createFromFormat('d-m-Y',$invoice_supplier['recieve_date']);
            $new_date = DateTime::createFromFormat('d-m-Y',$_POST['recieve_date']);

            if($old_date < $new_date){
                $maintenance_stock_model->runMaintenance($invoice_supplier['recieve_date']);
            }else{
                $maintenance_stock_model->runMaintenance($_POST['recieve_date']);
            }
        ?>
            <script> window.location="index.php?app=invoice_supplier&action=update&code=<?php echo $invoice_supplier_code;?>"; </script>
        <?php
        }else{
        ?>
            <script>swindow.history.back();</script>
        <?php
        }
    
    }else{
    ?>
        <script>window.history.back();</script>
    <?php
    }
}else if ($_GET['action'] == 'import-save' && $menu['invoice_supplier']['edit']){
    if(isset($_POST['invoice_supplier_code'])){
        $supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);

        $invoice_supplier_code = $_POST['invoice_supplier_code'];
        $invoice_supplier_code_gen = $_POST['invoice_supplier_code_gen'];
        $craete_date = $_POST['craete_date'];
        $vat = $_POST['vat'];
        $total_price = $_POST['total_price'];
        $cost_total = $_POST['cost_total'];
        $invoice_supplier_currency_total = $_POST['invoice_supplier_currency_total'];
        $vat_price = $_POST['vat_price'];
        $net_price = $_POST['net_price'];
        $invoice_supplier_list_count = $_POST['invoice_supplier_list_count'];
        
        $count_index = 0;

        for($index=0; $index<count($invoice_supplier_code); $index++){
            $data = [];
            $data['supplier_code'] = $_POST['supplier_code'];
            $data['employee_code'] = $_POST['employee_code'];
            $data['invoice_supplier_code'] = $invoice_supplier_code[$index];
            $data['invoice_supplier_code_gen'] = $invoice_supplier_code_gen[$index];
            $data['total_price'] = (float)filter_var($total_price[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['cost_total'] = (float)filter_var($cost_total[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_currency_total'] = (float)filter_var($invoice_supplier_currency_total[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['vat'] = (float)filter_var($vat[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['vat_price'] =(float)filter_var( $vat_price[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['net_price'] = (float)filter_var($net_price[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['craete_date'] = $craete_date[$index];
            $data['recieve_date'] = $_POST['recieve_date'];
            $data['supplier_name'] = $_POST['supplier_name'];
            $data['supplier_branch'] = $_POST['supplier_branch'];
            $data['supplier_address'] = $_POST['supplier_address'];
            $data['supplier_tax'] = $_POST['supplier_tax'];
            $data['term'] = $_POST['term'];
            $data['due_day'] = $_POST['due_day']; 
            $data['due_date'] = $_POST['due_date']; 
            $data['invoice_supplier_stock'] = $_POST['invoice_supplier_stock']; 
            $data['import_duty'] = 0;
            $data['freight_in'] = 0;
            $data['addby'] = $login_user['user_code'];

            $invoice_supplier_code = $invoice_supplier_model->insertInvoiceSupplier($data);

            $invoice_supplier = $data;
            $invoice_supplier['invoice_supplier_code'] = $invoice_supplier_code;

            if($invoice_supplier_code > 0){ 
            

                $data = [];

                $product_code = $_POST['product_code'];

                $product_code = $_POST['product_code'];
                $purchase_order_code = $_POST['purchase_order_code'];
                $purchase_order_list_no = $_POST['purchase_order_list_no'];

                $invoice_supplier_list_product_name = $_POST['invoice_supplier_list_product_name'];
                $invoice_supplier_list_product_detail = $_POST['invoice_supplier_list_product_detail'];
                $list_qty = $_POST['list_qty'];
                $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
                $list_price = $_POST['list_price'];
                $list_total = $_POST['list_total'];
                $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];

                $purchase_order_list_code = $_POST['purchase_order_list_code'];

                $stock_group_code = $_POST['stock_group_code'];
                
                $journal_list = [];

                if(is_array($product_code)){
                    for($i= $count_index ;  $i < count($product_code) && $i < $count_index + $invoice_supplier_list_count[$index]  ; $i++){
                        $data_sub = [];

                        $val_list = $purchase_order_list_model->getPurchaseOrderListIDByOther($purchase_order_code[$i],$purchase_order_list_no[$i]);
                        $val_product = $product_model->getProductByCode($product_code[$i]);

                        $product_code[$i] = $val_product['product_code'];

                        $purchase_order_list_code[$i] = $val_list;

                        $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                        $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("Hi").$i;
                        $data_sub['product_code'] = $product_code[$i];
                        $data_sub['stock_date'] = $data['recieve_date'];
                        $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name[$i];
                        $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail[$i];
                        $data_sub['list_qty'] = (float)filter_var($list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['list_price'] = (float)filter_var($list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['list_total'] = (float)filter_var($list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                        $data_sub['purchase_order_list_code'] = $purchase_order_list_code[$i];
                        $data_sub['stock_group_code'] = $stock_group_code[$i];
                        $data_sub['addby'] = $login_user['user_code']; 
                        $data_sub['updateby'] = $login_user['user_code']; 

                        $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);

                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_code'] == $product['buy_account_code']){
                                $has_account = true;
                                $journal_list[$ii]['list_total'] += $data_sub['list_total'];
                                break;
                            }
                        }

                        if($has_account == false){
                            $journal_list[] = array (
                                "account_code"=>$product['buy_account_code'], 
                                "list_total"=>$data_sub['list_total'] 
                            ); 
                        } 
                    }
                }else if($product_code != ""){
                    $data_sub = [];
                    $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                    $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                    $data_sub['product_code'] = $product_code;
                    $data_sub['stock_date'] = $data['recieve_date'];
                    
                    $data_sub['invoice_supplier_list_product_name'] = $invoice_supplier_list_product_name;
                    $data_sub['invoice_supplier_list_product_detail'] = $invoice_supplier_list_product_detail;
                    $data_sub['list_qty'] = (float)filter_var($list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['list_price'] = (float)filter_var($list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['list_total'] = (float)filter_var($list_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_currency_price'] = (float)filter_var($invoice_supplier_list_currency_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_currency_total'] = (float)filter_var($invoice_supplier_list_currency_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_import_duty'] = (float)filter_var($invoice_supplier_list_import_duty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_import_duty_total'] = (float)filter_var($invoice_supplier_list_import_duty_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_freight_in'] = (float)filter_var($invoice_supplier_list_freight_in, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_freight_in_total'] = (float)filter_var($invoice_supplier_list_freight_in_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    $data_sub['invoice_supplier_list_cost'] = (float)filter_var($invoice_supplier_list_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_cost_total'] = (float)filter_var($invoice_supplier_list_cost_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
                    $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
                    $data_sub['purchase_order_list_code'] = $purchase_order_list_code;
                    $data_sub['stock_group_code'] = $stock_group_code;
                    $data_sub['addby'] = $login_user['user_code']; 
                    $data_sub['updateby'] = $login_user['user_code']; 
                    //echo "----";
                    $code = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);

                    $product = $product_model->getProductByCode( $product_code );
                    $journal_list[] = array (
                        "account_code"=>$product['buy_account_code'], 
                        "list_total"=>$data_sub['list_total'] 
                    ); 
                }

                $account_vat_buy = $account_setting_model->getAccountSettingByCode(9);
                $account_buy = $account_setting_model->getAccountSettingByCode(26);
                $supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);
                $account_supplier = $supplier['account_code'];
                $maintenance_model->updateJournal($invoice_supplier,$journal_list, $account_supplier, $account_vat_buy['account_code'],$account_buy['account_code']);
                $count_index = $count_index + $invoice_supplier_list_count[$index];
            }
        } 
    ?>
        <script>
            window.location="index.php?app=invoice_supplier";
        </script>
    <?PHP
    }else{
    ?>
        <script>window.history.back();</script>
    <?php
    }
}else if ($_GET['action'] == 'edit_cost' && $menu['invoice_supplier']['edit']){
    $data = [];
    $data['total_price'] = (float)filter_var( $_POST['total_price'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['invoice_supplier_currency_total'] = (float)filter_var( $_POST['invoice_supplier_currency_total'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['cost_total'] = (float)filter_var( $_POST['cost_total'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['vat'] = (float)filter_var( $_POST['vat'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['vat_price'] = (float)filter_var( $_POST['vat_price'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['net_price'] = (float)filter_var( $_POST['net_price'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['import_duty'] = (float)filter_var( $_POST['import_duty'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['freight_in'] = (float)filter_var( $_POST['freight_in'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['updateby'] = $login_user['user_code'];
    $invoice_supplier_model->updateInvoiceSupplierCostByCode($invoice_supplier_code,$data); 
    $invoice_supplier_list_code = $_POST['invoice_supplier_list_code']; 
    $invoice_supplier_list_fix_type = $_POST['invoice_supplier_list_fix_type'];
    $invoice_supplier_list_duty_percent = $_POST['invoice_supplier_list_duty_percent'];
    $invoice_supplier_list_import_duty = $_POST['invoice_supplier_list_import_duty'];
    $invoice_supplier_list_import_duty_total = $_POST['invoice_supplier_list_import_duty_total'];
    $invoice_supplier_list_freight_in = $_POST['invoice_supplier_list_freight_in'];
    $invoice_supplier_list_freight_in_total = $_POST['invoice_supplier_list_freight_in_total'];
    $invoice_supplier_list_currency_price = $_POST['invoice_supplier_list_currency_price']; 
    $invoice_supplier_list_currency_total = $_POST['invoice_supplier_list_currency_total']; 
    $list_price = $_POST['list_price']; 
    $list_total = $_POST['list_total']; 
    $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost']; 
    $invoice_supplier_list_cost_total = $_POST['invoice_supplier_list_cost_total']; 
    
    if(is_array($invoice_supplier_list_code)){
        for($i=0; $i < count($invoice_supplier_list_code) ; $i++){ 
            $data = [];
            $data['invoice_supplier_list_fix_type'] = $invoice_supplier_list_fix_type[$invoice_supplier_list_code[$i]];
            $data['invoice_supplier_list_duty'] = (float)filter_var( $invoice_supplier_list_duty_percent[$i] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_import_duty'] = (float)filter_var( $invoice_supplier_list_import_duty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_import_duty_total'] = (float)filter_var( $invoice_supplier_list_import_duty_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_freight_in'] = (float)filter_var( $invoice_supplier_list_freight_in[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_freight_in_total'] = (float)filter_var( $invoice_supplier_list_freight_in_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_currency_price'] = (float)filter_var( $invoice_supplier_list_currency_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_currency_total'] = (float)filter_var( $invoice_supplier_list_currency_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['list_price'] = (float)filter_var( $list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['list_total'] = (float)filter_var( $list_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_cost'] = (float)filter_var( $invoice_supplier_list_cost[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['invoice_supplier_list_cost_total'] = (float)filter_var( $invoice_supplier_list_cost_total[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            $invoice_supplier_list_model->updateCostListByCode($data,$invoice_supplier_list_code[$i]); 
        }
    } 
    ?>
        <script>window.location="index.php?app=invoice_supplier&action=cost&code=<?php echo $invoice_supplier_code;?>"</script>
    <?php
}else{
    if(!isset($_GET['date_start'])){
        $date_start = $_SESSION['date_start'];
    }else{
        $date_start = $_GET['date_start'];
        $_SESSION['date_start'] = $date_start;
    }

    if(!isset($_GET['date_end'])){
        $date_end = $_SESSION['date_end'];
    }else{
        $date_end = $_GET['date_end'];
        $_SESSION['date_end'] = $date_end;
    }

    $keyword = $_GET['keyword'];
    $supplier_code = $_GET['supplier_code'];

    $suppliers = $supplier_model->getSupplierBy();

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_code,$keyword);
    $supplier_orders_in = $invoice_supplier_model->getSupplierOrder();
    $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder();

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($invoice_suppliers);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');
}
?>