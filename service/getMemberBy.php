<?php

header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../models/MemberModel.php');  
date_default_timezone_set('asia/bangkok');

$MemberModel = new MemberModel;   
 
$json = file_get_contents('php://input');
    
$obj = json_decode($json,true);  

if (isset($obj['user_code']) ) {
 
    $data=[]; 
    $member = $MemberModel->getMemberBy($obj['user_code'],$obj['member_type'],$obj['keyword']);
    // echo '<pre>';
    // print_r($songserm);
    // echo '</pre>';
    if (count($member) > 0) {
        $data ['data'] = $member ;
        $data ['result'] = true;
    } else {
        $data ['result_text'] = 'user_code=='.$obj['user_code'].'|||member_type=='.$obj['member_type'];
        $data ['result'] = false ;
    }
} else {
    $data ['result_text'] = "error not var" ;
    $data ['result'] = false;
}
echo json_encode($data);

?>
