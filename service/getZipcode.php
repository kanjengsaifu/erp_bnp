<?php 
  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../models/AddressModel.php');  
date_default_timezone_set('asia/bangkok');

$AddressModel = new AddressModel;   
 
$json = file_get_contents('php://input');
    
$obj = json_decode($json,true);  

if (isset($obj['POSTCODE']) ) {
 
    $data=[]; 
    $amphur = $AddressModel->getZipcodeByAmphurID($obj['POSTCODE']);
    // echo '<pre>';
    // print_r($amphur);
    // echo '</pre>';
    if (count($amphur) > 0) {
        $data ['data'] = $amphur ;
        $data ['result'] = true;
    } else {
        $data ['result_text'] = 'POSTCODE=='.$obj['POSTCODE'];
        $data ['result'] = false ;
    }
} else {
    $data ['result_text'] = "error not var" ;
    $data ['result'] = false;
}
echo json_encode($data);
?>