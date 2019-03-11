<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/FundAgentModel.php');

$fund_agent_model = new FundAgentModel;

$fund_agent = $fund_agent_model->getFundAgentByUsername($_POST['code'],$_POST['username']);

echo json_encode($fund_agent);
?>