<?php 
require_once('../../../../models/ContractorModel.php');

$contractor_model = new ContractorModel;

$member = $contractor_model->getContractorByUserCode($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($member); $i++){
?>
<option value="<?php echo $member[$i]['code']?>"><?php echo $member[$i]['name']?></option>
<?
}
?>