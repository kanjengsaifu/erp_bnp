<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/PurchaseOrderModel.php');

$purchase_request_list_code = json_decode($_POST['purchase_request_list_code'],true);

$purchase_order_model = new PurchaseOrderModel;

$supplier = $purchase_order_model->generatePurchaseOrderListBySupplierCode(
        $_POST['supplier_code'],
        $purchase_request_list_code, 
        $_POST['search']
    );

echo json_encode($supplier);
?>