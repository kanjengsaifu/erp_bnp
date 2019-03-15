<?php
require_once('../models/FarmerModel.php');
require_once('../models/AddressModel.php');

$path = "modules/farmer/views/";

$farmer_model = new FarmerModel;
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/farmer/";

$farmer_code = $_GET['code'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['farmer']['add']){ 
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['farmer']['edit']){
    $farmer = $farmer_model->getFarmerByCode($farmer_code);
    $province = $address_model->getProvinceBy();
    $amphur = $address_model->getAmphurByProviceID($farmer['PROVINCE_ID']);
    $district = $address_model->getDistrictByAmphurID($farmer['AMPHUR_ID']); 
    $village = $address_model->getVillageByDistrictID($farmer['DISTRICT_ID']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['farmer']['delete']){
    $farmer = $farmer_model->getFarmerByCode($farmer_code);

    $img_delete = ['profile_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($farmer[$img_delete[$i]] != ''){
            $target_file = $target_dir .$farmer[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $farmer_model->deleteFarmerByCode($farmer_code);

    ?> <script> window.location="index.php?app=farmer"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['farmer']['add']){
    if ($_POST['farmer_code'] == ''){
        $farmer_code = date('y').date('m').$_POST['district_id'];
        $farmer_code = $farmer_model->getFarmerLastCode($farmer_code,4);  
    }else{
        $farmer_code = $_POST['farmer_code'];
    }

    if($farmer_code != '' && isset($_POST['farmer_prefix'])){
        $check = true;
        $data['farmer_code'] = $farmer_code;
        $data['farmer_prefix'] = $_POST['farmer_prefix'];  
        $data['farmer_name'] = $_POST['farmer_name'];
        $data['farmer_lastname'] = $_POST['farmer_lastname'];
        $data['farmer_address'] = $_POST['farmer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['farmer_zipcode'] = $_POST['farmer_zipcode'];
        $data['farmer_mobile'] = $_POST['farmer_mobile'];
        $data['farmer_line'] = $_POST['farmer_line'];
        $data['addby'] = $login_user['user_code'];

        $img_upload = ['profile_image'];

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
            $result = $farmer_model->insertFarmer($data);

            if($result){
                ?> <script> window.location="index.php?app=farmer" </script> <?php
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
        ?> <script> window.location="index.php?app=farmer" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['farmer']['edit']){
    if(isset($_POST['farmer_code'])){
        $check = true;
        $data = [];  
        $data['farmer_code'] = $_POST['farmer_code'];
        $data['farmer_prefix'] = $_POST['farmer_prefix'];
        $data['farmer_name'] = $_POST['farmer_name'];
        $data['farmer_lastname'] = $_POST['farmer_lastname'];
        $data['farmer_address'] = $_POST['farmer_address'];
        $data['village_id'] = $_POST['village_id'];
        $data['farmer_zipcode'] = $_POST['farmer_zipcode'];
        $data['farmer_mobile'] = $_POST['farmer_mobile'];
        $data['farmer_line'] = $_POST['farmer_line'];
        $data['updateby'] = $login_user['user_code'];

        $img_upload = ['profile_image'];

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
            $result = $farmer_model->updateFarmerByCode($_POST['farmer_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=farmer" </script> <?php
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
        ?> <script> window.location="index.php?app=farmer" </script> <?php
    }
}else if ($_GET['action'] == 'detail'){
    $farmer = $farmer_model->getFarmerByCode($farmer_code);
    require_once($path.'detail.inc.php');
}else{
    $farmer = $farmer_model->getFarmerBy();
    require_once($path.'view.inc.php');
}
?>