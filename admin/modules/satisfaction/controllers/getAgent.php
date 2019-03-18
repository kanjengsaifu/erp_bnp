<?php 
require_once('../../../../models/AgentModel.php');

$member_model = new AgentModel;

$member = $member_model->getAgentByUserCode($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($member); $i++){
?>
<option value="<?php echo $member[$i]['code']?>"><?php echo $member[$i]['name']?></option>
<?
}
?>