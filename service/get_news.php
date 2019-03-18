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
if($get_data == "getall"){


	$Sql_Query = "
	SELECT 
	tb_news_category.news_cat_name,
	tb_news.news_id,
	tb_news.news_title,
	tb_news.news_detail,
	tb_news.news_img_1 
	FROM tb_news_category,
	tb_news 
	WHERE tb_news_category.news_cat_id=tb_news.news_cat_id 
	order by tb_news.news_id DESC";


	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else{
	$Sql_Query = "
	SELECT 
	tb_news_category.news_cat_name,
	tb_news.news_id,
	tb_news.news_title,
	tb_news.news_detail,
	tb_news.news_img_1,
	tb_news.news_img_2,
	tb_news.news_img_3,
	tb_news.news_img_4,
	tb_news.date_add 
	FROM tb_news_category,tb_news 
	WHERE tb_news_category.news_cat_id=tb_news.news_cat_id 
	AND tb_news.news_id = '$get_data'";
	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}

}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

?>