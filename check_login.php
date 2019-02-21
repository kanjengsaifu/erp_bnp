<?php
require_once('models/UserModel.php');

if(isset($_POST['username']) && isset($_POST['password'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    echo "<script language=\"JavaScript\" type=\"text/javascript\"> window.parent.refresh();</script>";

    // $model = new UserModel;
    
    // $user = $model->getLogin($user,$pass);

    // if($user['user_code'] != ''){

    //     setcookie("bnp_ips_user", serialize($user), time() + (86400 * 30), "/"); // 86400 = 1 day   

    //     echo "<script language=\"JavaScript\" type=\"text/javascript\"> window.parent.refresh();</script>";
    // }else{
    //     echo "<script language=\"JavaScript\" type=\"text/javascript\"> window.parent.error();</script>";
    // }
}else{
    header("Location index.php");
}
?>