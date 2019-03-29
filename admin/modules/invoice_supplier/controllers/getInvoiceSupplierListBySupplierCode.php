<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/InvoiceSupplierModel.php');

$invoice_supplier_model = new InvoiceSupplierModel;

$purchase_order_list_code = json_decode($_POST['purchase_order_list_code'],true);
$list_qty = json_decode($_POST['list_qty'],true);

$invoice_supplier_code = $_POST['invoice_supplier_code'];

$supplier = $invoice_supplier_model->generateInvoiceSupplierListBySupplierCode(
    $_POST['supplier_code'],
    $purchase_order_list_code ,
    $list_qty,
    $_POST['search'],
    '',
    $invoice_supplier_code
);

echo json_encode($supplier);
?>