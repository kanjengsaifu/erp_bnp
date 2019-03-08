<?php
require_once('../models/AgentModel.php');
require_once('../models/AgentStatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/agent/views/";

$agent_model = new AgentModel;
$agent_status_model = new AgentStatusModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/agent/";

$agent_code = $_GET['code'];

if ($_GET['action'] == 'insert'&&$menu['agent']['add']){ 
    $agent_status = $agent_status_model->getAgentStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['agent']['edit']){
    $agent = $agent_model->getAgentByCode($agent_code);
    $agent_status = $agent_status_model->getAgentStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($agent['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($agent['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['agent']['delete']){
    $agent = $agent_model->getAgentByCode($agent_code);

    $img_delete = ['agent_image','id_card_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($agent[$img_delete[$i]] != ''){
            $target_file = $target_dir .$agent[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $agent_model->deleteAgentByCode($agent_code);

    ?> <script> window.location="index.php?app=agent"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['agent']['add']){
    $agent_code = "AG".$_POST['province_id'].$_POST['district_id'];
    $agent_code = $agent_model->getAgentLastCode($agent_code,4);  

    if($agent_code != '' && isset($_POST['agent_prefix'])){
        $check = true;
        $data['agent_code'] = $agent_code;
        $data['agent_prefix'] = $_POST['agent_prefix'];  
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['agent_zipcode'] = $_POST['agent_zipcode'];
        $data['agent_status_code'] = $_POST['agent_status_code']; 

        $img_upload = ['agent_image','id_card_image'];

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
            $result = $agent_model->insertAgent($data);

            if($result){
                ?> <script> window.location="index.php?app=agent" </script> <?php
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
        ?> <script> window.location="index.php?app=agent" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['agent']['edit']){
    if(isset($_POST['agent_code'])){
        $check = true;
        $data = [];  
        $data['agent_code'] = $_POST['agent_code'];
        $data['agent_prefix'] = $_POST['agent_prefix'];
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['agent_zipcode'] = $_POST['agent_zipcode'];
        $data['agent_status_code'] = $_POST['agent_status_code']; 

        $img_upload = ['agent_image','id_card_image'];

        $target_file = [];
        for ($i=0; $i<count($img_upload); $i++){
            if($_FILES[$img_upload[$i]]['name'] == ""){
                $data[$img_upload[$i]] = $_POST[$img_upload.'_o'];
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
            $result = $agent_model->updateAgentByCode($_POST['agent_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=agent" </script> <?php
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
        ?> <script> window.location="index.php?app=agent" </script> <?php
    }
}else if ($_GET['action'] == 'profile'){
    $agent = $agent_model->getAgentByCode($agent_code);
    $agent_status = $agent_status_model->getAgentStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($agent['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($agent['amphur_id']); 
    require_once($path.'detail.inc.php');
}else{
    $agent = $agent_model->getAgentBy();
    require_once($path.'view.inc.php');
}
?>