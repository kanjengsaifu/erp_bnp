<?php 
require_once('../../../../models/ContractorModel.php');

$member_model = new ContractorModel;

$member = $member_model->getContractorByUserCode($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($member); $i++){
?>
<option value="<?php echo $member[$i]['code']?>"><?php echo $member[$i]['name']?></option>
<?
}
?>