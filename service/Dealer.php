<?php
date_default_timezone_set('asia/bangkok');
require_once('../models/DealerModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php');

 

$dealer_model = new DealerModel;
$status_model = new StatusModel; 
$address_model = new AddressModel; 

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
        $dealer_code = "AG".$d2.$d3;
        $dealer_code = $dealer_model->getDealerLastCode($dealer_code,4);  
    }else{
        $dealer_code = $_POST['dealer_code'];
    }

    if($dealer_code != ''){
        $check = true;
        $data['dealer_code'] = $dealer_code;
        $data['status_code'] = $_POST['status_code']; 
        $data['dealer_prefix'] = $_POST['dealer_prefix'];  
        $data['dealer_name'] = $_POST['dealer_name'];
        $data['dealer_lastname'] = $_POST['dealer_lastname'];
        $data['dealer_address'] = $_POST['dealer_address'];
        $data['dealer_fund_name'] = $_POST['dealer_fund_name'];
        $data['dealer_fund_budget'] = $_POST['dealer_fund_budget'];
        $data['village_id'] = $_POST['village_id'];
        $data['dealer_mobile'] = $_POST['dealer_mobile'];
        $data['dealer_line'] = $_POST['dealer_line'];
        $data['dealer_username'] = $_POST['dealer_username'];
        $data['dealer_password'] = $_POST['dealer_password'];
        $data['addby'] = $login_user['user_code'];

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
?>