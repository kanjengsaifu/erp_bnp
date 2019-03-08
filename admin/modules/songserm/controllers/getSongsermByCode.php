<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/SongsermModel.php');

$songserm_model = new SongsermModel;

$songserm = $songserm_model->getSongsermByCode($_POST['songserm_code']);

echo json_encode($songserm);
?>