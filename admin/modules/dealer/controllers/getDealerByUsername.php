<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/DealerModel.php');

$fund_agent_model = new DealerModel;

$fund_agent = $fund_agent_model->getDealerByUsername($_POST['code'],$_POST['username']);

echo json_encode($fund_agent);
?>