<?php  

require_once('../models/AgentModel.php');

$AgentModel = new AgentModel;

// echo '<pre>';
// print_r($fund_agent);
// echo '</pre>'; 
$data=[]; 
$fund_agent = $AgentModel->getAgentByUsername($_POST['code'],$_POST['username']);
// echo '<pre>';
// print_r($songserm);
// echo '</pre>';
if (count($fund_agent) > 0) { 
    $data ['result'] = true;
} else { 
    $data ['result'] = false ;
}

echo json_encode($data);
?>