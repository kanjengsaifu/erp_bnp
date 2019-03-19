<?php
require_once('../models/DealerModel.php');
require_once('../models/StatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/dealer/views/";

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

$dealer_code = $_GET['code'];

if ($_GET['action'] == 'insert'&&$menu['dealer']['add']){ 
    $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['dealer']['edit']){
    $dealer = $dealer_model->getDealerByCode($dealer_code);
    $status = $status_model->getStatusBy();
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($dealer['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($dealer['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($dealer['DISTRICT_ID']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['dealer']['delete']){
    $dealer = $dealer_model->getDealerByCode($dealer_code);

    $img_delete = ['profile_image','id_card_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($dealer[$img_delete[$i]] != ''){
            $target_file = $target_dir .$dealer[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $dealer_model->deleteDealerByCode($dealer_code);

    ?> <script> window.location="index.php?app=dealer"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['dealer']['add']){

    if ($_POST['dealer_code'] == ''){
        $dealer_code = "AG".$_POST['province_id'].$_POST['district_id'];
        $dealer_code = $dealer_model->getDealerLastCode($dealer_code,4);  
    }else{
        $dealer_code = $_POST['dealer_code'];
    }

    if($dealer_code != '' && isset($_POST['dealer_prefix'])){
        $check = true;
        $data['dealer_code'] = $dealer_code;
        $data['status_code'] = $_POST['status_code']; 
        $data['dealer_prefix'] = $_POST['dealer_prefix'];  
        $data['dealer_name'] = $_POST['dealer_name'];
        $data['dealer_lastname'] = $_POST['dealer_lastname'];
        $data['dealer_address'] = $_POST['dealer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['dealer_mobile'] = $_POST['dealer_mobile'];
        $data['dealer_line'] = $_POST['dealer_line'];
        $data['dealer_username'] = $_POST['dealer_username'];
        $data['dealer_password'] = $_POST['dealer_password'];
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
            $result = $dealer_model->insertDealer($data);


        }else{
            ?> 
            <script> 
                alert('<?php echo $error_msg; ?>'); 
                window.history.back(); 
            </script> 
            <?php
        }
    }else{
        ?> <script> window.location="index.php?app=dealer" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['dealer']['edit']){
    if(isset($_POST['dealer_code'])){
        $check = true;
        $data = [];  
        $data['status_code'] = $_POST['status_code']; 
        $data['dealer_prefix'] = $_POST['dealer_prefix'];
        $data['dealer_name'] = $_POST['dealer_name'];
        $data['dealer_lastname'] = $_POST['dealer_lastname'];
        $data['dealer_address'] = $_POST['dealer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['dealer_mobile'] = $_POST['dealer_mobile'];
        $data['dealer_line'] = $_POST['dealer_line'];
        $data['dealer_username'] = $_POST['dealer_username'];
        $data['dealer_password'] = $_POST['dealer_password'];
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
            $result = $dealer_model->updateDealerByCode($_POST['dealer_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=dealer" </script> <?php
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
        ?> <script> window.location="index.php?app=dealer" </script> <?php
    }
}else if ($_GET['action'] == 'approve'){
    if(isset($_POST['dealer_code'])){
        $result = $dealer_model->approveDealerByCode($_POST['dealer_code']);
    }

    ?> <script> window.location="index.php?app=dealer" </script> <?php
}else if ($_GET['action'] == 'detail'){
    $dealer = $dealer_model->getDealerByCode($dealer_code);
    require_once($path.'detail.inc.php');
}else if ($_GET['status'] == 'pending'){
    $on_pending = $dealer_model->countDealerByStatus('00');
    $dealer = $dealer_model->getDealerByStatus('00');
    require_once($path.'view.inc.php');
}else if ($_GET['status'] == 'cease'){
    $on_pending = $dealer_model->countDealerByStatus('00');
    $dealer = $dealer_model->getDealerByStatus('02');
    require_once($path.'view.inc.php');
}else{
    $on_pending = $dealer_model->countDealerByStatus('00');
    $dealer = $dealer_model->getDealerByStatus('01');
    require_once($path.'view.inc.php');
}
?>