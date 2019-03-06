<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/SupplierModel.php');
$model_supplier = new SupplierModel;
$supplier = $model_supplier->getSupplierByCode($_POST['supplier_code']);

echo json_encode($supplier);
?>