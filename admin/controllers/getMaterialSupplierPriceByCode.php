<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/MaterialSupplierModel.php');
$model_material = new MaterialSupplierModel;
$material = $model_material->getMaterialSupplierPriceByCode($_POST['material_code'],$_POST['supplier_code']);

echo json_encode($material);
?>