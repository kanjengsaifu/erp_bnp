<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/UserModel.php');
$user_model = new UserModel;
$user = $user_model->getUserByCode($_POST['user_code']);
// echo '<pre>';
// print_r($user);
// echo '</pre>';
echo json_encode($user);
?>