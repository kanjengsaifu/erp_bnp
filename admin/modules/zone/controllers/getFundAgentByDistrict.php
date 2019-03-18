<?php 
require_once('../../../../models/FundAgentModel.php');

$fund_agent_model = new FundAgentModel;

$fund_agent = $fund_agent_model->getFundAgentByDistrict($_POST['district']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($fund_agent); $i++){
?>
<option value="<?php echo $fund_agent[$i]['fund_agent_code']?>"><?php echo $fund_agent[$i]['name']?></option>
<?
}
?>