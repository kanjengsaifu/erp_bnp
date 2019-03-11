<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/ProductModel.php');

$product_model = new ProductModel;
$product = $product_model->checkProductBy($_POST['product_code']);
// echo '<pre>';
// print_r($product);
// echo '</pre>';
echo json_encode($product);
?>