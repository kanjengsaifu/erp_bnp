<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/ProductSupplierModel.php');

$product_supplier_model = new ProductSupplierModel;

$product = $product_supplier_model->getProductBySupplierCode($_POST['supplier_code']);

echo json_encode($product);
?>