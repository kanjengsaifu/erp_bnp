<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/MaterialModel.php');
$model_material = new MaterialModel;
$material = $model_material->getMaterialByCode($_POST['material_code']);

echo json_encode($material);
?>