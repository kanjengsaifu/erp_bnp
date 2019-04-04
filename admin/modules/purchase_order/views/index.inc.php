<?php
require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/PurchaseRequestListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/ProductSupplierModel.php');
require_once('../models/SupplierModel.php');
require_once('../models/CompanyModel.php');
require_once('../models/InvoiceSupplierModel.php');

require_once('../functions/DateTimeFunction.func.php');

$path = "modules/purchase_order/views/";

$user_model = new UserModel;
$product_model = new ProductModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$purchase_order_model = new PurchaseOrderModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$purchase_request_list_model = new PurchaseRequestListModel;
$product_supplier_model = new ProductSupplierModel;
$company_model = new CompanyModel;
$invoice_supplier_model = new InvoiceSupplierModel;

$date_time = new DateTimeFunction;

$purchase_order_code = $_GET['code'];
$purchase_order_list_code = $_GET['list']; 
$supplier_code = $_GET['supplier_code'];
 
if ($_GET['action'] == 'insert' && $menu['purchase_order']['add']){
    $suppliers = $supplier_model->getSupplierBy();
    $users = $user_model->getUserBy(); 
    $code = "PO".date("y").date("m").date("d");
    $purchase_order_code = $purchase_order_model->getPurchaseOrderLastCode($code,4); 

    if($supplier_code != ""){
        $supplier = $supplier_model->getSupplierByCode($supplier_code);
        $purchase_order_lists = $purchase_order_model->generatePurchaseOrderListBySupplierCode($supplier_code);
    }

    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update' && $menu['purchase_order']['edit']){
    $suppliers = $supplier_model->getSupplierBy();
    $users = $user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);
    $supplier = $supplier_model->getSupplierByCode($purchase_order['supplier_code']);
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'detail'){ 
    $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);
    if($supplier['vat_type'] == '0'){
        $vat= '0';
    }else{
        $vat = $purchase_order['vat'];
    }

    require_once($path.'detail.inc.php');
}else if ($_GET['action'] == 'delete' && $menu['purchase_order']['delete']){
    // $notification_model->deleteNotificationByTypeID('Purchase Order',$purchase_order_code);
    $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderCode($purchase_order_code);
    $purchase_orders = $purchase_order_model->deletePurchaseOrderByCode($purchase_order_code);
    ?>
    <script>
        window.location = "index.php?app=purchase_order"
    </script>
    <?php
}else if ($_GET['action'] == 'cancelled' && $menu['purchase_order']['cancel']){
    $purchase_order_model->cancelPurchaseOrderByCode($purchase_order_code);
    ?>
    <script>
        window.location = "index.php?app=purchase_order"
    </script>
    <?php
}else if ($_GET['action'] == 'uncancelled' && $menu['purchase_order']['cancel']){
    $purchase_order_model->uncancelPurchaseOrderByCode($purchase_order_code);
    ?>
    <script>
        window.location = "index.php?app=purchase_order"
    </script>
    <?php
}else if ($_GET['action'] == 'add' && $menu['purchase_order']['add']){
    if(isset($_POST['purchase_order_code'])){
        $data = [];
        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['purchase_order_remark'] = $_POST['purchase_order_remark'];
        $data['purchase_order_date'] = $date_time->changeDateFormat($_POST['purchase_order_date']);
        $data['purchase_order_total_price'] = (float)filter_var($_POST['purchase_order_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat'] = (float)filter_var($_POST['purchase_order_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat_type'] = $_POST['purchase_order_vat_type'];
        $data['purchase_order_vat_price'] = (float)filter_var($_POST['purchase_order_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net_price'] = (float)filter_var($_POST['purchase_order_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_revise_code'] = $_POST['purchase_order_code'];
        $data['addby'] = $login_user['user_code'];

        $result = $purchase_order_model->insertPurchaseOrder($data);

        if($result){
            for($i=0; $i<count($_POST['product_code']); $i++){
                $data = [];
                $data['purchase_order_list_code'] = $_POST['purchase_order_code'].date("Hi").$i;
                $data['purchase_order_code'] = $_POST['purchase_order_code'];
                $data['product_code'] = $_POST['product_code'][$i];
                $data['stock_group_code'] = $_POST['stock_group_code'][$i];
                $data['purchase_order_list_no'] = $i;
                $data['purchase_order_list_qty'] = (float)filter_var($_POST['purchase_order_list_qty'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
                $data['purchase_order_list_price'] = (float)filter_var($_POST['purchase_order_list_price'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
                $data['purchase_order_list_price_sum'] = (float)filter_var($_POST['purchase_order_list_price_sum'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
                $data['purchase_order_list_remark'] = $_POST['purchase_order_list_remark'][$i];
                $data['addby'] = $login_user['user_code'];

                $result = $purchase_order_list_model->insertPurchaseOrderList($data);
                if($result){
                    $purchase_request_list_model->updatePurchaseOrderCode($_POST['purchase_request_list_code'][$i],$data['purchase_order_list_code']);
                }
            }

            for($i=0; $i < count($_POST['save_product_price']); $i++){
                for($j=0; $j < count($_POST['product_code']); $j++){
                    if($_POST['product_code'][$j] == $_POST['save_product_price'][$i]){
                        $product_price = (float)filter_var($_POST['purchase_order_list_price'][$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    }
                }

                $product_supplier_price = $product_supplier_model->getProductSupplierPriceByCode($_POST['save_product_price'][$i],$_POST['supplier_code']);
    
                $data = [];
                $data['product_supplier_code'] = $product_supplier_price['product_supplier_code'];
                $data['product_code'] = $_POST['save_product_price'][$i];
                $data['supplier_code'] = $_POST['supplier_code'];
                $data['product_buyprice'] = $product_price;
    
                if($data['product_supplier_code'] != ''){ 
                    $data['updateby'] = $login_user['user_code'];
                    $product_supplier_model->updateProductSupplierPriceByCode($data);
                }else{
                    $data['product_supplier_code'] = $_POST['supplier_code'].$_POST['save_product_price'][$i];
                    $data['addby'] = $login_user['user_code'];
                    $product_supplier_model->insertProductSupplier($data);
                }
            }

            ?>
            <script>
                window.location = "index.php?app=purchase_order&action=update&code=<?php echo $_POST['purchase_order_code']; ?>"
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
}else if ($_GET['action'] == 'edit' && $menu['purchase_order']['edit']){
    if(isset($_POST['purchase_order_code'])){
        $purchase_order_list_model->deletePurchaseOrderListByPurchaseOrderCodeNotIN($_POST['purchase_order_code'],$_POST['purchase_order_list_code']);

        for($i=0; $i<count($_POST['product_code']); $i++){
            $data = [];
            $data['purchase_order_list_no'] = $i;
            $data['purchase_order_list_qty'] = (float)filter_var($_POST['purchase_order_list_qty'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
            $data['purchase_order_list_price'] = (float)filter_var($_POST['purchase_order_list_price'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
            $data['purchase_order_list_price_sum'] = (float)filter_var($_POST['purchase_order_list_price_sum'][$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
            $data['purchase_order_list_remark'] = $_POST['purchase_order_list_remark'][$i];

            if($_POST['purchase_order_list_code'][$i] != ''){
                $data['updateby'] = $login_user['user_code'];
                $result = $purchase_order_list_model->updatePurchaseOrderListByCodeAdmin($data,$_POST['purchase_order_list_code'][$i]);
            }else{
                $data['purchase_order_list_code'] = $_POST['purchase_order_code'].date("Hi").$i;
                $data['product_code'] = $_POST['product_code'][$i];
                $data['stock_group_code'] = $_POST['stock_group_code'][$i];
                $data['addby'] = $login_user['user_code'];

                $result = $purchase_order_list_model->insertPurchaseOrderList($data);
                if($result){
                    $purchase_request_list_model->updatePurchaseOrderCode($_POST['purchase_request_list_code'][$i],$data['purchase_order_list_code']);
                }
            }
        }

        for($i=0; $i < count($_POST['save_product_price']); $i++){
            for($j=0; $j < count($_POST['product_code']); $j++){
                if($_POST['product_code'][$j] == $_POST['save_product_price'][$i]){
                    $product_price = (float)filter_var($_POST['purchase_order_list_price'][$j], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
            }

            $product_supplier_price = $product_supplier_model->getProductSupplierPriceByCode($_POST['save_product_price'][$i],$_POST['supplier_code']);

            $data = [];
            $data['product_supplier_code'] = $product_supplier_price['product_supplier_code'];
            $data['product_code'] = $_POST['save_product_price'][$i];
            $data['supplier_code'] = $_POST['supplier_code'];
            $data['product_buyprice'] = $product_price;

            if($data['product_supplier_code'] != ''){ 
                $data['updateby'] = $login_user['user_code'];
                $product_supplier_model->updateProductSupplierPriceByCode($data);
            }else{
                $data['product_supplier_code'] = $_POST['supplier_code'].$_POST['save_product_price'][$i];
                $data['addby'] = $login_user['user_code'];
                $product_supplier_model->insertProductSupplier($data);
            }
        }

        $data['purchase_order_code'] = $_POST['purchase_order_code'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['purchase_order_category'] = $_POST['purchase_order_category'];
        $data['purchase_order_date'] = $_POST['purchase_order_date'];
        $data['purchase_order_credit_term'] = $_POST['purchase_order_credit_term'];
        $data['purchase_order_approve_by'] = $login_user['user_code'];

        if($_POST['purchase_order_approve_status'] == 'Approve'){
            $data['purchase_order_status'] = 'Approved';
        }else if($_POST['purchase_order_approve_status'] == 'Waitting'){
            $data['purchase_order_status'] = 'Request';
        }else {
            $data['purchase_order_status'] = 'New';
        }

        $data['purchase_order_delivery_by'] = $_POST['purchase_order_delivery_by'];
        $data['purchase_order_remark'] = $_POST['purchase_order_remark'];
        $data['purchase_order_total_price'] = (float)filter_var($_POST['purchase_order_remark'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat'] = (float)filter_var($_POST['purchase_order_vat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_vat_price'] = (float)filter_var($_POST['purchase_order_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['purchase_order_net_price'] = (float)filter_var($_POST['purchase_order_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['updateby'] = $login_user['user_code'];

        $result = $purchase_order_model->updatePurchaseOrderByCode($purchase_order_code,$data);
    
        if($result){ 
            ?>
            <script>
                window.location = "index.php?app=purchase_order&action=update&code=<?php echo $purchase_order_code;?>"
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
}else if ($_GET['action'] == 'revise' && ($menu['purchase_order']['edit'] || $menu['purchase_order']['add'])){
    if($purchase_order_code != ''){
        $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);
        $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);
        $result = $purchase_order_model->cancelPurchaseOrderByCode($purchase_order_code);  

        $purchase_order_code = $purchase_order['purchase_order_revise_code']."-REVISE-".($purchase_order['purchase_order_revise_no'] + 1);

        $data = [];
        $data['purchase_order_code'] = $purchase_order_code;
        $data['supplier_code'] = $purchase_order['supplier_code'];
        $data['employee_code'] = $purchase_order['employee_code'];
        $data['purchase_order_date'] = $purchase_order['purchase_order_date'];
        $data['purchase_order_credit_term'] = $purchase_order['purchase_order_credit_term'];
        $data['purchase_order_approve_status'] = '';
        $data['purchase_order_delivery_by'] = $purchase_order['purchase_order_delivery_by'];
        $data['purchase_order_remark'] = $purchase_order['purchase_order_remark'];
        $data['purchase_order_total_price'] = $purchase_order['purchase_order_total_price'];
        $data['purchase_order_vat'] = $purchase_order['purchase_order_vat'];
        $data['purchase_order_vat_type'] = $purchase_order['purchase_order_vat_type'];
        $data['purchase_order_vat_price'] = $purchase_order['purchase_order_vat_price'];
        $data['purchase_order_net_price'] = $purchase_order['purchase_order_net_price'];
        $data['purchase_order_revise_code'] = $purchase_order['purchase_order_revise_code']; 
        $data['purchase_order_revise_no'] = $purchase_order['purchase_order_revise_no'] + 1;
        $data['addby'] = $login_user['user_code'];

        $result = $purchase_order_model->insertPurchaseOrder($data);
            
        for($i=0; $i < count($purchase_order_lists) ; $i++){
            $data = [];
            $data['purchase_order_list_code'] = $purchase_order_code.date("Hi").$i;
            $data['purchase_order_list_no'] = $purchase_order_lists[$i]['purchase_order_list_no'];
            $data['purchase_order_code'] = $purchase_order_code;
            $data['product_code'] = $purchase_order_lists[$i]['product_code'];
            $data['stock_group_code'] = $purchase_order_lists[$i]['stock_group_code'];
            $data['purchase_order_list_qty'] = $purchase_order_lists[$i]['purchase_order_list_qty'];
            $data['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
            $data['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
            $data['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];
            $data['addby'] = $login_user['user_code'];

            $result = $purchase_order_list_model->insertPurchaseOrderList($data);

            if($result){
                if($purchase_order_lists[$i]['purchase_request_list_code'] != ""){
                    $purchase_request_list_model->updatePurchaseOrderCode($purchase_order_lists[$i]['purchase_request_list_code'],$data['purchase_order_list_code']);
                }
            }
        }
    
        ?>
        <script>
            window.location = "index.php?app=purchase_order&action=update&code=<?php echo $purchase_order_code;?>"
        </script>
        <?php
    }else{
    ?>
        <script>
            window.history.back();
        </script>
    <?php
    }
}else if ($_GET['action'] == 'approve' && $menu['purchase_order']['approve']){
    if(isset($_POST['purchase_order_approve_status'])){
        $data = [];
        $data['purchase_order_approve_status'] = $_POST['purchase_order_approve_status'];
        $data['purchase_order_approve_by'] = $login_user['user_code'];
        if($_POST['purchase_order_approve_status'] == 'Approve'){
            $data['purchase_order_status'] = 'Approved';
        }else if($_POST['purchase_order_approve_status'] == 'Waitting'){
            $data['purchase_order_status'] = 'Request';
        }else {
            $data['purchase_order_status'] = 'New';
        }
        
        $data['updateby'] = $login_user['user_code'];
        $result = $purchase_order_model->updatePurchaseOrderApproveByCode($purchase_order_code,$data);

        if($result){
            // $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);
            // $notification_model->setNotificationSeenByTypeID('Purchase Order',$purchase_order_code);
            // $notification_model->setNotificationByUserID("Purchase Order",$purchase_order_code,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&code = $purchase_order_code",$purchase_order['employee_code']);
            // $notification_model->setNotification("Purchase Order",$purchase_order_code,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&code = $purchase_order_code","license_purchase_page",'Medium');
            // $notification_model->setNotification("Purchase Order",$purchase_order_code,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&code = $purchase_order_code","license_purchase_page",'High');  
        
            ?>
            <script>
                window.location = "index.php?app=purchase_order&action=detail&code=<?php echo $purchase_order_code;?>"
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
}else if ($_GET['action'] == 'balance' && $menu['purchase_order']['edit']){
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_code);

    for($i=0; $i < count($purchase_order_lists) ; $i++){
        if($purchase_order_list_code > 0 ){
            if($purchase_order_list_code == $purchase_order_lists[$i]['purchase_order_list_code'] ){
                $data_sub = [];
                $data_sub['purchase_order_code'] = $purchase_order_code;
                $data_sub['product_code'] = $purchase_order_lists[$i]['product_code'];
                $data_sub['list_recieve_qty'] = $purchase_order_lists[$i]['list_recieve_qty'];
                $data_sub['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
                $data_sub['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                $data_sub['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];
                $purchase_order_list_model->updatePurchaseOrderListByCodeAdmin($data_sub,$purchase_order_lists[$i]['purchase_order_list_code']);
            }
        }else{
            $data_sub = [];
            $data_sub['purchase_order_code'] = $purchase_order_code;
            $data_sub['product_code'] = $purchase_order_lists[$i]['product_code'];
            $data_sub['list_recieve_qty'] = $purchase_order_lists[$i]['list_recieve_qty'];
            $data_sub['purchase_order_list_price'] = $purchase_order_lists[$i]['purchase_order_list_price'];
            $data_sub['purchase_order_list_price_sum'] = $purchase_order_lists[$i]['purchase_order_list_price_sum'];
            $data_sub['purchase_order_list_remark'] = $purchase_order_lists[$i]['purchase_order_list_remark'];
            $purchase_order_list_model->updatePurchaseOrderListByCodeAdmin($data_sub,$purchase_order_lists[$i]['purchase_order_list_code']);
        }
    } 
    ?>
    <script>
        window.location = "index.php?app=purchase_order&action=detail&code=<?php echo $purchase_order_code;?>"
    </script>
    <?php 
}else if ($_GET['action'] == 'sending' && $menu['purchase_order']['edit']){
    if(isset($purchase_order_code)){
        $company = $company_model->getCompanyBy(); 
        $purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);

        $supplier = $supplier_model->getSupplierByCode($supplier_code);
    
        if($supplier_code != ''){
            /******** setmail ********/
            require("controllers/mail/class.phpmailer.php");

            $mail = new PHPMailer();

            $body = '
                We are opened the purchase order No:'.$purchase_order['purchase_order_code'].' .
                Can you confirm the order details?. 
                At <a href="'.$company_model->supplier_page_url.'/index.php?app=purchase_order&action=sending&code='.$purchase_order_code.'">Click</a> 
                <br>
                <br>
                <b> Best regards,</b><br><br>
                <b> '.$user_admin['user_name'].' '.$user_admin['user_lastname'].'</b><br> 
                <b>Head Office :</b> '.$company['company_address_en_1'].' '.$company['company_address_en_2'].' <br>'.$company['company_address_en_3'].' 
                Tel.'.$company['company_tel'].' Fax. '.$company['company_fax'].' Tax. '.$company['company_tax'].'
            ';

            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Host = $company['company_email_smtp']; // SMTP server
            $mail->Port = $company['company_email_port']; 
            $mail->Username = $company['company_email_user']; // account SMTP
            $mail->Password = $company['company_email_password']; //  SMTP
            $mail->SetFrom( $company['company_email'], $company['company_name_en']);
            $mail->AddReplyTo( $company['company_email'],$company['company_name_en']);
            $mail->Subject = 'Purchase Order  No:'.$purchase_order['purchase_order_code'].' From: '.$company['company_name_en'];
            $mail->MsgHTML($body);
            $mailChecking = false;

            if($supplier['supplier_email'] !=""){
                $mail->AddCC($supplier['supplier_email'], "Supplier Mail"); //
                $mailChecking = true;
            }
            if($supplier['supplier_email_2'] !=""){
                $mail->AddCC($supplier['supplier_email_2'], "Supplier Mail 2"); //
                $mailChecking = true;
            }
            if($supplier['supplier_email_3'] !=""){
                $mail->AddCC($supplier['supplier_email_3'], "Supplier Mail 3"); //
                $mailChecking = true;
            }
                        
            if($mailChecking){
                if(!$mail->Send()) {
                    $result = "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    $data = [];
                    $data['purchase_order_status'] = 'Sending';
                    $data['updateby'] = $login_user['user_code'];
                    $purchase_order_model->updatePurchaseOrderStatusByCode($purchase_order_code,$data);
                    $result = "Send purchase order complete.";
                } 
            }else{
                $result = "กรุณาใส่ Email อย่างน้อย 1 ตัว";
            }
            
            ?>
            <script>
                alert("<?php echo $result; ?>");
                window.history.back();
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
}else if ($_GET['action'] == 'update_sending' && $menu['purchase_order']['edit']){
    $data = [];
    $data['purchase_order_status'] = 'Confirm';
    $result = $purchase_order_model->updatePurchaseOrderStatusByCode($purchase_order_code,$data);
    ?>
    <script>
        window.location = "index.php?app=purchase_order"
    </script>
    <?php
}else if ($_GET['action'] == 'cancel_sending' && $menu['purchase_order']['edit']){
    $data = [];
    $data['purchase_order_status'] = 'Approved';
    $result = $purchase_order_model->updatePurchaseOrderStatusByCode($purchase_order_code,$data);
    ?>
    <script>
        window.location = "index.php?app=purchase_order"
    </script>
    <?php
}else if ($_GET['action'] == 'request' && $menu['purchase_order']['edit']){
    if(isset($purchase_order_code)){
        $data = [];
        $data['purchase_order_approve_status'] = "Waitting";
        $data['purchase_order_approve_by'] = '';
        $data['purchase_order_status'] = 'Request';
        $data['updateby'] = $login_user['user_code'];
		$purchase_order = $purchase_order_model->getPurchaseOrderByCode($purchase_order_code);

        $result = $purchase_order_model->updatePurchaseOrderRequestByCode($purchase_order_code,$data);

        if($result){
            // $notification_model->setNotification("Purchase Order",$purchase_order_code,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&code = $purchase_order_code","license_manager_page",'High');
            // $notification_model->setNotification("Purchase Order",$purchase_order_code,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been request","index.php?app=purchase_order&action=detail&code = $purchase_order_code","license_purchase_page",'High');  
            ?>
            <script>
                alert("Send request complete.");
                window.history.back();
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

    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end = date('t-m-Y');
    }

    $suppliers = $supplier_model->getSupplierBy();
    $supplier_orders = $purchase_order_model->getSupplierOrder();
    $purchase_orders = $purchase_order_model->getPurchaseOrderBy($date_start,$date_end,$supplier_code,$keyword,$user_code);

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
    require_once($path.'view.inc.php');
}
?>