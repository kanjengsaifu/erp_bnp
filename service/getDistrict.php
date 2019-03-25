 
<?php

header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../models/AddressModel.php');  
date_default_timezone_set('asia/bangkok');

$AddressModel = new AddressModel;   
 
$json = file_get_contents('php://input');
    
$obj = json_decode($json,true);  

if (isset($obj['amphur']) ) {
 
    $data=[]; 
    $district = $AddressModel->getDistrictByAmphurID($obj['amphur']);
    // echo '<pre>';
    // print_r($songserm);
    // echo '</pre>';
    if (count($district) > 0) {
        $data ['data'] = $district ;
        $data ['result'] = true;
    } else {
        $data ['result_text'] = 'amphur=='.$obj['amphur'];
        $data ['result'] = false ;
    }
} else {
    $data ['result_text'] = "error not var" ;
    $data ['result'] = false;
}
echo json_encode($data);

?>
