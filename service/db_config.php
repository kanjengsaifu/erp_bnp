<?php


// $host="localhost";
// $username="revelsof_hkk";
// $password="root123456";
// $db="revelsof_hkk";
 
$host="192.168.0.131";
$username="admin";
$password="123456";
$db="revelsoft_erp_bnp";

// $con=mysql_connect("$host","$username","$password");
// $con_db=mysql_select_db("$db");
$objConnect = mysql_connect($host,$username,$password);
if($objConnect)
{

	$objDB = mysql_select_db($db);
}
else
{

}

mysql_query("SET character_set_results=utf8");
mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");


?>