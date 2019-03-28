<?php 
date_default_timezone_set('asia/bangkok');

require_once('../../../../models/PurchaseOrderModel.php');
require_once('../../../../models/SupplierModel.php');
require_once('../../../../models/PaperModel.php');
require_once('../../../../models/UserModel.php');

require_once('../../../../functions/CodeGenerateFunction.func.php');

$purchase_order_model = new PurchaseOrderModel;
$supplier_model = new SupplierModel;
$paper_model = new PaperModel;
$user_model = new UserModel;
$code_generate = new CodeGenerate;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByCode('11');

$supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);
$user = $user_model->getUserByCode($_POST['employee_code']);
        
$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name_en"];

$code = $code_generate->cut2Array($paper['paper_code'],$data);

$last_code = "";

for($i = 0 ; $i < count($code); $i++){
    if($code[$i]['type'] == "number"){
        $last_code = $purchase_order_model->getPurchaseOrderLastCode($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
} 

echo $last_code;
?>