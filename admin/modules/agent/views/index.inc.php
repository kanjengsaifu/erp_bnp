<?php
require_once('../models/AgentModel.php');
require_once('../models/AgentLocationModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/agent/views/";

$agent_model = new AgentModel;
$agent_location_model = new AgentLocationModel;
$status_model = new StatusModel; 
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
$location_code = $_GET['location'];

if ($_GET['action'] == 'insert'&&$menu['agent']['add']){ 
    $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['agent']['edit']){
    $agent = $agent_model->getAgentByCode($agent_code);
    $location = $agent_location_model->getAgentLocationBy($agent_code);
    $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($agent['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($agent['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($agent['DISTRICT_ID']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['agent']['delete']){
    $agent = $agent_model->getAgentByCode($agent_code);

    $img_delete = ['profile_image','id_card_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($agent[$img_delete[$i]] != ''){
            $target_file = $target_dir .$agent[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $agent_location_model->deleteAgentLocationBy($agent_code);
    $result = $agent_model->deleteAgentByCode($agent_code);

    ?> <script> window.location="index.php?app=agent"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['agent']['add']){

    if ($_POST['agent_code'] == ''){
        $agent_code = "AG".$_POST['village_id'];
        $agent_code = $agent_model->getAgentLastCode($agent_code,4);  
    }else{
        $agent_code = $_POST['agent_code'];
    }

    if($agent_code != '' && isset($_POST['agent_prefix'])){
        $check = true;
        $data['agent_code'] = $agent_code;
        $data['status_code'] = $_POST['status_code']; 
        $data['agent_prefix'] = $_POST['agent_prefix'];  
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_line'] = $_POST['agent_line'];
        $data['agent_username'] = $_POST['agent_username'];
        $data['agent_password'] = $_POST['agent_password'];
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
            $result = $agent_model->insertAgent($data);


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
        $data['status_code'] = $_POST['status_code']; 
        $data['agent_prefix'] = $_POST['agent_prefix'];
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_lastname'] = $_POST['agent_lastname'];
        $data['agent_address'] = $_POST['agent_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_mobile'] = $_POST['agent_mobile'];
        $data['agent_line'] = $_POST['agent_line'];
        $data['agent_username'] = $_POST['agent_username'];
        $data['agent_password'] = $_POST['agent_password'];
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
}else if ($_GET['action'] == 'approve'){
    if(isset($_POST['agent_code'])){
        $result = $agent_model->approveAgentByCode($_POST['agent_code']);
    }

    ?> <script> window.location="index.php?app=agent" </script> <?php
}else if ($_GET['action'] == 'insert-location'&&$menu['agent']['add']){ 
    require_once($path.'insert-location.inc.php');
}else if ($_GET['action'] == 'update-location'&&$menu['agent']['edit']){ 
    $location = $agent_location_model->getAgentLocationByCode($location_code);
    require_once($path.'update-location.inc.php');
}else if ($_GET['action'] == 'add-location'&&$menu['agent']['add']){
    $code = date('y').$_POST['agent_code'];
    $location_code = $agent_location_model->getAgentLocationLastCode($code,3);  

    if($location_code != '' && isset($_POST['agent_code'])){
        $check = true;
        $data['location_code'] = $location_code;
        $data['agent_code'] = $_POST['agent_code'];
        $data['location_lat'] = $_POST['location_lat'];
        $data['location_long'] = $_POST['location_long'];
        $data['addby'] = $login_user['user_code'];

        $result = $agent_location_model->insertAgentLocation($data);

        if($result){
            ?> <script> window.location="index.php?app=agent&action=update&code=<?php echo $_POST['agent_code']; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'edit-location'&&$menu['agent']['edit']){
    $code = date('y').$_POST['agent_code'];
    $location_code = $agent_location_model->getAgentLocationLastCode($code,3);  

    if($location_code != '' && isset($_POST['agent_code'])){
        $check = true;
        $data['location_code'] = $location_code;
        $data['agent_code'] = $_POST['agent_code'];
        $data['location_lat'] = $_POST['location_lat'];
        $data['location_long'] = $_POST['location_long'];
        $data['addby'] = $login_user['user_code'];

        $result = $agent_location_model->insertAgentLocation($data);

        if($result){
            ?> <script> window.location="index.php?app=agent&action=update&code=<?php echo $_POST['agent_code']; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'delete-location'&&$menu['agent']['delete']){
    $location = $agent_location_model->getAgentLocationByCode($location_code);
    $result = $agent_location_model->deleteAgentLocationByCode($location_code);

    if($result){
        ?> <script> window.location="index.php?app=agent&action=update&code=<?php echo $location['agent_code']; ?>" </script> <?php
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'detail'){
    $agent = $agent_model->getAgentByCode($agent_code);
    require_once($path.'detail.inc.php');
}else if ($_GET['status'] == 'pending'){
    $on_pending = $agent_model->countAgentByStatus('00');
    $agent = $agent_model->getAgentByStatus('00');
    require_once($path.'view.inc.php');
}else if ($_GET['status'] == 'cease'){
    $on_pending = $agent_model->countAgentByStatus('00');
    $agent = $agent_model->getAgentByStatus('02');
    require_once($path.'view.inc.php');
}else{
    $on_pending = $agent_model->countAgentByStatus('00');
    $agent = $agent_model->getAgentByStatus('01');
    require_once($path.'view.inc.php');
}
?>