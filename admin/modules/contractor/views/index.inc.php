<?php
require_once('../models/ContractorModel.php');
require_once('../models/ContractorStatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/contractor/views/";

$contractor_model = new ContractorModel;
$contractor_status_model = new ContractorStatusModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../img_upload/contractor/";
$contractor_code = $_GET['code'];

if ($_GET['action'] == 'insert'&&$menu['contractor']['add']!=1){ 
    $contractor_status = $contractor_status_model->getContractorStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['contractor']['edit']==1){
    $contractor = $contractor_model->getContractorByID($contractor_code);
    $contractor_position = $contractor_position_model->getContractorPositionBy();
    $contractor_status = $contractor_status_model->getContractorStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($contractor['province_id']);
    $add_district = $address_model->getDistricByAmphurID($contractor['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['contractor']['delete']==1){
    $contractor = $contractor_model->getContractorByID($contractor_code);
    if ($contractor['contractor_image'] != ''){
        $target_file = $target_dir .$contractor['contractor_image'];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
    }
    $contractor = $contractor_model->deleteContractorById($contractor_code);
    ?>
    <script> window.location="index.php?content=contractor"</script>
    <?php
}else if ($_GET['action'] == 'add'&&$menu['contractor']['add']==1){
    $contractor_code = "CT";
    $contractor_code = $contractor_model->getContractorLastCode($contractor_code,4);  
    if($contractor_code!=false){
        $data['contractor_code'] = $contractor_code;
        $data['contractor_prefix'] = $_POST['contractor_prefix'];
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_email'] = $_POST['contractor_email'];
        $data['contractor_username'] = $_POST['contractor_username'];
        $data['contractor_password'] = $_POST['contractor_password'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['contractor_zipcode'] = $_POST['contractor_zipcode'];
        $data['contractor_status_code'] = $_POST['contractor_status_code']; 
        
        $contractor = $contractor_model->insertContractor($data);
    }else{
    ?>
        <script>window.location="index.php?app=contractor"</script>
    <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['contractor']['edit']==1){
    if(isset($_POST['contractor_contractor_code'])){
        $data = [];  
        $data['contractor_code'] = $_POST['contractor_code'];
        $data['contractor_prefix'] = $_POST['contractor_prefix'];
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_email'] = $_POST['contractor_email'];
        $data['contractor_username'] = $_POST['contractor_username'];
        $data['contractor_password'] = $_POST['contractor_password'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['contractor_zipcode'] = $_POST['contractor_zipcode'];
        $data['contractor_status_code'] = $_POST['contractor_status_code']; 

        $contractor = $contractor_model->updateContractorByID($_POST['contractor_code'],$data);

        if($contractor){  
?>
        <script>
            window.location="index.php?app=contractor"
        </script>
<?php
        }else{
?>
        <script>
            window.location="index.php?app=contractor"
        </script>
<?php
        }
    }else{
?>
    <script>
        window.location="index.php?app=contractor"
    </script>
<?php
    }
}else{
    $contractor = $contractor_model->getContractorBy();
    require_once($path.'view.inc.php');
}
?>