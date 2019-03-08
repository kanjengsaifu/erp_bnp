<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/ZoneModel.php');

$zone_model = new ZoneModel;

$zone = $zone_model->getZoneByCode($_POST['zone_code']);

echo json_encode($zone);
?>