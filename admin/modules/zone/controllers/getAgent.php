<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/AgentModel.php');

$agent_model = new AgentModel;

$agent = $agent_model->getAgentBy();

$data = [];

for($i=0; $i<count($agent); $i++){
    $data[] = array(
        "value" => $agent[$i]['agent_code'],
        "label" => $agent[$i]['agent_code'].' : '.$agent[$i]['agent_name']
    );
}

echo json_encode($data);
?>	