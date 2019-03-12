<?php
require_once('../../../../models/ZoneCallCenterModel.php');

$zone_call_center_model = new ZoneCallCenterModel;

$user = json_decode($_POST['user'], True);

$login_user = unserialize($_COOKIE['bnp_ips_user']);

for($i=0; $i<count($user); $i++){
    $data = [];
    $data['zone_call_center_code'] = $_POST['zone_code'].'-'.$user[$i]['user_code'];
    $data['zone_code'] = $_POST['zone_code'];
    $data['user_code'] = $user[$i]['user_code'];
    $data['addby'] = $login_user['user_code'];

    $result = $zone_call_center_model->insertZoneCallCenter($data);
}

echo $result;
?>