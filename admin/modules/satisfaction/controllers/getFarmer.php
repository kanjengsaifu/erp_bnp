<?php 
require_once('../../../../models/FarmerModel.php');

$member_model = new FarmerModel;

$member = $member_model->getFarmerByUserCode($_POST['user_code']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($member); $i++){
?>
<option value="<?php echo $member[$i]['code']?>"><?php echo $member[$i]['name']?></option>
<?
}
?>