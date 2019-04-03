<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/InvoiceSupplierModel.php');

$invoice_supplier_model = new InvoiceSupplierModel;

$purchase = $invoice_supplier_model->getConfirmPurchaseOrderBy($_GET['keyword']);

echo json_encode($purchase);

?>