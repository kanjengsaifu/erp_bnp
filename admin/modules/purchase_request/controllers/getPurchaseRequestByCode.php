<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/PurchaseRequestModel.php');

$purchase_request_model = new PurchaseRequestModel;

$purchase_request = $purchase_request_model->getPurchaseRequestByCode($_POST['code']);

echo json_encode($purchase_request);
?>