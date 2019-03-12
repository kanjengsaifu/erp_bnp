<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/FarmerModel.php');

$farmer_model = new FarmerModel;

$farmer = $farmer_model->getFarmerByCode($_POST['farmer_code']);

echo json_encode($farmer);
?>