<?php
date_default_timezone_set('Asia/Bangkok');

session_start();
 
require_once('../models/UserModel.php');  

$user_model = new UserModel;  

if(isset($_COOKIE['bnp_ips_user'])){
    $login_user_data = unserialize($_COOKIE['bnp_ips_user']);
    if($login_user_data['user_username'] != '' && $login_user_data['user_password'] != ''){
        $login_user = $user_model->getLogin($login_user_data['user_username'],$login_user_data['user_password']); 
        if ($login_user['user_status_code'] == "US001"){
            setcookie("bnp_ips_user", serialize($login_user), time() + (86400 * 30), "/"); // 86400 = 1 day   
        }else{
            setcookie('bnp_ips_user', null, -1, '/');
            ?> <script> window.location = "../index.php" </script> <?PHP 
        }
    }else{
        setcookie('bnp_ips_user', null, -1, '/');
        ?> <script> window.location = "../index.php" </script> <?PHP 
    }
}else{
?> <script> window.location = "../index.php" </script> <?PHP 
} 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once('views/header.inc.php') ?>
    </head>
    <body>
        <div id="loadScreen">
            <div id="loader-text" style="color:black;" ><div align="center" style="margin-top:50px;">Loading...</div></div> 
            <div id="loader"></div>
        </div>

        <div id="wrapper" style="display:none;"> 
            <?php require_once("views/menu.inc.php"); ?> 
            <div id="page-wrapper" style="min-height: calc(100vh - 51px);">
            <?php require_once("views/body.inc.php"); ?>
            </div> 
        </div> 
        <?php require_once('views/footer.inc.php'); ?>
    </body>
</html>