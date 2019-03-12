<?php 
require_once('../../../../models/AgentModel.php');

$agent_model = new AgentModel;

$agent = $agent_model->getAgentByDistrict($_POST['district']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($agent); $i++){
?>
<option value="<?php echo $agent[$i]['agent_code']?>"><?php echo $agent[$i]['name']?></option>
<?
}
?>