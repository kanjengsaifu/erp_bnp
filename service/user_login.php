<?php
header('Content-Type: text/html; charset=utf-8');
// Importing DBConfig.php file.
include 'db_config.php';

 // Getting the received JSON into $json variable.
$json = file_get_contents('php://input');

 // decoding the received JSON and store into $obj variable.
$obj = json_decode($json,true);

// Populate User email from JSON $obj array and store into $email.


// Populate Password from JSON $obj array and store into $password.

$action = $obj['action'];

//Applying User Login query with email and password match.
if($action == "login"){

	$email = $obj['email'];
	$password = $obj['password'];

	$email = mysql_real_escape_string($email);
	$password = mysql_real_escape_string($password);
	
	$Sql_Query = "SELECT * 
	FROM tb_songserm 
	WHERE songserm_username = '$email' 
	AND songserm_password = '$password'";

	$check = mysql_query($Sql_Query);


	if($check){

		$rows = array();
		while($r = mysql_fetch_assoc($check)) {

			$rows[] = $r;

		}
		echo json_encode($rows, JSON_UNESCAPED_UNICODE);
	}

}

else if($action == "line"){
	$line = $obj['line_id'];
	$Sql_Query = "SELECT * 
	FROM tb_member 
	WHERE member_line_id = '$line'";
	$check = mysql_query($Sql_Query);
	if($check){

		$rows = array();
		while($r = mysql_fetch_assoc($check)) {

			$rows[] = $r;

		}
		echo json_encode($rows, JSON_UNESCAPED_UNICODE);
	}

}

else if($action == "syn"){
	$id = $obj['user_id'];
	$line = $obj['line_id'];
	$Sql_Query = "UPDATE tb_member 
	SET member_line_id = '$line' 
	WHERE member_id = '$id';";
	$check = mysql_query($Sql_Query);
	if($check){
		$row = "ok";
	}
	echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}


?>