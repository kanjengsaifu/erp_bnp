<?php
date_default_timezone_set('asia/bangkok');
require_once('../models/DealerModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php'); 
require_once('../models/DealerLocationModel.php');
 

$dealer_model = new DealerModel;
$status_model = new StatusModel; 
$address_model = new AddressModel; 
$dealer_location_model = new DealerLocationModel;

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/dealer/";

$dealer_code = $_POST['code'];
 

if ($_POST['action'] == 'add'){

    if ($_POST['dealer_code'] == ''){
        $dealer_code = "DL".$d2.$d3;
        $dealer_code = $dealer_model->getDealerLastCode($dealer_code,4);  
    }else{
        $dealer_code = $_POST['dealer_code'];
    }

    if($dealer_code != ''){
        $check = true;
        $data['dealer_code'] = $dealer_code; 
        $data['dealer_username'] = $_POST['dealer_username'];
        $data['dealer_password'] = $_POST['dealer_password'];
        $data['status_code'] = $_POST['status_code']; 
        $data['dealer_prefix'] = $_POST['dealer_prefix'];  
        $data['dealer_name'] = $_POST['dealer_name'];
        $data['dealer_lastname'] = $_POST['dealer_lastname'];
        $data['dealer_address'] = $_POST['dealer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['dealer_mobile'] = $_POST['dealer_mobile'];
        $data['dealer_line'] = $_POST['dealer_line'];
        $data['dealer_fund_name'] = $_POST['dealer_fund_name'];
        $data['dealer_fund_budget'] = $_POST['dealer_fund_budget']; 
        $data['dealer_signature'] = $_POST['dealer_signature'];
        $data['addby'] = $_POST['addby'];

        $input_image = array('profile_image','id_card_image');

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
            $check_result = $dealer_model->insertDealer($data); 
            if($check_result!=false){
                if($_POST['location_checked']==true){ 
                    $code =  date('y').$dealer_code;
                    $location_code = $dealer_location_model->getDealerLocationLastCode($code,3);  
                    if($location_code != '' && $dealer_code!=""){
                        $check = true;
                        $data['location_code'] = $location_code;
                        $data['dealer_code'] = $dealer_code;
                        $data['location_lat'] = $_POST['location_lat'];
                        $data['location_long'] = $_POST['location_long'];
                        $data['addby'] = $_POST['addby'];
        
                        $result_check = $dealer_location_model->insertDealerLocation($data);
        
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
        $result ['result_text'] = 'it can not upload';
        $result ['result'] = false;
        echo json_encode($result);
    }
} 
else if ($_POST['action'] == 'update'){
    $dealer = $dealer_model->getDealerByCode($dealer_code);
    // $location = $dealer_location_model->getDealerLocationBy($dealer_code);
    // $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($dealer['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($dealer['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($dealer['DISTRICT_ID']);  
    
    $result_detail['dealer'] = $dealer;
    $result_detail['province'] = $province;
    $result_detail['amphur'] = $amphur;
    $result_detail['district'] = $district;
    $result_detail['village'] = $village;
    $result_detail['result'] = true;
    echo json_encode($result_detail); 
}
else if ($_POST['action'] == 'edit'){
    if($dealer_code!=''){
        $check = true;
        $data = [];   
        $data['dealer_username'] = $_POST['dealer_username'];
        $data['dealer_password'] = $_POST['dealer_password'];
        $data['status_code'] = $_POST['status_code']; 
        $data['dealer_prefix'] = $_POST['dealer_prefix'];  
        $data['dealer_name'] = $_POST['dealer_name'];
        $data['dealer_lastname'] = $_POST['dealer_lastname'];
        $data['dealer_address'] = $_POST['dealer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['dealer_mobile'] = $_POST['dealer_mobile'];
        $data['dealer_line'] = $_POST['dealer_line'];
        $data['dealer_fund_name'] = $_POST['dealer_fund_name'];
        $data['dealer_fund_budget'] = $_POST['dealer_fund_budget'];
        $data['dealer_signature'] = $_POST['dealer_signature'];
        $data['updateby'] = $_POST['updateby'];

        $input_image = array('profile_image','id_card_image');

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
            $check_result = $dealer_model->updateDealerByCode($dealer_code,$data);
    
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