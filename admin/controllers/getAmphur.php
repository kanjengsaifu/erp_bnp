<?php 
require_once('../../models/AddressModel.php');

$address_model = new AddressModel;

$amphur = $address_model->getAmphurByProviceID($_POST['province']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($amphur); $i++){
?>
<option value="<?php echo $amphur[$i]['AMPHUR_ID']?>"><?php echo $amphur[$i]['AMPHUR_NAME']?></option>
<?
}
?>