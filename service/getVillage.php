 
<?php

header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../models/AddressModel.php');  
date_default_timezone_set('asia/bangkok');

$AddressModel = new AddressModel;   
 
$json = file_get_contents('php://input');
    
$obj = json_decode($json,true);  

if (isset($obj['district']) ) {
 
    $data=[]; 
    $village = $AddressModel->getVillageByDistrictID($obj['district']);
    // echo '<pre>';
    // print_r($songserm);
    // echo '</pre>';
    if (count($village) > 0) {
        $data ['data'] = $village ;
        $data ['result'] = true;
    } else {
        $data ['result_text'] = 'district=='.$obj['district'];
        $data ['result'] = false ;
    }
} else {
    $data ['result_text'] = "error not var" ;
    $data ['result'] = false;
}
echo json_encode($data);

?>
