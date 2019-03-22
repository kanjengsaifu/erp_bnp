<?php
require_once('../models/StockListModel.php');
require_once('../models/StockGroupModel.php');

$path = "modules/search_product/views/";

$stock_list_model = new StockListModel;
$stock_group_model = new StockGroupModel;

$stock_group_code = $_GET['code'];
$keyword = $_GET['keyword'];

if($_GET['page'] == '' || $_GET['page'] == '0'){
    $page = 0;
}else{
    $page = $_GET['page'] - 1;
}

$page_size = 100;

$stock_group = $stock_group_model->getStockGroupBy();

if(isset($_GET['keyword']) || isset($_GET['code']))
    $stock_list = $stock_list_model->getStockListBy($stock_group_code,$keyword);

$page_max = (int)(count($stock_list)/$page_size);
if(count($stock_list)%$page_size > 0){
    $page_max += 1;
}

require_once($path.'view.inc.php');
?>