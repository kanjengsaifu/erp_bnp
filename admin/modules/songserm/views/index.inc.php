<?php
require_once('../models/SongsermModel.php');
require_once('../models/SongsermStatusModel.php');
require_once('../models/AddressModel.php');

$path = "modules/songserm/views/";

$songserm_model = new SongsermModel;
$songserm_status_model = new SongsermStatusModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";

$target_dir = "../upload/songserm/";

$songserm_code = $_GET['code'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['songserm']['add']){ 
    $songserm_status = $songserm_status_model->getSongsermStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['songserm']['edit']){
    $songserm = $songserm_model->getSongsermByCode($songserm_code);
    $songserm_status = $songserm_status_model->getSongsermStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($songserm['province_id']);
    $add_district = $address_model->getDistricByAmphurID($songserm['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['songserm']['delete']){
    $songserm = $songserm_model->getSongsermByCode($songserm_code);

    $img_delete = ['songserm_image','id_card_image','house_regis_image','account_image'];

    for ($i=0; $i<count($img_delete); $i++){
        if ($songserm[$img_delete[$i]] != ''){
            $target_file = $target_dir .$songserm[$img_delete[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }

    $result = $songserm_model->deleteSongsermByCode($songserm_code);

    ?> <script> window.location="index.php?app=songserm"</script> <?php
}else if ($_GET['action'] == 'add'&&$menu['songserm']['add']){
    $songserm_code = "CT";
    $songserm_code = $songserm_model->getSongsermLastCode($songserm_code,4);  

    if($songserm_code != '' && isset($_POST['songserm_prefix'])){
        $check = true;
        $data['songserm_code'] = $songserm_code;
        $data['songserm_prefix'] = $_POST['songserm_prefix'];  
        $data['songserm_name'] = $_POST['songserm_name'];
        $data['songserm_lastname'] = $_POST['songserm_lastname'];
        $data['songserm_mobile'] = $_POST['songserm_mobile'];
        $data['songserm_address'] = $_POST['songserm_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['songserm_zipcode'] = $_POST['songserm_zipcode'];
        $data['songserm_status_code'] = $_POST['songserm_status_code']; 

        $img_upload = ['songserm_image','id_card_image','house_regis_image','account_image'];

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
            $result = $songserm_model->insertSongserm($data);

            if($result){
                ?> <script> window.location="index.php?app=songserm" </script> <?php
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
        ?> <script> window.location="index.php?app=songserm" </script> <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['songserm']['edit']){
    if(isset($_POST['songserm_code'])){
        $check = true;
        $data = [];  
        $data['songserm_code'] = $_POST['songserm_code'];
        $data['songserm_prefix'] = $_POST['songserm_prefix'];
        $data['songserm_name'] = $_POST['songserm_name'];
        $data['songserm_lastname'] = $_POST['songserm_lastname'];
        $data['songserm_mobile'] = $_POST['songserm_mobile'];
        $data['songserm_address'] = $_POST['songserm_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['songserm_zipcode'] = $_POST['songserm_zipcode'];
        $data['songserm_status_code'] = $_POST['songserm_status_code']; 

        $img_upload = ['songserm_image','id_card_image','house_regis_image','account_image'];

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
            $result = $songserm_model->updateSongsermByCode($_POST['songserm_code'],$data);

            if($result){
                ?> <script> window.location="index.php?app=songserm" </script> <?php
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
        ?> <script> window.location="index.php?app=songserm" </script> <?php
    }
}else if ($_GET['action'] == 'profile'){
    $songserm = $songserm_model->getSongsermByCode($songserm_code);
    $songserm_status = $songserm_status_model->getSongsermStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($songserm['province_id']);
    $add_district = $address_model->getDistricByAmphurID($songserm['amphur_id']); 
    require_once($path.'detail.inc.php');
}else {
    $songserm = $songserm_model->getSongsermBy();
    require_once($path.'view.inc.php');
}
?>