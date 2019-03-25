<?php 
require_once('../../models/UserModel.php');

$user_model = new UserModel;

$user = $user_model->getUserBy();
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($user); $i++){
?>
<option value="<?php echo $user[$i]['user_code']?>"><?php echo $user[$i]['name']?></option>
<?
}
?>