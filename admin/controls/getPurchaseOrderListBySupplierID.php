<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/MaterialSupplierModel.php'); 

$material_supplier_model = new MaterialSupplierModel;
$supplier = $material_supplier_model->getMaterialSupplierBySupplierCode(
    $_POST['supplier_code'],
    $_POST['search']
    );
echo json_encode($supplier);

?>