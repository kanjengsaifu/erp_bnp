<?php 
require_once('../../../../models/FundAgentModel.php');

$member_model = new FundAgentModel;

$member = $member_model->getFundAgentByUserCode($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($member); $i++){
?>
<option value="<?php echo $member[$i]['code']?>"><?php echo $member[$i]['name']?></option>
<?
}
?>