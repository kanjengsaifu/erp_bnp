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
	SELECT * FROM 
	tb_shop_category 
	WHERE shop_cat_show = 1  
	order by shop_cat_id ASC";

	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}else if($get_data =="getshop"){
	$cat_id = $obj['cat_id'];
	$Sql_Query = "
	SELECT * 
	FROM tb_shop 
	WHERE shop_cat_id='$cat_id' 
	AND shop_show  = 1  
	ORDER BY shop_id DESC";
	$check = mysql_query($Sql_Query);
	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data == "getshopimg"){
	$shop_id = $obj['shop_id'];
	$Sql_Query = "
	SELECT * 
	FROM tb_shop_slide 
	WHERE slide_shop_id='$shop_id' 
	AND slide_view  = 1  
	ORDER BY slide_id ASC";
	$check = mysql_query($Sql_Query);
	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data =="getshopdetail"){
	$shop_id = $obj['shop_id'];
	$Sql_Query = "
	SELECT * 
	FROM tb_shop 
	WHERE shop_id='$shop_id' 
	AND shop_show  = 1  
	ORDER BY shop_id ASC";
	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data =="getshoppromotion"){
	$shop_id = $obj['shop_id'];
	$sql = "SELECT * , 
	ifnull(
	(
	SELECT count(*) 
	FROM tb_use_promotion 
	WHERE promotion_id = tb.promotion_id
	)
	,0) AS use_count 
	FROM tb_promotion 
	AS tb 
	LEFT JOIN tb_shop_promotion 
	ON tb.promotion_id = tb_shop_promotion.promotion_id 
	WHERE tb_shop_promotion.shop_id = '$shop_id' 
	AND tb.promotion_show = 1
	AND DATE_ADD(tb.promotion_end, INTERVAL 1 DAY) >= NOW()";

	$check = mysql_query($sql);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data =="getpromotion"){
	$promotion_id = $obj['promotion_id'];
	$sql = "SELECT * 
	from tb_promotion 
	WHERE promotion_id = '$promotion_id'" ;

	$check = mysql_query($sql);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}else if($get_data =="usepromotion"){
	$shop_id = $obj['shop_id'];
	$promotion_id = $obj['promotion_id'];
	$user_id = $obj['user_id'];
	$sql = "INSERT 
	INTO tb_use_promotion (shop_id,promotion_id,member_id,date_use) 
	VALUES ('$shop_id','$promotion_id','$user_id',NOW())";
	$check = mysql_query($sql);
	if($check){

		$rows = "Complete";
	}
	else {"Error";}

}else if($get_data =="getnear"){
	$cat_id = $obj['cat_id'];
	$lat = $obj['lat'];
	$lon = $obj['lon'];
	$Sql_Query = "
	SELECT * ,
	(SELECT 
	haversine(
	tb_shop.shop_location_lat,
	tb_shop.shop_location_long,
	'$lat',
	'$lon')
	) 
	AS distance
	FROM tb_shop 
	WHERE tb_shop.shop_cat_id='$cat_id' 
	AND shop_show  = 1  
	ORDER BY distance ASC";
	$check = mysql_query($Sql_Query);
	// $row =$Sql_Query;
	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data =="getpop"){
	$cat_id = $obj['cat_id'];
	$Sql_Query = "
	SELECT *,
	ifnull((
	SELECT COUNT(*) 
	FROM `tb_use_promotion`  
	WHERE tb_use_promotion.shop_id = tb_shop.shop_id
	GROUP BY shop_id  
	ORDER BY COUNT(*)),0) 
	AS use_count
	FROM tb_shop 
	WHERE tb_shop.shop_cat_id = '$cat_id'  
	ORDER BY `use_count` DESC";
	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}
}
else if($get_data == "search"){
	$keyword = $obj['keyword'];
	$Sql_Query = "
	SELECT *
	FROM tb_shop 
	LEFT JOIN tb_shop_category 
	ON tb_shop.shop_cat_id = tb_shop_category.shop_cat_id
	WHERE tb_shop.shop_name LIKE '%$keyword%' 
	OR tb_shop.shop_detail LIKE '%$keyword%' 
	OR tb_shop.shop_province LIKE '%$keyword%' 
	OR tb_shop.shop_amphur LIKE '%$keyword%' 
	OR tb_shop.shop_district LIKE '%$keyword%' 
	OR tb_shop.shop_phone LIKE '%$keyword%' 
	AND tb_shop.shop_show = 1";
	$check = mysql_query($Sql_Query);

	$rows = array();
	while($r = mysql_fetch_assoc($check)) {

		$rows[] = $r;

	}

}


echo json_encode($rows, JSON_UNESCAPED_UNICODE);

?>