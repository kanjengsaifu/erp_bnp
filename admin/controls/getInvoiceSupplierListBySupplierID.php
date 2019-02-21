<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$purchase_order_list_code = json_decode($_POST['purchase_order_list_code'],true);

$invoice_supplier_model = new InvoiceSupplierModel;
$supplier = $invoice_supplier_model->generateInvoiceSupplierListBySupplierId($_POST['supplier_code'],$purchase_order_list_code ,$_POST['search'],$_POST['purchase_order_code']);
//  echo '<pre>';
//  print_r($supplier);
//  echo '</pre>';
echo json_encode($supplier);

?>