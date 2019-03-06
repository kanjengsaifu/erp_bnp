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

require_once('../functions/CodeGenerateFunction.func.php'); 

date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_order/views/";
$user_model = new UserModel;
$material_model = new MaterialModel;
$supplier_model = new SupplierModel; 
$purchase_order_model = new PurchaseOrderModel; 
$purchase_order_list_model = new PurchaseOrderListModel; 
$material_supplier_model = new MaterialSupplierModel;
$company_model = new CompanyModel; 



$purchase_order_code = $_GET['purchase_order_code'];
$purchase_order_list_code = $_GET['purchase_order_list_code']; 
$supplier_code = $_GET['supplier_code']; 
$type = strtoupper($_GET['type']);
 

if ($_GET['action'] == 'insert' && $menu['purchase_order']['add']==1){
     
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy(); 
      
    if($supplier_code != ""){
        $supplier=$supplier_model->getSupplierByCode($supplier_code);
        if($supplier['vat_type'] == '0'){
            $vat= '0';
        }else{
            $vat = $invoice_supplier['vat'];
        }
        //$materials=$material_supplier_model->getMaterialBySupplierCode(/*$supplier_code*/);
        $materials=$material_model->getMaterialBy('','','','Active');

        if($supplier['supplier_domestic'] == "ภายในประเทศ"){
            $paper = $paper_model->getPaperByCode('11');
        }else{
            $paper = $paper_model->getPaperByCode('10');
        }

        $user=$user_model->getUserByCode($admin_id);
        
        $data = [];
        $data['year'] = date("Y");
        $data['month'] = date("m");
        $data['number'] = "0000000000";
        $data['employee_name'] = $user["user_name_en"];

        $code = $code_generate->cut2Array($paper['paper_code'],$data);
        $last_code = "";
        for($i = 0 ; $i < count($code); $i++){
        
            if($code[$i]['type'] == "number"){
                $last_code = $purchase_order_model->getPurchaseOrderLastCode($last_code,$code[$i]['length']);
            }else{
                $last_code .= $code[$i]['value'];
            }   
        } 

        $purchase_order_lists = $purchase_order_model->generatePurchaseOrderListBySupplierId($supplier_code,$purchase_request_id,$type);
        
    }
    $first_date = date("d")."-".date("m")."-".date("Y"); 
    

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['purchase_order']['edit']==1){
    
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);
    $type=$purchase_order["purchase_order_type"];
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);
    $supplier=$supplier_model->getSupplierByCode($purchase_order['supplier_code']);
    if($supplier['vat_type'] == '0'){
        $vat= '0';
    }else{
        $vat = $invoice_supplier['vat'];
    }
    //$materials=$material_supplier_model->getMaterialBySupplierCode($purchase_order['supplier_code']);
    $materials=$material_model->getMaterialBy('','','','Active');
    // echo "<pre>";
    // print_r( $purchase_order_lists);
    // echo "</pre>";
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'&& $menu['purchase_order']['view']==1){ 
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByCode($purchase_order_code);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);
    // if($supplier['vat_type'] == '0'){
    //     $vat= '0';
    // }else{
    //     $vat = $purchase_order['vat'];
    // }
    // echo "<pre>";
    // print_r($purchase_order);
    // echo "</pre>";
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['purchase_order']['delete']==1){

    // $notification_model->deleteNotificationByTypeCode('Purchase Order',$purchase_order_code);
    // $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderCode($purchase_order_code);
    $purchase_orders = $purchase_order_model->deletePurchaseOrderByCode($purchase_order_code);
?>
    <script>window.location="index.php?app=purchase_order"</script>
<?php

}else if ($_GET['action'] == 'cancelled' && $menu['purchase_order']['delete']==1){
    $purchase_order_model->cancelPurchaseOrderByCode($purchase_order_code);
?>
    <script>
        window.location="index.php?app=purchase_order"
    </script>
<?php

}else if ($_GET['action'] == 'uncancelled' && $menu['purchase_order']['delete']==1){
    $purchase_order_model->uncancelPurchaseOrderByCode($purchase_order_code);
?>
     <script>
        window.location="index.php?app=purchase_order"
    </script>
<?php

}else if ($_GET['action'] == 'add' && $menu['purchase_order']['add']==1){
    $purchase_order_code = "PO";
    $purchase_order_code = $purchase_order_model->getPurchaseOrderLastCode($purchase_order_code,7);  
    if($purchase_order_code!=''){
        $data = []; 
        $data['purchase_order_code'] = $purchase_order_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term']; 
        $data['purchase_order_type'] = $type;
        $data['purchase_order_status'] = 'New';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by']; 
        $data['purchase_order_remark'] = $_POST['purchase_order_remark'];
        $data['purchase_order_delivery_date'] = $_POST['purchase_order_delivery_date'];
        $data['purchase_order_total_price'] = (float)filter_var($purchase_order_total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat_type'] =  $_POST['purchase_order_vat_type'];
        $data['purchase_order_vat'] = $_POST['purchase_order_vat'];;
        $data['purchase_order_vat_price'] = (float)filter_var($purchase_order_vat_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net_price'] = (float)filter_var($purchase_order_net_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['user_code'] = $_POST['user_code'];
        $data['addby'] = $login_user['user_code'];
        $data['branch_code'] = $login_user['branch_code'];

        $purchase_order_code = $purchase_order_model->insertPurchaseOrder($data);

        if($purchase_order_code != ""){
            $data = [];
            $material_code = $_POST['material_code']; 

            $purchase_order_list_qty = $_POST['purchase_order_list_qty'];
            $purchase_order_list_price = $_POST['purchase_order_list_price'];
            $purchase_order_list_price_sum = $_POST['purchase_order_list_price_sum'];
            $purchase_order_list_delivery_min = $_POST['purchase_order_list_delivery_min'];
            $purchase_order_list_delivery_max = $_POST['purchase_order_list_delivery_max'];
            $purchase_order_list_remark = $_POST['purchase_order_list_remark'];

           
            if(is_array($material_code)){
                for($i=0; $i < count($material_code) ; $i++){
                    $data_sub = [];
                    $data_sub['purchase_order_list_code'] = $purchase_order_code.date("YmdHisu").$i;
                    $data_sub['purchase_order_code'] = $purchase_order_code;
                    $data_sub['material_code'] = $material_code[$i]; 
                    $data_sub['purchase_order_list_no'] = $i;
                    
                    $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min[$i];
                    $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max[$i];
                    $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark[$i];
                    $data_sub['addby'] = $login_user['user_code'];
                    
                    // echo '<pre>';
                    // print_r($data_sub);
                    // echo '</pre>';
                    $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                    // if($id != ""){
                     
                    // }
                }
                $data['purchase_order_status'] = 'New';
            }else if($material_code != ""){
                $data_sub = [];
                $data_sub['purchase_order_list_code'] = $purchase_order_code.date("YmdHisu").$i;
                $data_sub['purchase_order_code'] = $purchase_order_code;
                $data_sub['purchase_order_list_no'] = 0;
                $data_sub['material_code'] = $material_code; 
                $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min;
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max;
                $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark;
                $data_sub['addby'] = $login_user['user_code'];

                // echo '<pre>';
                // print_r($data_sub);
                // echo '</pre>';
                $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                // if($id != ""){
                    
                // }
                $data['purchase_order_status'] = 'New';
            }else{
                $data['purchase_order_status'] = '';
            }

            $data['purchase_order_code'] = $_POST['purchase_order_code'];
            $data['supplier_code'] = $_POST['supplier_code'];
            $data['purchase_order_code'] = $_POST['purchase_order_code']; 
            $data['purchase_order_date'] = $_POST['purchase_order_date'];
            $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term']; 
            $data['purchase_order_status'] = 'New';
            $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by']; 
            $data['purchase_order_remark'] = $_POST['purchase_order_remark'];
            $data['purchase_order_delivery_date'] = $_POST['purchase_order_delivery_date'];
            $data['purchase_order_total_price'] = (float)filter_var($purchase_order_total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['purchase_order_vat_type'] =  $_POST['purchase_order_vat_type'];
            $data['purchase_order_vat'] = $_POST['purchase_order_vat'];;
            $data['purchase_order_vat_price'] = (float)filter_var($purchase_order_vat_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['purchase_order_net_price'] = (float)filter_var($purchase_order_net_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data['user_code'] = $_POST['user_code'];

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';

            $purchase_order_model->updatePurchaseOrderByCode($purchase_order_code,$data);

            $save_material_price = $_POST['save_material_price'];
            // echo '<pre>';
            // print_r($save_material_price);
            // echo '</pre>';
            for($i=0; $i < count($save_material_price); $i++){
                $material_price = 0;
                for($j=0; $j < count($material_code); $j++){
                    if($material_code[$j] == $save_material_price[$i]){
                        $material_price = (float)filter_var($purchase_order_list_price[$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    }
                }
                $material_supplier_prices =  $material_supplier_model->getMaterialSupplierPriceByCode($save_material_price[$i],$_POST['supplier_code']);
    
                $data = [];
                $data['material_code'] = $save_material_price[$i];
                $data['supplier_code'] =$_POST['supplier_code'];
                $data['material_supplier_buyprice'] = $material_price; 
                $data['material_supplier_lead_time'] = 0;   
    
                if(count($material_supplier_prices) > 0){ 
                    $material_supplier_model->updateMaterialSupplierPriceByCode($data);
                }else{
                    $material_supplier_code = "MATS";
                    $material_supplier_code = $material_supplier_model->getMaterialSupplierLastCode($material_supplier_code,3);  
                    $data['material_supplier_code'] = $material_supplier_code;
                    $material_supplier_model->insertMaterialSupplier($data);
                }
            }


            ?>
                    <script>
                     window.location="index.php?app=purchase_order&action=update&purchase_order_code=<?php echo $purchase_order_code;?>"</script>
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
    
}else if ($_GET['action'] == 'edit' && $menu['purchase_order']['edit']==1){
    
    if(isset($_POST['purchase_order_code'])){

        $data = [];
        
        $material_code = $_POST['material_code']; 

        $purchase_order_list_code = $_POST['purchase_order_list_code'];
        $purchase_order_list_qty = $_POST['purchase_order_list_qty'];
        $purchase_order_list_price = $_POST['purchase_order_list_price'];
        $purchase_order_list_price_sum = $_POST['purchase_order_list_price_sum'];
        $purchase_order_list_delivery_min = $_POST['purchase_order_list_delivery_min'];
        $purchase_order_list_delivery_max = $_POST['purchase_order_list_delivery_max'];
        $purchase_order_list_remark = $_POST['purchase_order_list_remark'];

        $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderCodeNotIN($purchase_order_code,$purchase_order_list_code);
        
        if(is_array($material_code)){
            for($i=0; $i < count($material_code) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_list_code'] = $purchase_order_code.date("YmdHisu").$i;
                $data_sub['purchase_order_list_no'] = $i;
                $data_sub['purchase_order_code'] = $purchase_order_code;
                $data_sub['material_code'] = $material_code[$i];
                $data_sub['stock_group_id'] = $stock_group_id[$i];
                
                $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min[$i];
                $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max[$i];
                $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark[$i];

                if($purchase_order_list_code[$i] != '0' ){
                    $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_list_code[$i]);
                }else{
                    $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub);
                  
                }
                
            }
            $data['purchase_order_status'] = 'New';
        }else if($material_code != ""){
            $data_sub = [];
            $data_sub['purchase_order_list_code'] = $purchase_order_code.date("YmdHisu").$i;
            $data_sub['purchase_order_list_no'] = 0;
            $data_sub['purchase_order_code'] = $purchase_order_code;
            $data_sub['material_code'] = $material_code; 
            $data_sub['purchase_order_list_qty'] = (float)filter_var($purchase_order_list_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_price'] = (float)filter_var($purchase_order_list_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_price_sum'] = (float)filter_var($purchase_order_list_price_sum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $data_sub['purchase_order_list_delivery_min'] = $purchase_order_list_delivery_min;
            $data_sub['purchase_order_list_delivery_max'] = $purchase_order_list_delivery_max;
            $data_sub['purchase_order_list_remark'] = $purchase_order_list_remark;

            if($purchase_order_list_code != '0'){
                $purchase_order_list_model->updatePurchaseOrderListByIdAdmin($data_sub,$purchase_order_list_code);
            }else{
                $id = $purchase_order_list_model->insertPurchaseOrderList($data_sub); 
            }
             
            $data['purchase_order_status'] = 'New';
        }else{
            $data['purchase_order_status'] = '';
        }

        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['purchase_order_category'] = $_POST['purchase_order_category'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term']; 
        $data['purchase_order_status'] = 'New';
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by']; 
        $data['purchase_order_remark'] = $_POST['purchase_order_remark'];
        $data['purchase_order_delivery_date'] = $_POST['purchase_order_delivery_date'];
        $data['purchase_order_total_price'] = (float)filter_var($purchase_order_total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat_type'] =  $_POST['purchase_order_vat_type'];
        $data['purchase_order_vat'] = $_POST['purchase_order_vat'];;
        $data['purchase_order_vat_price'] = (float)filter_var($purchase_order_vat_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net_price'] = (float)filter_var($purchase_order_net_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['user_code'] = $_POST['user_code'];

        $output = $purchase_order_model->updatePurchaseOrderByCode($purchase_order_code , $data); 

        $save_material_price = $_POST['save_material_price'];
        for($i=0; $i < count($save_material_price); $i++){
            $material_price = 0;
            for($j=0; $j < count($material_code); $j++){
                if($material_code[$j] == $save_material_price[$i]){
                    $material_price = (float)filter_var($purchase_order_list_price[$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
            }
            $material_supplier_prices =  $material_supplier_model->getMaterialSupplierPriceByCode($save_material_price[$i],$_POST['supplier_code']);

            $data = [];
            $data['material_code'] = $save_material_price[$i];
            $data['supplier_code'] =$_POST['supplier_code'];
            $data['material_buyprice'] = $material_price;
            
            $data['material_supplier_status'] = 'Active';

            if(count($material_supplier_prices) > 0){ 
                $material_supplier_model->updateMaterialSupplierPriceByCode($data);
            }else{
                $material_supplier_model->insertMaterialSupplier($data);
            }
        }

        if($output){ 
            ?>
                    <script>window.location="index.php?app=purchase_order&action=update&purchase_order_code=<?php echo $purchase_order_code;?>"</script>
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
   
    
}else if ($menu['purchase_order']['view']==1){

    $view_type = $_GET['view_type'];

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

    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }

    $supplier_code = $_GET['supplier_code'];

    $suppliers=$supplier_model->getSupplierBy();

    $purchase_orders = $purchase_order_model->getPurchaseOrderBy($login_user['branch_code'],$date_start,$date_end,$supplier_code,$keyword);
    // echo '<pre>';
    // print_r($purchase_orders);
    // echo '</pre>'; 
    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 50;
    $list_size = count($purchase_orders);
    $page_max = (int)($list_size/$page_size);
    if($list_size%$page_size > 0){
        $page_max += 1;
    }

    // echo "<pre>";
    // print_r( $purchase_orders);
    // echo"</pre>";

    require_once($path.'view.inc.php');

}





?>