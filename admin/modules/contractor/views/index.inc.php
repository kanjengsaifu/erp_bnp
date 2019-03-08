<?php
require_once('../models/ContractorModel.php');
require_once('../models/ContractorStatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/contractor/views/";

$contractor_model = new ContractorModel;
$contractor_status_model = new ContractorStatusModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/contractor/";

$contractor_code = $_GET['code'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['contractor']['add']){ 
    $contractor_status = $contractor_status_model->getContractorStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['contractor']['edit']){
    $contractor = $contractor_model->getContractorByCode($contractor_code);
    $contractor_status = $contractor_status_model->getContractorStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($contractor['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($contractor['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['contractor']['delete']){
    $contractor = $contractor_model->getContractorByCode($contractor_code);

    $img_delete = ['contractor_image','id_card_image','house_regis_image','account_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($contractor[$img_delete[$i]] != ''){
            $target_file = $target_dir .$contractor[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $contractor_model->deleteContractorByCode($contractor_code);

    ?> <script> window.location="index.php?app=contractor"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['contractor']['add']){
    $contractor_code = "CT";
    $contractor_code = $contractor_model->getContractorLastCode($contractor_code,4);  

    if($contractor_code != '' && isset($_POST['contractor_prefix'])){
        $check = true;
        $data['contractor_code'] = $contractor_code;
        $data['contractor_prefix'] = $_POST['contractor_prefix'];  
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['contractor_zipcode'] = $_POST['contractor_zipcode'];
        $data['contractor_status_code'] = $_POST['contractor_status_code']; 

        $img_upload = ['contractor_image','id_card_image','house_regis_image','account_image'];

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
            $result = $contractor_model->insertContractor($data);

            if($result){
                ?> <script> window.location="index.php?app=contractor" </script> <?php
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
        ?> <script> window.location="index.php?app=contractor" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['contractor']['edit']){
    if(isset($_POST['contractor_code'])){
        $check = true;
        $data = [];  
        $data['contractor_code'] = $_POST['contractor_code'];
        $data['contractor_prefix'] = $_POST['contractor_prefix'];
        $data['contractor_name'] = $_POST['contractor_name'];
        $data['contractor_lastname'] = $_POST['contractor_lastname'];
        $data['contractor_mobile'] = $_POST['contractor_mobile'];
        $data['contractor_address'] = $_POST['contractor_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['contractor_zipcode'] = $_POST['contractor_zipcode'];
        $data['contractor_status_code'] = $_POST['contractor_status_code']; 

        $img_upload = ['contractor_image','id_card_image','house_regis_image','account_image'];

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
            $result = $contractor_model->updateContractorByCode($_POST['contractor_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=contractor" </script> <?php
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
        ?> <script> window.location="index.php?app=contractor" </script> <?php
    }
}else if ($_GET['action'] == 'approve'){
    if(isset($_POST['contractor_code'])){
        $contractor = $contractor_model->approveContractorByCode($_POST['contractor_code']);
    }

    ?> <script> window.location="index.php?app=contractor" </script> <?php
}else if ($_GET['action'] == 'profile'){
    $contractor = $contractor_model->getContractorByCode($contractor_code);
    $contractor_status = $contractor_status_model->getContractorStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($contractor['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($contractor['amphur_id']); 
    require_once($path.'detail.inc.php');
}else if ($_GET['status'] == 'pending'){
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('00');
    require_once($path.'view.inc.php');
}else if ($_GET['status'] == 'cease'){
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('02');
    require_once($path.'view.inc.php');
}else{
    $on_pending = $contractor_model->countContractorByStatus('00');
    $contractor = $contractor_model->getContractorByStatus('01');
    require_once($path.'view.inc.php');
}
?>