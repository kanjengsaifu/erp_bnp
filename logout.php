<?php 
session_start();
session_destroy();

setcookie('bnp_ips_user', null, -1, '/');

header('Location: index.php'); 
?>