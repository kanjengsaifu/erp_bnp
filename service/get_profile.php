<?php


header('Content-Type: text/html; charset=utf-8');
// Importing DBConfig.php file.
include 'db_config.php';

// Creating connection.


 // Getting the received JSON into $json variable.
$json = file_get_contents('php://input');

 // decoding the received JSON and store into $obj variable.
$obj = json_decode($json,true);

// Populate User email from JSON $obj array and store into $email.
$get_data = $obj['data'];
$user_id = $obj['user_id'];

if($get_data == "getprofile"){

	$Sql_Query = " SELECT * ,
	(SELECT DATEDIFF( tb_expire.date_expire,now())  ) AS exp 
	FROM tb_member
	LEFT JOIN tb_member_type ON tb_member.member_type_id = tb_member_type.member_type_id 
	LEFT JOIN tb_expire ON tb_member.expire_id = tb_expire.expire_id 
	WHERE tb_member.member_id = '$user_id' ";

	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data == "updateprofile"){


	$password = $obj['password'];

	$Sql_Query = "UPDATE tb_member 
	SET 
	member_password = '$password' 
	WHERE member_id = '$user_id'";
	$check = mysql_query($Sql_Query);

}

else if($get_data == "sendfeedback"){
	$detail = $obj['detail'];

	$Sql_Query = "INSERT INTO tb_suggestion 
	(member_id,suggestion_detail,suggestion_read,date_add) 
	VALUES 
	('$user_id','$detail','0',NOW())";
	$check = mysql_query($Sql_Query);

}

echo json_encode($rows, JSON_UNESCAPED_UNICODE);

?>