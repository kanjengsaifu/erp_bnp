<?php
require_once('../models/ZoneModel.php');
require_once('../models/ZoneListModel.php');
require_once('../models/AddressModel.php');

$path = "modules/zone/views/";

$zone_model = new ZoneModel;
$zone_list_model = new ZoneListModel; 
$address_model = new AddressModel; 

$zone_code = $_GET['code'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['zone']['add']){ 
    $zone_list = $zone_list_model->getZoneListBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['zone']['edit']){
    $zone = $zone_model->getZoneByCode($zone_code);
    $zone_list = $zone_list_model->getZoneListBy();
    $province = $address_model->getProvinceBy();
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['zone']['delete']){
    $result = $zone_model->deleteZoneByCode($zone_code);
    ?> <script> window.location="index.php?app=zone"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['zone']['add']){
    $zone_code = "ZONE";
    $zone_code = $zone_model->getZoneLastCode($zone_code,4);  

    if($zone_code != '' && isset($_POST['zone_name'])){
        $data = [];  
        $data['zone_code'] = $zone_code;
        $data['zone_name'] = $_POST['zone_name'];
        $data['zone_description'] = $_POST['zone_description'];
        $data['addby'] = $login_user['user_code'];  

        $result = $zone_model->insertZone($data);

        if($result){
            ?> <script> window.location="index.php?app=zone&action=update&code=<?php echo $zone_code; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['zone']['edit']){
    if(isset($_POST['zone_code'])){
        $data = [];  
        $data['zone_name'] = $_POST['zone_name'];
        $data['zone_description'] = $_POST['zone_description'];
        $data['updateby'] = $login_user['user_code'];  

        $result = $zone_model->updateZoneByCode($_POST['zone_code'],$data);

        if($result){
            ?> <script> window.location="index.php?app=zone" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else{
    $zone = $zone_model->getZoneBy();
    require_once($path.'view.inc.php');
}
?>