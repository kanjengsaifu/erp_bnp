<?php
require_once('../models/ZoneModel.php');
require_once('../models/ZoneListModel.php');
require_once('../models/ZoneSongsermModel.php');
require_once('../models/ZoneContractorModel.php');
require_once('../models/ZoneCallCenterModel.php');
require_once('../models/AgentModel.php');
require_once('../models/DealerModel.php');
require_once('../models/SongsermPositionModel.php');
require_once('../models/AddressModel.php');

$path = "modules/zone/views/";

$zone_model = new ZoneModel;
$zone_list_model = new ZoneListModel; 
$zone_songserm_model = new ZoneSongsermModel; 
$zone_contractor_model = new ZoneContractorModel; 
$zone_call_center_model = new ZoneCallCenterModel; 
$agent_model = new AgentModel;
$dealer_model = new DealerModel;
$songserm_position_model = new SongsermPositionModel; 
$address_model = new AddressModel; 

$zone_code = $_GET['code'];
$zone_list_code = $_GET['list'];
$zone_call_center_code = $_GET['callcenter'];
$zone_songserm_code = $_GET['songserm'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['zone']['add']){ 
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'add'&&$menu['zone']['add']){
    if ($_POST['zone_code'] == ''){
        $code = "ZO".date('y').date('m').date('d');
        $zone_code = $zone_model->getZoneLastCode($code,4);  
    }else{
        $zone_code = $_POST['zone_code'];
    }

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
}else if ($_GET['action'] == 'update'&&$menu['zone']['edit']){
    $zone = $zone_model->getZoneByCode($zone_code);
    $zone_list = $zone_list_model->getZoneListByZone($zone_code);
    $zone_songserm = $zone_songserm_model->getZoneSongsermBy($zone_code);
    $zone_contractor = $zone_contractor_model->getZoneContractorBy($zone_code);
    $zone_call_center = $zone_call_center_model->getZoneCallCenterByZone($zone_code);
    $songserm_position = $songserm_position_model->getSongsermPositionBy();
    $province = $address_model->getProvinceBy();
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'edit'&&$menu['zone']['edit']){
    if(isset($_POST['zone_code'])){
        $data = [];  
        $data['zone_name'] = $_POST['zone_name'];
        $data['zone_description'] = $_POST['zone_description'];
        $data['updateby'] = $login_user['user_code'];  

        $result = $zone_model->updateZoneByCode($_POST['zone_code'],$data);

        if(!$result){
            ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่ได้'); </script> <?php
        }
        ?> <script> window.history.back(); </script> <?php
    }else{
        ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่ได้'); </script> <?php
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'insert-list'&&$menu['zone']['add']){
    $zone = $zone_model->getZoneByCode($zone_code);
    $province = $address_model->getProvinceBy();
    require_once($path.'insert-list.inc.php');
}else if ($_GET['action'] == 'add-list'&&$menu['zone']['add']){
    if(isset($_POST['zone_code'])){
        $data = [];  
        $data['zone_list_code'] = $_POST['zone_code'].$_POST['village_id'];
        $data['zone_code'] = $_POST['zone_code'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_code'] = $_POST['agent_code'];
        $data['dealer_code'] = $_POST['dealer_code'];
        $data['addby'] = $login_user['user_code'];

        $result = $zone_list_model->insertZoneList($data);

        if($result){
            ?> <script> window.location="index.php?app=zone&action=update&code=<?php echo $_POST['zone_code']; ?>" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเพิ่มพื้นที่หมู่บ้านได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }
    ?> <script> alert('ไม่สามารถเพิ่มพื้นที่หมู่บ้านได้'); </script> <?php
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'update-list'&&$menu['zone']['edit']){
    $zone_list = $zone_list_model->getZoneListByCode($zone_list_code);
    $province = $address_model->getProvinceBy();
    $agent = $agent_model->getAgentByDistrict($zone_list['DISTRICT_ID']);
    $dealer = $dealer_model->getDealerByDistrict($zone_list['DISTRICT_ID']);
    $district = $address_model->getDistrictByAmphurID($zone_list['AMPHUR_ID']); 
    $amphur = $address_model->getAmphurByProviceID($zone_list['PROVINCE_ID']);

    require_once($path.'update-list.inc.php');
}else if ($_GET['action'] == 'edit-list'&&$menu['zone']['add']){
    if(isset($_POST['zone_list_code'])){
        $data = [];  
        $data['zone_list_code'] = $_POST['zone_code'].$_POST['village_id'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_code'] = $_POST['agent_code'];
        $data['dealer_code'] = $_POST['dealer_code'];
        $data['updateby'] = $login_user['user_code'];

        $result = $zone_list_model->updateZoneListByCode($_POST['zone_list_code'],$data);

        if($result){
            ?> <script> window.location="index.php?app=zone&action=update&code=<?php echo $_POST['zone_code']; ?>" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่หมู่บ้านได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }
    ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่หมู่บ้านได้'); </script> <?php
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete-songserm'&&$menu['zone']['delete']){
    $result = $zone_songserm_model->deleteZoneSongsermByCode($zone_songserm_code);
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete-list'&&$menu['zone']['delete']){
    $result = $zone_list_model->deleteZonelistByCode($zone_list_code);
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete-callcenter'&&$menu['zone']['delete']){
    $result = $zone_call_center_model->deleteZoneCallCenterByCode($zone_call_center_code);
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete'&&$menu['zone']['delete']){
    $result = $zone_model->deleteZoneByCode($zone_code);
    ?> <script> window.location="index.php?app=zone"</script> <?php
}else{
    $zone = $zone_model->getZoneBy();
    require_once($path.'view.inc.php');
}
?>