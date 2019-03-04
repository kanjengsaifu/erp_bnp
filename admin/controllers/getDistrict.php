<?php 
require_once('../../models/AddressModel.php');

$address_model = new AddressModel;

$district = $address_model->getDistricByAmphurID($_POST['amphur']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($district); $i++){
?>
<option value="<?php echo $district[$i]['DISTRICT_ID']?>"><?php echo $district[$i]['DISTRICT_NAME']?></option>
<?
}
?>