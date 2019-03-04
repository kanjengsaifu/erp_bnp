<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/MaterialModel.php');
$keyword = $_GET['keyword'];

$material_model = new MaterialModel;

$material = $material_model->getMaterialBy($supplier_code,$keyword );

echo json_encode($material);

?>