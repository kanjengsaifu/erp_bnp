<?php
require_once('../models/AddressModel.php');

$path = "modules/address/views/";

$address_model = new AddressModel; 

$province_id = $_GET['province'];
$amphur_id = $_GET['amphur'];
$district_id = $_GET['district'];
$village_id = $_GET['village'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['address']['add']){ 
    $village = $address_model->getDistrictByID($district_id);
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($village['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($village['AMPHUR_ID']); 
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'add'&&$menu['address']['add']){
    if(isset($_POST['village_name'])){
        $data = [];  
        $data['VILLAGE_CODE'] = $_POST['village_code'];
        $data['VILLAGE_NO'] = $_POST['village_no'];
        $data['VILLAGE_NAME'] = $_POST['village_name'];
        $data['DISTRICT_ID'] = $_POST['district_id'];

        $result = $address_model->insertVillage($data);

        if($result){
            ?> <script> window.location="index.php?app=address&action=district&district=<?php echo $_POST['district_id']; ?>"; </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['address']['edit']){
    if(isset($_POST['village_id'])){
        $data = [];  
        $data['VILLAGE_CODE'] = $_POST['village_code'];
        $data['VILLAGE_NO'] = $_POST['village_no'];
        $data['VILLAGE_NAME'] = $_POST['village_name'];
        $data['DISTRICT_ID'] = $_POST['district_id'];

        $result = $address_model->updateVillageByID($_POST['village_id'],$data);

        if($result){
            ?> <script> window.location="index.php?app=address&action=district&district=<?php echo $_POST['district_id']; ?>"; </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'update'&&$menu['address']['edit']){
    $village = $address_model->getVillageByID($village_id); 
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($village['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($village['AMPHUR_ID']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['address']['delete']){
    $village = $address_model->getVillageByID($village_id); 

    $result = $address_model->deleteVillageByID($village_id);
    ?> <script> window.location="index.php?app=address&action=district&district=<?php echo $village['DISTRICT_ID']; ?>"; </script> <?php
}else if ($_GET['action'] == 'province'){
    $province = $address_model->getProvinceByID($province_id);
    $amphur = $address_model->getAmphurInfoByProviceID($province_id);
    require_once($path.'province.inc.php');
}else if ($_GET['action'] == 'amphur'){
    $amphur = $address_model->getAmphurByID($amphur_id);
    $district = $address_model->getDistrictInfoByAmphurID($amphur_id); 
    require_once($path.'amphur.inc.php');
}else if ($_GET['action'] == 'district'){
    $district = $address_model->getDistrictByID($district_id);
    $village = $address_model->getVillageByDistrictID($district_id); 
    require_once($path.'district.inc.php');
}else{
    $province = $address_model->getProvinceInfoBy();
    require_once($path.'view.inc.php');
}
?>