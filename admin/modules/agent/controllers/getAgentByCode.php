<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/AgentModel.php');

$agent_model = new AgentModel;

$agent = $agent_model->getAgentByCode($_POST['code']);

echo json_encode($agent);
?>