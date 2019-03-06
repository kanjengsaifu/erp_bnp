<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php'); 
require_once('../models/UserModel.php'); 
require_once('../models/MaterialModel.php');
require_once('../models/MaterialSupplierModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/CompanyModel.php'); 
require_once('../models/InvoiceSupplierModel.php'); 
require_once('../models/InvoiceSupplierListModel.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/invoice_supplier/views/";
$user_model = new UserModel;
$material_model = new MaterialModel;
$supplier_model = new SupplierModel; 
$purchase_order_model = new PurchaseOrderModel; 
$purchase_order_list_model = new PurchaseOrderListModel; 
$material_supplier_model = new MaterialSupplierModel;
$company_model = new CompanyModel;  
$invoice_supplier_model = new InvoiceSupplierModel;  
$invoice_supplier_list_model = new InvoiceSupplierListModel;  

$invoice_supplier_code = $_GET['invoice_supplier_code'];
$purchase_order_list_code = $_GET['purchase_order_list_code']; 
$purchase_order_code = $_GET['purchase_order_code']; 
$supplier_code = $_GET['supplier_code'];
$purchase_request_code = $_GET['purchase_request_code'];
$type = strtoupper($_GET['type']);
 

if ($_GET['action'] == 'insert' && $menu['invoice_supplier']['add']==1){
    
     

    $suppliers=$supplier_model->getSupplierBy();
    $supplier=$supplier_model->getSupplierByPO($purchase_order_code);
    $users=$user_model->getUserBy();  
 
    $first_date = date("d")."-".date("m")."-".date("Y"); 
    

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['invoice_supplier']['edit']){

    $materials=$material_model->getMaterialBy(); 
    $users=$user_model->getUserBy();

    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByCode($invoice_supplier_code);

    $supplier=$supplier_model->getSupplierByCode($invoice_supplier['supplier_code']);
    $suppliers=$supplier_model->getSupplierBy();
    // $sort = $supplier['supplier_domestic'];
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_code);
    // $invoice_supplier_import_duty_lists = $invoice_supplier_import_duty_list_model->getInvoiceSupplierImportDutyListBy($invoice_supplier_id);
    // $invoice_supplier_freight_in_lists = $invoice_supplier_freight_in_list_model->getInvoiceSupplierFreightInListBy($invoice_supplier_id);

    // $exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyCode($invoice_supplier['invoice_supplier_date_recieve'],$supplier['currency_id']);

    // $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierBy("","","","","","0",$lock_1,$lock_2);

    // for($i = 0 ; $i < count($invoice_suppliers) ; $i++){
    //     if($invoice_supplier_id == $invoice_suppliers[$i]['invoice_supplier_id']){ 
    //         $previous_id = $invoice_suppliers[$i-1]['invoice_supplier_id'];
    //         $previous_code = $invoice_suppliers[$i-1]['invoice_supplier_code_gen'];
    //         $next_id = $invoice_suppliers[$i+1]['invoice_supplier_id'];
    //         $next_code = $invoice_suppliers[$i+1]['invoice_supplier_code_gen'];

    //     }
    // }

    echo '<pre>';
    print_r($invoice_supplier_lists);
    echo '</pre>';
    require_once($path.'update.inc.php');
 

}else if ($_GET['action'] == 'detail'&& $menu['invoice_supplier']['view']==1){ 
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByCode($invoice_supplier_code);

    $supplier=$supplier_model->getSupplierByCode($invoice_supplier['supplier_code']); 
    // $sort = $supplier['supplier_domestic'];
    $invoice_supplier_lists = $invoice_supplier_list_model->getInvoiceSupplierListBy($invoice_supplier_code); 
    
    // if($supplier['vat_type'] == '0'){
    //     $vat= '0';
    // }else{
    //     $vat = $purchase_order['vat'];
    // }
    
    $purchase_orders = $invoice_supplier_model->getPurchaseOrderByInvoiceSupplierId($invoice_supplier_code);
    echo "<pre>";
    print_r($purchase_orders);
    echo "</pre>";
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['invoice_supplier']['delete']==1){

    // $notification_model->deleteNotificationByTypeCode('Purchase Order',$invoice_supplier_code);
    // $invoice_supplier_list_model->deletePurchaseOrderListByPurchaseOrderCode($invoice_supplier_code);
    $invoice_suppliers = $invoice_supplier_model->deleteInvoiceSupplierById($invoice_supplier_code);
?>
    <script>window.location="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>"</script>
<?php 

}else if ($_GET['action'] == 'add' && $menu['invoice_supplier']['add']==1){
  
    $invoice_supplier_code = "RR";
    $invoice_supplier_code = $invoice_supplier_model->getInvoiceSupplierLastCode($invoice_supplier_code,7);  
    if($invoice_supplier_code!=''){
        $data = []; 
        $data['invoice_supplier_code'] = $invoice_supplier_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['user_code'] = $login_user['user_code'];
        $data['invoice_supplier_code_receive'] = $_POST['invoice_supplier_code_receive'];
        $data['invoice_supplier_total_price'] = (float)filter_var($invoice_supplier_total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = $_POST['invoice_supplier_vat'];;
        $data['invoice_supplier_vat_price'] = (float)filter_var($invoice_supplier_vat_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (float)filter_var($invoice_supplier_net_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_branch'] = $_POST['invoice_supplier_branch'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due'];
        $data['invoice_supplier_due_day'] = $_POST['invoice_supplier_due_day'];
        $data['invoice_supplier_begin'] = $_POST['invoice_supplier_begin'];
        $data['invoice_supplier_remark'] = $_POST['invoice_supplier_remark']; 
        $data['addby'] = $login_user['user_code'];
        $data['branch_code'] = $login_user['branch_code'];
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        $invoice_supplier_code = $invoice_supplier_model->insertInvoiceSupplier($data);

        
        
        if($invoice_supplier_code !=''){ 

            $data = [];
            $material_code = $_POST['material_code'];
            $invoice_supplier_list_material_name = $_POST['invoice_supplier_list_material_name'];
            $invoice_supplier_list_material_detail = $_POST['invoice_supplier_list_material_detail'];
            $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
            $invoice_supplier_list_duty = $_POST['invoice_supplier_list_duty'];
            $invoice_supplier_list_fix_type = $_POST['invoice_supplier_list_fix_type'];
            $invoice_supplier_list_import_duty = $_POST['invoice_supplier_list_import_duty'];
            $invoice_supplier_list_freight_in = $_POST['invoice_supplier_list_freight_in'];
            $invoice_supplier_list_currency_price = $_POST['invoice_supplier_list_currency_price'];
            $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
            $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
            $invoice_supplier_list_price_sum = $_POST['invoice_supplier_list_price_sum'];
            $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
            $purchase_order_list_code = $_POST['purchase_order_list_code']; 

            if(is_array($material_code)){
               
                for($i=0; $i < count($material_code) ; $i++){ 
                    $data_sub = []; 
                    $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                    $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                    $data_sub['invoice_supplier_list_no'] = $i;
                    $data_sub['material_code'] = $material_code[$i]; 
                    
                    $data_sub['invoice_supplier_list_material_name'] = $invoice_supplier_list_material_name[$i];
                    $data_sub['invoice_supplier_list_material_detail'] = $invoice_supplier_list_material_detail[$i];
                    $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['invoice_supplier_list_price_sum'] = (float)filter_var($invoice_supplier_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                    $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                    $data_sub['purchase_order_list_code'] = $purchase_order_list_code[$i]; 
                    $data_sub['addby'] = $login_user['user_code']; 
                    $data_sub['branch_code'] = $login_user['branch_code'];
                    // echo '<pre>';
                    // print_r($data_sub);
                    // echo '</pre>';
                    $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);

                   
                }
                
            }else if($material_code != ""){
                $data_sub = [];
                $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                $data_sub['invoice_supplier_list_no'] = 0; 
                $data_sub['material_code'] = $material_code;
                
                $data_sub['invoice_supplier_list_material_name'] = $invoice_supplier_list_material_name;
                $data_sub['invoice_supplier_list_material_detail'] = $invoice_supplier_list_material_detail;
                $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price_sum'] = (float)filter_var($invoice_supplier_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
                $data_sub['purchase_order_list_code'] = $purchase_order_list_code; 
                $data_sub['addby'] = $login_user['user_code']; 
                $data_sub['branch_code'] = $login_user['branch_code'];
     
                $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
 
            }
           
            ?>
            <script>
                window.location="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>&action=update&invoice_supplier_code=<?php echo $invoice_supplier_code;?>";
                
            </script>
            <?php
        }else{
            ?>
            <script>
            window.history.back();
            </script>
            <?php
        }
       
    }else{
        ?>
            <script>
            window.history.back();
            </script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit' && $menu['invoice_supplier']['edit']==1){
    
    
    if($invoice_supplier_code!=''){

        $data = [];
        
        $invoice_supplier_list_code = $_POST['invoice_supplier_list_code'];
        $material_code = $_POST['material_code'];
        $invoice_supplier_list_material_name = $_POST['invoice_supplier_list_material_name'];
        $invoice_supplier_list_material_detail = $_POST['invoice_supplier_list_material_detail'];
        $invoice_supplier_list_qty = $_POST['invoice_supplier_list_qty'];
        $invoice_supplier_list_duty = $_POST['invoice_supplier_list_duty'];
        $invoice_supplier_list_fix_type = $_POST['invoice_supplier_list_fix_type'];
        $invoice_supplier_list_import_duty = $_POST['invoice_supplier_list_import_duty'];
        $invoice_supplier_list_freight_in = $_POST['invoice_supplier_list_freight_in'];
        $invoice_supplier_list_currency_price = $_POST['invoice_supplier_list_currency_price'];
        $invoice_supplier_list_cost = $_POST['invoice_supplier_list_cost'];
        $invoice_supplier_list_price = $_POST['invoice_supplier_list_price'];
        $invoice_supplier_list_price_sum = $_POST['invoice_supplier_list_price_sum'];
        $invoice_supplier_list_remark = $_POST['invoice_supplier_list_remark'];
        $purchase_order_list_code = $_POST['purchase_order_list_code'];  
 
        $invoice_supplier_list_model->deleteInvoiceSupplierListByInvoiceSupplierCodeNotIN($invoice_supplier_code,$invoice_supplier_list_code);
        
        if(is_array($material_code)){
            for($i=0; $i < count($material_code) ; $i++){
                $data_sub = []; 
                if($invoice_supplier_list_code[$i]=='0'){

                    $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
                }else{

                    $data_sub['invoice_supplier_list_code'] = $invoice_supplier_list_code[$i];
                }
                $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
                $data_sub['invoice_supplier_list_no'] = $i;
                $data_sub['material_code'] = $material_code[$i]; 
                
                $data_sub['invoice_supplier_list_material_name'] = $invoice_supplier_list_material_name[$i];
                $data_sub['invoice_supplier_list_material_detail'] = $invoice_supplier_list_material_detail[$i];
                $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['invoice_supplier_list_price_sum'] = (float)filter_var($invoice_supplier_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark[$i];
                $data_sub['purchase_order_list_code'] = $purchase_order_list_code[$i]; 
                $data_sub['updateby'] = $login_user['user_code']; 
                $data_sub['addby'] = $login_user['user_code']; 
                $data_sub['branch_code'] = $login_user['branch_code'];

                if($invoice_supplier_list_code[$i] != '0' ){
                    $invoice_supplier_list_model->updateInvoiceSupplierListById($data_sub,$invoice_supplier_list_code[$i]);
                }else{
                    $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                    
                  
                }
                // echo '<pre>';
                // print_r($invoice_supplier_list_code[$i]);
                // echo '</pre>';
            } 
            
        }else if($material_code != ""){
            $data_sub = []; 
            if($invoice_supplier_list_code=='0'){

                $data_sub['invoice_supplier_list_code'] = $invoice_supplier_code.date("YmdHisu").$i;
            }else{

                $data_sub['invoice_supplier_list_code'] = $invoice_supplier_list_code;
            }
            $data_sub['invoice_supplier_code'] = $invoice_supplier_code;
            $data_sub['invoice_supplier_list_no'] = 0;
            $data_sub['material_code'] = $material_code; 
            
            $data_sub['invoice_supplier_list_material_name'] = $invoice_supplier_list_material_name;
            $data_sub['invoice_supplier_list_material_detail'] = $invoice_supplier_list_material_detail;
            $data_sub['invoice_supplier_list_qty'] = (float)filter_var($invoice_supplier_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_price'] = (float)filter_var($invoice_supplier_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['invoice_supplier_list_price_sum'] = (float)filter_var($invoice_supplier_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
            $data_sub['invoice_supplier_list_remark'] = $invoice_supplier_list_remark;
            $data_sub['purchase_order_list_code'] = $purchase_order_list_code; 
            $data_sub['updateby'] = $login_user['user_code'];
            $data_sub['addby'] = $login_user['user_code']; 
            $data_sub['branch_code'] = $login_user['branch_code']; 

            if($purchase_order_list_code != '0' ){
                $invoice_supplier_list_model->updateInvoiceSupplierListById($data_sub,$purchase_order_list_code);
            }else{
                $id = $invoice_supplier_list_model->insertInvoiceSupplierList($data_sub);
                
            }
        } 
        
         
        $data['supplier_code'] = $_POST['supplier_code'];  
        $data['invoice_supplier_code_receive'] = $_POST['invoice_supplier_code_receive'];
        $data['invoice_supplier_total_price'] = (float)filter_var($invoice_supplier_total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat'] = $_POST['invoice_supplier_vat'];;
        $data['invoice_supplier_vat_price'] = (float)filter_var($invoice_supplier_vat_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = (float)filter_var($invoice_supplier_net_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_branch'] = $_POST['invoice_supplier_branch'];
        $data['invoice_supplier_due'] = $_POST['invoice_supplier_due'];
        $data['invoice_supplier_due_day'] = $_POST['invoice_supplier_due_day'];
        $data['invoice_supplier_begin'] = $_POST['invoice_supplier_begin'];
        $data['invoice_supplier_remark'] = $_POST['invoice_supplier_remark']; 
        $data['updateby'] = $login_user['user_code'];

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        $output = $invoice_supplier_model->updateInvoiceSupplierByCode($invoice_supplier_code , $data); 
 

        if($output!='0'){ 
            ?>
                    <script>
                    window.location="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>&action=update&invoice_supplier_code=<?php echo $invoice_supplier_code;?>"
                    </script>
            <?php
        }else{
            ?>
                    <script>
                    window.history.back();
                    </script>
            <?php
        }
    
    }else{
        ?>
            <script>
            window.history.back();
            </script>
        <?php
    }
        
 
}else if ($menu['invoice_supplier']['view']==1){

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

    if(!isset($_GET['keyword'])){
        $keyword = $_SESSION['keyword'];
    }else{
        
        $keyword = $_GET['keyword']; 
        $_SESSION['keyword'] = $keyword;
    }

    $supplier_code = $_GET['supplier_code'];

    $suppliers=$supplier_model->getSupplierBy();

    $invoice_suppliers = $invoice_supplier_model->getInvoiceSupplierByPO($purchase_order_code);
    // $supplier_orders_in = $invoice_supplier_model->getSupplierOrder("ภายในประเทศ");
    // $supplier_orders_out = $invoice_supplier_model->getSupplierOrder("ภายนอกประเทศ");
    // $purchase_orders_in = $invoice_supplier_model->getPurchaseOrder("ภายในประเทศ");
    // $purchase_orders_out = $invoice_supplier_model->getPurchaseOrder("ภายนอกประเทศ");

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
    echo '<pre>';
    print_r($invoice_suppliers);
    echo '</pre>';
    require_once($path.'view.inc.php');

}





?>