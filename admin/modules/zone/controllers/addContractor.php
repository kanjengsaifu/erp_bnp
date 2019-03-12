<?php
require_once('../../../../models/ZoneContractorModel.php');

$zone_contractor_model = new ZoneContractorModel;

$contractor = json_decode($_POST['contractor'], True);

$login_user = unserialize($_COOKIE['bnp_ips_user']);

for($i=0; $i<count($contractor); $i++){
    $data = [];
    $data['zone_contractor_code'] = $_POST['zone_code'].'-'.$contractor[$i]['contractor_code'];
    $data['zone_code'] = $_POST['zone_code'];
    $data['contractor_code'] = $contractor[$i]['contractor_code'];
    $data['addby'] = $login_user['user_code'];

    $result = $zone_contractor_model->insertZoneContractor($data);
}

echo $result;
?>