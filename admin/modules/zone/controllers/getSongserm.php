<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/SongsermModel.php');

$songserm_model = new SongsermModel;

$songserm = $songserm_model->getSongsermBy();

$data = [];

for($i=0; $i<count($songserm); $i++){
    $data[] = array(
        "value" => $songserm[$i]['songserm_code'],
        "label" => $songserm[$i]['songserm_code'].' : '.$songserm[$i]['songserm_name']
    );
}

echo json_encode($data);
?>	