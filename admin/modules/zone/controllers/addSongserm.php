<?php
require_once('../../../../models/ZoneSongsermModel.php');

$zone_songserm_model = new ZoneSongsermModel;

$songserm = json_decode($_POST['songserm'], True);

$login_user = unserialize($_COOKIE['bnp_ips_user']);

for($i=0; $i<count($songserm); $i++){
    $data = [];
    $data['zone_songserm_code'] = $_POST['zone_code'].'-'.$songserm[$i]['songserm_code'];
    $data['zone_code'] = $_POST['zone_code'];
    $data['songserm_code'] = $songserm[$i]['songserm_code'];
    $data['addby'] = $login_user['user_code'];

    $result = $zone_songserm_model->insertZoneSongserm($data);
}

echo $result;
?>