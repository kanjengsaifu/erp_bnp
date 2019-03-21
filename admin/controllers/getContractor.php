<?php 
require_once('../../models/ContractorModel.php');

$contractor_model = new ContractorModel;

$contractor = $contractor_model->getContractorBy($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($contractor); $i++){
?>
<option value="<?php echo $contractor[$i]['contractor_code']?>"><?php echo $contractor[$i]['name']?></option>
<?
}
?>