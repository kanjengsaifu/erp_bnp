<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/StockReportModel.php');
$stock_report_code = json_decode($_POST['stock_report_code'],true);

$stock_report_model = new StockReportModel;
$stock_report = $stock_report_model->generateStockReportBy($_POST['branch_code'],$stock_report_code ,$_POST['search']);
//  echo '<pre>';
//  print_r($stock_report);
//  echo '</pre>';
echo json_encode($stock_report);

?>