<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/ContractorModel.php');

$contractor_model = new ContractorModel;

$contractor = $contractor_model->getContractorByCode($_POST['contractor_code']);

echo json_encode($contractor);
?>