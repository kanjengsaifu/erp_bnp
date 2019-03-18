<?php 
require_once('../../models/AddressModel.php');

$address_model = new AddressModel;

$village = $address_model->getVillageByDistrictID($_POST['district']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($village); $i++){
?>
<option value="<?php echo $village[$i]['VILLAGE_ID']?>"><?php echo $village[$i]['VILLAGE_NAME']?></option>
<?
}
?>