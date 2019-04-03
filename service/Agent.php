<?php
date_default_timezone_set('asia/bangkok');
require_once('../models/AgentModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php'); 
require_once('../models/AgentLocationModel.php');
 

$agent_model = new AgentModel;
$status_model = new StatusModel; 
$address_model = new AddressModel; 
$agent_location_model = new AgentLocationModel;

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/agent/";

$agent_code = $_POST['code'];
 

if ($_POST['action'] == 'add'){

    if ($_POST['agent_code'] == ''){
        $agent_code = "AG".$d2.$d3;
        $agent_code = $agent_model->getAgentLastCode($agent_code,4);  
    }else{
        $agent_code = $_POST['agent_code'];
    }

    if($agent_code != ''){
        $check = true;
        $data['agent_code'] = $agent_code; 
        $data['agent_username'] = $_POST['agent_username'];
        $data['agent_password'] = $_POST['agent_password'];
        $data['status_code'] = $_POST['status_code']; 
        $data['agent_prefix'] = $_POST['agent_prefix'];  
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_line'] = $_POST['agent_line']; 
        $data['agent_signature'] = $_POST['agent_signature'];
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
            $check_result = $agent_model->insertAgent($data); 
            if($check_result!=false){
                if($_POST['location_checked']==true){ 
                    $code =  date('y').$agent_code;
                    $location_code = $agent_location_model->getAgentLocationLastCode($code,3);  
                    if($location_code != '' && $agent_code!=""){
                        $check = true;
                        $data['location_code'] = $location_code;
                        $data['agent_code'] = $agent_code;
                        $data['location_lat'] = $_POST['location_lat'];
                        $data['location_long'] = $_POST['location_long'];
                        $data['addby'] = $_POST['addby'];
        
                        $result_check = $agent_location_model->insertAgentLocation($data);
        
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
                $result ['result_text'] = 'check_result = '.$check_result;
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
            $result ['result_text'] = 'check = '.$check;
            $result ['result'] = false;
            echo json_encode($result);
        } 
 
    }else{
        $result ['result_text'] = 'agent_code empty';
        $result ['result'] = false;
        echo json_encode($result);
    }
} 
else if ($_POST['action'] == 'update'){
    $agent = $agent_model->getAgentByCode($agent_code);
    // $location = $agent_location_model->getAgentLocationBy($agent_code);
    // $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($agent['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($agent['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($agent['DISTRICT_ID']);  
    
    $result_detail['agent'] = $agent;
    $result_detail['province'] = $province;
    $result_detail['amphur'] = $amphur;
    $result_detail['district'] = $district;
    $result_detail['village'] = $village;
    $result_detail['result'] = true;
    echo json_encode($result_detail); 
}
else if ($_POST['action'] == 'edit'){
    if($agent_code!=''){
        $check = true;
        $data = [];   
        $data['agent_username'] = $_POST['agent_username'];
        $data['agent_password'] = $_POST['agent_password'];
        $data['status_code'] = $_POST['status_code']; 
        $data['agent_prefix'] = $_POST['agent_prefix'];  
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_line'] = $_POST['agent_line']; 
        $data['agent_signature'] = $_POST['agent_signature'];
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
            $check_result = $agent_model->updateAgentByCode($agent_code,$data);
    
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