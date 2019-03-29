<?php

// header('Access-Control-Allow-Origin: *');  
// header("Access-Control-Allow-Methods: *");
// header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('asia/bangkok');

require_once('../models/ContractorModel.php');
require_once('../models/StatusModel.php');
require_once('../models/ZoneContractorModel.php');
require_once('../models/ContractorLocationModel.php');
require_once('../models/AddressModel.php');

$path = "modules/contractor/views/";

$contractor_model = new ContractorModel;
$status_model = new StatusModel; 
$zone_contractor_model = new ZoneContractorModel;
$contractor_location_model = new ContractorLocationModel;
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/contractor/";

$contractor_code = $_POST['code'];
$location_code = $_POST['location'];

// $json = file_get_contents('php://input');
    
// $obj = json_decode($json,true); 

// echo json_encode($_POST['action']);

if ($_POST['action'] == 'insert'){ 
    $province = $address_model->getProvinceBy();
}else if ($_POST['action'] == 'update'){
    $contractor = $contractor_model->getContractorByCode($contractor_code);
    // $location = $contractor_location_model->getContractorLocationBy($contractor_code);
    // $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($contractor['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($contractor['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($contractor['DISTRICT_ID']);  
    
    $result_detail['contractor'] = $contractor;
    $result_detail['province'] = $province;
    $result_detail['amphur'] = $amphur;
    $result_detail['district'] = $district;
    $result_detail['village'] = $village;
    $result_detail['result'] = true;
    echo json_encode($result_detail); 
    
}else if ($_POST['action'] == 'delete'){
    $contractor = $contractor_model->getContractorByCode($contractor_code);

    $img_delete = ['profile_image','id_card_image','house_regis_image','account_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($contractor[$img_delete[$i]] != ''){
            $target_file = $target_dir .$contractor[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $zone_contractor_model->deleteZoneContractorByContractor($contractor_code);
    $result = $contractor_location_model->deleteContractorLocationBy($contractor_code);
    $result = $contractor_model->deleteContractorByCode($contractor_code);

    ?> <script> window.location="index.php?app=contractor"</script> <?php
}else if ($_POST['action'] == 'add'){
    if ($_POST['contractor_code'] == ''){
        $contractor_code = "CT".$d2.$d3;
        $contractor_code = $contractor_model->getContractorLastCode($contractor_code,4);  
    }else{
        $contractor_code = $_POST['contractor_code'];
    }
 
    $check = true;
    $data['contractor_code'] = $contractor_code;
    $data['status_code'] = $_POST['status_code']; 
    $data['contractor_prefix'] = $_POST['contractor_prefix'];  
    $data['contractor_name'] = $_POST['contractor_name'];
    $data['contractor_lastname'] = $_POST['contractor_lastname'];
    $data['contractor_address'] = $_POST['contractor_address'];
    $data['village_id'] = $_POST['village_id'];
    $data['contractor_mobile'] = $_POST['contractor_mobile'];
    $data['contractor_line'] = $_POST['contractor_line'];
    $data['addby'] = $login_user['user_code'];

    // $input_image = ['profile_image','id_card_image','house_regis_image','account_image']; 
    
    $input_image = array('profile_image','id_card_image','house_regis_image','account_image');

    for($i = 0;$i<count($input_image);$i++){
        if($_FILES[$input_image[$i]]['name'] == ""){
            $data[$input_image[$i]] = $_POST[$input_image[$i].'_o'];
        }else {
            $target_file = $target_dir .$date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"])); 
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "ขอโทษด้วย. มีไฟล์นี้ในระบบแล้ว";
                $check = false;
            }else if ($_FILES[$input_image[$i]]["size"] > 5000000) {
                $error_msg = "ขอโทษด้วย. ไฟล์ของคุณต้องมีขนาดน้อยกว่า 5 MB.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "ขอโทษด้วย. ระบบสามารถอัพโหลดไฟล์นามสกุล JPG, JPEG, PNG & GIF เท่านั้น.";
                $check = false;
            }
            else if (move_uploaded_file($_FILES[$input_image[$i]]["tmp_name"], $target_file)) {
                $data[$input_image[$i]] = $date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"]));
                if($_POST[$input_image[$i].'_o']!=""){
                    $target_file = $target_dir . $_POST[$input_image[$i].'_o'];
                    if (file_exists($target_file)&&$_POST[$input_image[$i].'_o']!='') {
                        unlink($target_file);
                    }
                }
            } 
            else {
                $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                $check = false;
            } 
        }
    }
    if($check){
        $result = $contractor_model->insertContractor($data);

        if($result!=false){
            $result_detail['result'] = true;
            echo json_encode($result_detail);
        }else{
            for ($i=0; $i<count($input_image); $i++){
                if ($data[$input_image[$i]] != ''){
                    $target_file = $target_dir .$data[$input_image[$i]];
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            }
            $result ['result_text'] = 'it can not upload';
            $result ['result'] = false;
            echo json_encode($result);
        }
    }else{
        for ($i=0; $i<count($input_image); $i++){
            if ($data[$input_image[$i]] != ''){
                $target_file = $target_dir .$data[$input_image[$i]];
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
        }
        $result ['result_text'] = 'it can not upload';
        $result ['result'] = false;
        echo json_encode($result);
    } 
}else if ($_POST['action'] == 'edit'){
    if($contractor_code!=''){
        $check = true;
        $data = [];   
        $data['contractor_prefix'] = $_POST['contractor_prefix'];
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_line'] = $_POST['contractor_line'];
        $data['updateby'] = $login_user['user_code'];

        $img_upload = ['profile_image','id_card_image','house_regis_image','account_image'];

        $target_file = [];
        for ($i=0; $i<count($img_upload); $i++){
            if($_FILES[$img_upload[$i]]['name'] == ""){
                $data[$img_upload[$i]] = $_POST[$img_upload[$i].'_o'];
                $target_file[$i] = "";
            }else {
                $target_file[$i] = $target_dir .$date.'-'.strtolower(basename($_FILES[$img_upload[$i]]['name']));
                $imageFileType = strtolower(pathinfo($target_file[$i],PATHINFO_EXTENSION));

                if (file_exists($target_file[$i])) {
                    $error_msg =  "ขอโทษด้วย. มีไฟล์นี้ในระบบแล้ว";
                    $check = false;
                }else if ($_FILES[$img_upload[$i]]["size"] > 5000000) {
                    $error_msg = "ขอโทษด้วย. ไฟล์ของคุณต้องมีขนาดน้อยกว่า 5 MB.";
                    $check = false;
                }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                    $error_msg = "ขอโทษด้วย. ระบบสามารถอัพโหลดไฟล์นามสกุล JPG, JPEG, PNG & GIF เท่านั้น.";
                    $check = false;
                }
            }
        }

        if($check){
            for ($i=0; $i<count($img_upload); $i++){
                if($_FILES[$img_upload[$i]]['name'] != ""){
                    if (move_uploaded_file($_FILES[$img_upload[$i]]["tmp_name"], $target_file[$i])) {
                        $data[$img_upload[$i]] = $date.'-'.strtolower(basename($_FILES[$img_upload[$i]]['name']));

                        if ($_POST[$img_upload.'_o'] != ''){
                            $target_file = $target_dir . $_POST[$img_upload.'_o'];
                            if (file_exists($target_file)) {
                                unlink($target_file);
                            }
                        }
                    }else {
                        $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                        $check = false;
                    }
                }
            }
        }

        if($check){
            $check_result = $contractor_model->updateContractorByCode($contractor_code,$data);
    
            if($check_result!=false){
                $result['result'] = true;
                echo json_encode($result);
            }else{
                for ($i=0; $i<count($input_image); $i++){
                    if ($data[$input_image[$i]] != ''){
                        $target_file = $target_dir .$data[$input_image[$i]];
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                    }
                }
                $result ['result_text'] = 'it can not upload result!=false';
                $result ['result'] = false;
                echo json_encode($result);
            }
        }else{
            for ($i=0; $i<count($input_image); $i++){
                if ($data[$input_image[$i]] != ''){
                    $target_file = $target_dir .$data[$input_image[$i]];
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            }
            $result ['result_text'] = $error_msg;
            $result ['result'] = false;
            echo json_encode($result);
        } 
    }else{
        $result ['result_text'] = 'empty code';
        $result ['result'] = false;
        echo json_encode($result);
    }
}else if ($_POST['action'] == 'approve'){
    if(isset($_POST['contractor_code'])){
        $result = $contractor_model->approveContractorByCode($_POST['contractor_code']);
    }

    ?> <script> window.location="index.php?app=contractor" </script> <?php
}else if ($_POST['action'] == 'insert-location'){ 
    require_once($path.'insert-location.inc.php');
}else if ($_POST['action'] == 'update-location'){ 
    $location = $contractor_location_model->getContractorLocationByCode($location_code);
    require_once($path.'update-location.inc.php');
}else if ($_POST['action'] == 'add-location'){
    $code = date('y').$_POST['contractor_code'];
    $location_code = $contractor_location_model->getContractorLocationLastCode($code,3);  

    if($location_code != '' && isset($_POST['contractor_code'])){
        $check = true;
        $data['location_code'] = $location_code;
        $data['contractor_code'] = $_POST['contractor_code'];
        $data['location_lat'] = $_POST['location_lat'];
        $data['location_long'] = $_POST['location_long'];
        $data['addby'] = $login_user['user_code'];

        $result = $contractor_location_model->insertContractorLocation($data);

        if($result){
            ?> <script> window.location="index.php?app=contractor&action=update&code=<?php echo $_POST['contractor_code']; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_POST['action'] == 'edit-location'){
    $code = date('y').$_POST['contractor_code'];
    $location_code = $contractor_location_model->getContractorLocationLastCode($code,3);  

    if($location_code != '' && isset($_POST['contractor_code'])){
        $check = true;
        $data['location_code'] = $location_code;
        $data['contractor_code'] = $_POST['contractor_code'];
        $data['location_lat'] = $_POST['location_lat'];
        $data['location_long'] = $_POST['location_long'];
        $data['addby'] = $login_user['user_code'];

        $result = $contractor_location_model->insertContractorLocation($data);

        if($result){
            ?> <script> window.location="index.php?app=contractor&action=update&code=<?php echo $_POST['contractor_code']; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_POST['action'] == 'delete-location'){
    $location = $contractor_location_model->getContractorLocationByCode($location_code);
    $result = $contractor_location_model->deleteContractorLocationByCode($location_code);

    if($result){
        ?> <script> window.location="index.php?app=contractor&action=update&code=<?php echo $location['contractor_code']; ?>" </script> <?php
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_POST['action'] == 'detail'){
    $contractor = $contractor_model->getContractorByCode($contractor_code);
    require_once($path.'detail.inc.php');
}else if ($_POST['status'] == 'pending'){
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('00');
    require_once($path.'view.inc.php');
}else if ($_POST['status'] == 'cease'){
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('02');
    require_once($path.'view.inc.php');
}else{
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('01');
    require_once($path.'view.inc.php');
}
?>