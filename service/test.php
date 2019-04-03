<?php

header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../models/MemberModel.php');  
date_default_timezone_set('asia/bangkok');

$MemberModel = new MemberModel;   
 
$json = file_get_contents('php://input');
    
$obj = json_decode($json,true);  
 
    
echo json_encode($obj);

?>
