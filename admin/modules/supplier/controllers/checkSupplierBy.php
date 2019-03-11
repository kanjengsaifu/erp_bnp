<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/SupplierModel.php');

$supplier_model = new SupplierModel;
$supplier = $supplier_model->checkSupplierBy($_POST['supplier_code']);
// echo '<pre>';
// print_r($supplier);
// echo '</pre>';
echo json_encode($supplier);
?>