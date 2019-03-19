<?php 
require_once('../../../../models/DealerModel.php');

$dealer_model = new DealerModel;

$dealer = $dealer_model->getDealerByDistrict($_POST['district']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($dealer); $i++){
?>
<option value="<?php echo $dealer[$i]['dealer_code']?>"><?php echo $dealer[$i]['name']?></option>
<?
}
?>