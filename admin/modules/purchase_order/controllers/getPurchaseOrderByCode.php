<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/PurchaseOrderModel.php');

$purchase_order_model = new PurchaseOrderModel;

$purchase_order = $purchase_order_model->getPurchaseOrderByCode($_POST['code']);

echo json_encode($purchase_order);
?>