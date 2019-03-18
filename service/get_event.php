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
$get_data = $obj['data'];;
$year = $obj['year'];
$month = $obj['month'];
$day = $obj['day'];

if($get_data == "getevent"){

	$dataStart = $year."-".$month."-".$day." ";
	$dataEnd = $year."-".$month."-".$day." ";
	$Sql_Query = "SELECT * FROM `tb_event` WHERE event_date >= '$dataStart' AND event_date <= '$dataEnd' AND event_show = 1";
	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if ($get_data=="getmarker"){
	$monthEnd = (int)$month +1;
	$dateStart = $year."-".$month."-"."01";
	$dateEnd = $year."-".strval($monthEnd)."-"."01";
	$Sql_Query = "SELECT DISTINCT DATE_FORMAT(event_date,'%Y-%m-%d') 
	AS marker 
	FROM tb_event 
	WHERE event_date 
	BETWEEN '$dateStart' 
	AND '$dateEnd' 
	AND event_show = 1";

	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else {
	$id = $obj['data'];
	$Sql_Query = "SELECT 
	tb_event.event_title,
	tb_event.event_detail,
	tb_event.event_date,
	tb_event.event_location,
	tb_event.event_image_1,  
	tb_event.event_image_2,  
	tb_event.event_image_3,  
	tb_event.event_image_4,
	tb_event.event_time_start,
	tb_event.event_time_end   
	FROM tb_event 
	WHERE tb_event.event_id = '$id' 
	AND tb_event.event_show=1";

	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {
		$rows[] = $r;
	}

}

echo json_encode($rows, JSON_UNESCAPED_UNICODE);

?>