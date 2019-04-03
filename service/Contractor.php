<?php
 
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
    $data['contractor_signature'] = $_POST['contractor_signature'];
    $data['addby'] = $_POST['addby'];

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
        $result_check = $contractor_model->insertContractor($data);

        if($result_check!=false){
            if($_POST['location_checked']==true){ 
                $code = date('y').$contractor_code;
                $location_code = $contractor_location_model->getContractorLocationLastCode($code,3);  
                if($location_code != '' && $contractor_code!=""){
                    $check = true;
                    $data['location_code'] = $location_code;
                    $data['contractor_code'] = $contractor_code;
                    $data['location_lat'] = $_POST['location_lat'];
                    $data['location_long'] = $_POST['location_long'];
                    $data['addby'] = $_POST['addby'];
    
                    $result_check = $contractor_location_model->insertContractorLocation($data);
    
                    if($result_check!=false){  
                        $result ['result'] = true;
                        $result ['result_location'] = true;
                        echo json_encode($result);
                    }else{
                        $result ['result_text'] = 'err: location';
                        $result ['result'] = true;
                        $result ['result_location'] = false;
                        echo json_encode($result);
                    }
                }else{
                    $result ['result_text'] = 'err: location';
                    $result ['result'] = true;
                    $result ['result_location'] = false;
                    echo json_encode($result);
                }
            }else{ 
                $result['result'] = true;
                $result ['result_location'] = true;
                echo json_encode($result);
            } 

        }else{
            for ($i=0; $i<count($input_image); $i++){
                if ($_FILES[$input_image[$i]]['name'] != ''){
                    $target_file = $target_dir .$_FILES[$input_image[$i]]['name'];
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            }
            $result ['result_text'] = 'it can not upload';
            $result ['result'] = false; 
            $result ['result_location'] = true;
            echo json_encode($result);
        }
    }else{
        for ($i=0; $i<count($input_image); $i++){
            if ($_FILES[$input_image[$i]]['name'] != ''){
                $target_file = $target_dir .$_FILES[$input_image[$i]]['name'];
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
        }
        $result ['result_text'] = 'it can not upload';
        $result ['result'] = false; 
        $result ['result_location'] = true;
        echo json_encode($result);
    } 
}else if ($_POST['action'] == 'edit'){
    if($contractor_code!=''){
        $check = true;
        $data = [];   
        $data['status_code'] = $_POST['status_code'];
        $data['contractor_prefix'] = $_POST['contractor_prefix'];
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_line'] = $_POST['contractor_line'];
        $data['contractor_signature'] = $_POST['contractor_signature'];
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
                    if ($_FILES[$input_image[$i]]['name'] != ''){
                        $target_file = $target_dir .$_FILES[$input_image[$i]]['name'];
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
                if ($_FILES[$input_image[$i]]['name'] != ''){
                    $target_file = $target_dir .$_FILES[$input_image[$i]]['name'];
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
} 
?>