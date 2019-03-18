<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once ('../models/SongsermModel.php');
session_start();
 
$json = file_get_contents('php://input');
 
$obj = json_decode($json,true);
 
 

if (isset($obj['songserm_username']) && isset($obj['songserm_password'])) {
 
    $data=[];
    $model = new SongsermModel;
    $songserm = $model->getSongsermLogin($obj['songserm_username'],$obj['songserm_password']);
    // echo '<pre>';
    // print_r($songserm);
    // echo '</pre>';
    if (count($songserm) > 0) {
        $data ['data'] = $songserm ;
        $data ['result'] = true;
    } else {
        $data ['result'] = false ;
    }
} else {
   $data ['result'] = false;
}


echo json_encode($data);

?>