<?php  

require_once('../models/DealerModel.php');

$fund_agent_model = new DealerModel;

// echo '<pre>';
// print_r($fund_agent);
// echo '</pre>'; 
$data=[]; 
$fund_agent = $fund_agent_model->getDealerByUsername($_POST['code'],$_POST['username']);
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