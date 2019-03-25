<?php
require_once('../models/PurchaseRequestModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/StockGroupModel.php');

$path = "modules/purchase_request/views/";

$user_model = new UserModel;
$customer_model = new CustomerModel;
$notification_model = new NotificationModel;
$purchase_request_model = new PurchaseRequestModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$product_model = new ProductModel;
$supplier_model = new SupplierModel; 
$stock_group_model = new StockGroupModel; 

$purchase_request_code = $_GET['code'];

if(!isset($_GET['list'])){
    $list = $_SESSION['list'];
}else{
    $list = $_GET['list']; 
    $_SESSION['list'] = $list;
}

if ($_GET['action'] == 'insert' && $menu['purchase']['add']){
    $products = $product_model->getProductBy();
    $customers = $customer_model->getCustomerBy();
    $suppliers = $supplier_model->getSupplierBy();
    $stock_groups = $stock_group_model->getStockGroupBy();
    $users = $user_model->getUserByPermission('purchase');

    $code = "PR".date("y").date("m").date("d");
    $last_code = $purchase_request_model->getPurchaseRequestLastCode($code,4);
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update' && $menu['purchase']['edit']){
    $products = $product_model->getProductBy();
    $customers = $customer_model->getCustomerBy();
    $suppliers = $supplier_model->getSupplierBy();
    $stock_groups = $stock_group_model->getStockGroupBy();
    $users = $user_model->getUserBy();
    $purchase_request = $purchase_request_model->getPurchaseRequestByCode($purchase_request_code);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_code);

    $first_date = date("d")."-".date("m")."-".date("Y");

    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'detail'){
    $purchase_request = $purchase_request_model->getPurchaseRequestViewByCode($purchase_request_code);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_code);
    require_once($path.'detail.inc.php');
}else if ($_GET['action'] == 'delete'){
    $notification_model->deleteNotificationByTypeID('Purchase Request',$purchase_request_code);
    $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestID($purchase_request_code);
    $purchase_request = $purchase_request_model->deletePurchaseRequestByCode($purchase_request_code);
    ?> <script>window.location="index.php?app=purchase_request"</script> <?php
}else if ($_GET['action'] == 'cancelled'){
    $purchase_request_model->cancelPurchaseRequestByCode($purchase_request_code);
    ?> <script>window.location="index.php?app=purchase_request"</script> <?php
}else if ($_GET['action'] == 'uncancelled'){
    $purchase_request_model->uncancelPurchaseRequestByCode($purchase_request_code);
    ?> <script>window.location="index.php?app=purchase_request"</script> <?php
}else if ($_GET['action'] == 'add' && $menu['purchase']['add']){
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['purchase_request_code'] = $_POST['purchase_request_code'];
        $data['request_date'] = $_POST['request_date'];
        $data['request_alert'] = $_POST['request_alert'];
        $data['purchase_request_type'] = $_POST['purchase_request_type'];
        $data['approve_status'] = "Waiting";
        $data['customer_code'] = $_POST['customer_code'];
        $data['request_remark'] = $_POST['request_remark'];
        $data['addby'] = $login_user['user_code'];

        $purchase_request_code = $purchase_request_model->insertPurchaseRequest($data); 

        if($purchase_request_code != ''){
            $product_code = $_POST['product_code'];
            $supplier_code = $_POST['supplier_code'];
            $stock_group_code = $_POST['stock_group_code'];
            $purchase_request_list_code = $_POST['purchase_request_list_code'];
            $request_list_qty = $_POST['request_list_qty'];
            $request_list_remark = $_POST['request_list_remark'];

            $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestIDNotIN($_POST['purchase_request_code'],$purchase_request_list_code);

            if(is_array($product_code)){
                for($i=0; $i < count($product_code) ; $i++){
                    $data = [];
                    $data['purchase_request_code'] = $purchase_request_code;
                    $data['request_list_no'] = $i;
                    $data['purchase_request_list_code'] = $purchase_request_code.date("YmdHisu").$i;
                    $data['product_code'] = $product_code[$i];
                    $data['supplier_code'] = $supplier_code[$i];
                    $data['stock_group_code'] = $stock_group_code[$i];
                    $data['request_list_qty'] = $request_list_qty[$i];
                    $data['request_list_remark'] = $request_list_remark[$i];
        
                    if($purchase_request_list_code[$i] == ''){
                        $purchase_request_list_model->insertPurchaseRequestList($data);
                    }else{
                        $purchase_request_list_model->updatePurchaseRquestListByCode($data,$purchase_request_list_code[$i]);
                    }
                }
            }else{
                $data = [];
                $data['purchase_request_list_code'] = $purchase_request_code.date("YmdHisu").$i;
                $data['request_list_no'] = 0;
                $data['purchase_request_code'] = $purchase_request_code;
                $data['product_code'] = $product_code;
                $data['supplier_code'] = $supplier_code;
                $data['stock_group_code'] = $stock_group_code;
                $data['request_list_qty'] = $request_list_qty;
                $data['request_list_remark'] = $request_list_remark;

                if($purchase_request_list_code == ''){
                    $purchase_request_list_model->insertPurchaseRequestList($data);
                }else{
                    $purchase_request_list_model->updatePurchaseRquestListByCode($data,$purchase_request_list_code);
                }
            } 
            
            ?> <script>window.location="index.php?app=purchase_request&action=update&code=<?php echo $purchase_request_code;?>"</script> <?php
        }else{ 
            ?> <script>window.history.back();</script> <?php
        }
    }else{
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'edit' && $menu['purchase']['edit']){
    if(isset($_POST['purchase_request_code'])){
        $data = [];
        $data['request_date'] = $_POST['request_date'];
        $data['request_alert'] = $_POST['request_alert'];
        $data['approve_status'] = "Waiting";
        $data['user_code'] = $_POST['user_code'];
        $data['customer_code'] = $_POST['customer_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['request_remark'] = $_POST['request_remark'];

        $output = $purchase_request_model->updatePurchaseRequestByCode($purchase_request_code,$data);

        $product_code = $_POST['product_code'];
        $stock_group_code = $_POST['stock_group_code'];
        $purchase_request_list_code = $_POST['purchase_request_list_code'];
        $request_list_qty = $_POST['request_list_qty'];
        $request_list_remark = $_POST['request_list_remark'];

        $purchase_request_list_model->deletePurchaseRequestListByPurchaseRequestIDNotIN($purchase_request_code,$purchase_request_list_code);

        if(is_array($product_code)){
            for($i=0; $i < count($product_code) ; $i++){
                $data = [];
                $data['purchase_request_list_code'] = $purchase_request_code.date("YmdHisu").$i;
                $data['purchase_request_code'] = $purchase_request_code;
                $data['product_code'] = $product_code[$i];
                $data['supplier_code'] = $supplier_code[$i];
                $data['stock_group_code'] = $stock_group_code[$i];
                $data['request_list_qty'] = $request_list_qty[$i];
                $data['request_list_remark'] = $request_list_remark[$i];
    
                if($purchase_request_list_code[$i] == '0'){
                    $purchase_request_list_model->insertPurchaseRequestList($data);
                }else{
                    $purchase_request_list_model->updatePurchaseRquestListByCode($data,$purchase_request_list_code[$i]);
                }
            }
        }else{
            $data = [];
            $data['purchase_request_list_code'] = $purchase_request_code.date("YmdHisu").$i;
            $data['purchase_request_code'] = $purchase_request_code;
            $data['product_code'] = $product_code;
            $data['supplier_code'] = $supplier_code;
            $data['stock_group_code'] = $stock_group_code;
            $data['request_list_qty'] = $request_list_qty;
            $data['request_list_remark'] = $request_list_remark;

            if($purchase_request_list_code == '0'){
                $purchase_request_list_model->insertPurchaseRequestList($data);
            }else{
                $purchase_request_list_model->updatePurchaseRquestListByCode($data,$purchase_request_list_code);
            }
        }

        if($output){
            $notification_model->setNotification("Purchase Request",$purchase_request_code,"Purchase Request <br>No. ".$_POST['purchase_request_code']." ".$data['urgent_status'],"index.php?app=purchase_request&action=detail&code=$purchase_request_code","license_manager_page",'High');
            ?> <script>window.location="index.php?app=purchase_request"</script> <?php
        }else{
            ?> <script>window.history.back();</script> <?php
        }
    }else{
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'rewrite' && $menu['purchase']['edit']){
    $purchase_request = $purchase_request_model->getPurchaseRequestByCode($purchase_request_code);
    $purchase_request_lists = $purchase_request_list_model->getPurchaseRequestListBy($purchase_request_code);
    $purchase_request_model->cancelPurchaseRequestByCode($purchase_request_code);

    $data = [];
    $data['purchase_request_rewrite_no'] = $purchase_request['purchase_request_rewrite_no'] + 1;
    $data['purchase_request_code'] = $purchase_request['purchase_request_code']."-REVISE-".$data['purchase_request_rewrite_no'];
    $data['purchase_request_code'] = $data['purchase_request_code'];
    $data['request_date'] = $purchase_request['request_date']; 
    $data['purchase_request_type'] = $purchase_request['purchase_request_type'];
    $data['approve_status'] = "Waiting";
    $data['customer_code'] = $purchase_request['customer_code'];
    $data['purchase_request_rewrite_code'] = $purchase_request_code; 
    $data['request_remark'] = $purchase_request['request_remark'];

    $purchase_request_code = $purchase_request_model->insertPurchaseRequest($data);

    if($purchase_request_code != 0){
        if(count($purchase_request_lists) > 0){
            for($i=0; $i < count($purchase_request_lists) ; $i++){
                $data = [];
                $data['purchase_request_code'] = $purchase_request_code;
                $data['purchase_request_list_code'] = $purchase_request_code.date("YmdHisu").$i;
                $data['product_code'] = $purchase_request_lists[$i]['product_code'];
                $data['supplier_code'] = $purchase_request_lists[$i]['supplier_code'];
                $data['stock_group_code'] = $purchase_request_lists[$i]['stock_group_code'];
                $data['request_list_qty'] = $purchase_request_lists[$i]['request_list_qty'];
                $data['request_list_remark'] = $purchase_request_lists[$i]['request_list_remark'];
                $purchase_request_list_model->insertPurchaseRequestList($data); 
            }
        }
        ?> <script>window.location="index.php?app=purchase_request&action=update&code=<?php echo $purchase_request_code;?>"</script> <?php
    }else{
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'approve'){
    if(isset($_POST['approve_status'])){
        $data = [];
        $data['approve_status'] = $_POST['approve_status'];
        $data['purchase_request_accept_by'] = $login_user['user_code'];
        $data['purchase_request_status'] = 'Approved';
        $data['updateby'] = $login_user['user_code'];

        $result = $purchase_request_model->updatePurchaseRequestAcceptByCode($purchase_request_code,$data);

        if($result){
            $purchase_request = $purchase_request_model->getPurchaseRequestViewByCode($purchase_request_code);
            $notification_model->setNotificationSeenByTypeID('Purchase Request',$purchase_request_code);

            $notification_model->setNotification("Purchase Request",$purchase_request_code,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['approve_status'],"index.php?app=purchase_request&action=detail&code=$purchase_request_code","license_purchase_page",'High');
            $notification_model->setNotification("Purchase Request",$purchase_request_code,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['approve_status'],"index.php?app=purchase_request&action=detail&code=$purchase_request_code","license_purchase_page",'Medium');
            $notification_model->setNotificationByUserID("Purchase Request",$purchase_request_code,"Purchase Request <br>No. ".$purchase_request['purchase_request_code']." has ".$purchase_request['approve_status'],"index.php?app=purchase_request&action=detail&code=$purchase_request_code",$purchase_request['user_code']);
           
            ?> <script>window.location="index.php?app=purchase_request"</script> <?php
        }else{
            ?> <script>window.history.back();</script> <?php
        }
    }else{
        ?> <script>window.history.back();</script> <?php
    }
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
    
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    }
    
    $keyword = $_GET['keyword'];

    $customers = $customer_model->getCustomerBy();
    $suppliers = $supplier_model->getSupplierBy();
    $purchase_request = $purchase_request_model->getPurchaseRequestBy($date_start,$date_end,$keyword);

    require_once($path.'view.inc.php');
}
?>