<?php
require_once('../models/FundAgentModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/fund_agent/views/";

$fund_agent_model = new FundAgentModel;
$status_model = new StatusModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/fund_agent/";

$fund_agent_code = $_GET['code'];

if ($_GET['action'] == 'insert'&&$menu['fund_agent']['add']){ 
    $status = $status_model->getStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['fund_agent']['edit']){
    $fund_agent = $fund_agent_model->getFundAgentByCode($fund_agent_code);
    $status = $status_model->getStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($fund_agent['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($fund_agent['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['fund_agent']['delete']){
    $fund_agent = $fund_agent_model->getFundAgentByCode($fund_agent_code);

    $img_delete = ['profile_image','id_card_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($fund_agent[$img_delete[$i]] != ''){
            $target_file = $target_dir .$fund_agent[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $fund_agent_model->deleteFundAgentByCode($fund_agent_code);

    ?> <script> window.location="index.php?app=fund_agent"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['fund_agent']['add']){

    if ($_POST['fund_agent_code'] == ''){
        $fund_agent_code = "AG".$_POST['province_id'].$_POST['district_id'];
        $fund_agent_code = $fund_agent_model->getFundAgentLastCode($fund_agent_code,4);  
    }else{
        $fund_agent_code = $_POST['fund_agent_code'];
    }

    if($fund_agent_code != '' && isset($_POST['fund_agent_prefix'])){
        $check = true;
        $data['fund_agent_code'] = $fund_agent_code;
        $data['status_code'] = $_POST['status_code']; 
        $data['fund_agent_prefix'] = $_POST['fund_agent_prefix'];  
        $data['fund_agent_name'] = $_POST['fund_agent_name'];
        $data['fund_agent_lastname'] = $_POST['fund_agent_lastname'];
        $data['fund_agent_address'] = $_POST['fund_agent_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['fund_agent_zipcode'] = $_POST['fund_agent_zipcode'];
        $data['fund_agent_mobile'] = $_POST['fund_agent_mobile'];
        $data['fund_agent_username'] = $_POST['fund_agent_username'];
        $data['fund_agent_password'] = $_POST['fund_agent_password'];
        $data['addby'] = $login_user['user_code'];

        $img_upload = ['profile_image','id_card_image'];

        $target_file = [];
        for ($i=0; $i<count($img_upload); $i++){
            if($_FILES[$img_upload[$i]]['name'] == ""){
                $data[$img_upload[$i]] = "";
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
                    }else {
                        $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                        $check = false;
                    }
                }
            }
        }

        if($check){
            $result = $fund_agent_model->insertFundAgent($data);


        }else{
            ?> 
            <script> 
                alert('<?php echo $error_msg; ?>'); 
                window.history.back(); 
            </script> 
            <?php
        }
    }else{
        ?> <script> window.location="index.php?app=fund_agent" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['fund_agent']['edit']){
    if(isset($_POST['fund_agent_code'])){
        $check = true;
        $data = [];  
        $data['status_code'] = $_POST['status_code']; 
        $data['fund_agent_prefix'] = $_POST['fund_agent_prefix'];
        $data['fund_agent_name'] = $_POST['fund_agent_name'];
        $data['fund_agent_lastname'] = $_POST['fund_agent_lastname'];
        $data['fund_agent_address'] = $_POST['fund_agent_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['fund_agent_zipcode'] = $_POST['fund_agent_zipcode'];
        $data['fund_agent_mobile'] = $_POST['fund_agent_mobile'];
        $data['fund_agent_username'] = $_POST['fund_agent_username'];
        $data['fund_agent_password'] = $_POST['fund_agent_password'];
        $data['updateby'] = $login_user['user_code']; 

        $img_upload = ['profile_image','id_card_image'];

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
            $result = $fund_agent_model->updateFundAgentByCode($_POST['fund_agent_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=fund_agent" </script> <?php
            }else{
                ?> <script> window.history.back(); </script> <?php
            }
        }else{
            ?> 
            <script> 
                alert('<?php echo $error_msg; ?>'); 
                window.history.back(); 
            </script> 
            <?php
        }
    }else{
        ?> <script> window.location="index.php?app=fund_agent" </script> <?php
    }
}else if ($_GET['action'] == 'approve'){
    if(isset($_POST['fund_agent_code'])){
        $result = $fund_agent_model->approveFundAgentByCode($_POST['fund_agent_code']);
    }

    ?> <script> window.location="index.php?app=fund_agent" </script> <?php
}else if ($_GET['action'] == 'detail'){
    $fund_agent = $fund_agent_model->getFundAgentByCode($fund_agent_code);
    require_once($path.'detail.inc.php');
}else if ($_GET['status'] == 'pending'){
    $on_pending = $fund_agent_model->countFundAgentByStatus('00');
    $fund_agent = $fund_agent_model->getFundAgentByStatus('00');
    require_once($path.'view.inc.php');
}else{
    $on_pending = $fund_agent_model->countFundAgentByStatus('00');
    $fund_agent = $fund_agent_model->getFundAgentByStatus('01');
    require_once($path.'view.inc.php');
}
?>