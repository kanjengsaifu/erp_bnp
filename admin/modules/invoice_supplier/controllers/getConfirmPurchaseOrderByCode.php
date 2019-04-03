<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../../../models/InvoiceSupplierModel.php');

$invoice_supplier_model = new InvoiceSupplierModel;

$purchase_order = $invoice_supplier_model->getConfirmPurchaseOrderByCode($_POST['purchase_order_code']);

echo json_encode($purchase_order);

?>