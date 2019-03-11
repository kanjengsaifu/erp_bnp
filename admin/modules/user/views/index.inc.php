<?php
require_once('../models/UserModel.php');
require_once('../models/UserStatusModel.php');
require_once('../models/LicenseModel.php'); 
require_once('../models/UserPositionModel.php');  
require_once('../models/AddressModel.php'); 

$path = "modules/user/views/";

$user_model = new UserModel;
$user_status_model = new UserStatusModel; 
$license_model = new LicenseModel; 
$user_position_model = new UserPositionModel; 
$address_model = new AddressModel; 

$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";
$target_dir = "../img_upload/user/";
$user_code = $_GET['code'];
if ($_GET['action'] == 'insert'&&$menu['user']['add']==1){ 
    $user_code = "U";
    $user_code = $user_model->getUserLastCode($user_code,4);  
    $license = $license_model->getLicenseBy();
    $user_position = $user_position_model->getUserPositionBy();
    $user_status = $user_status_model->getUserStatusBy();
    $add_province = $address_model->getProvinceBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'&&$menu['user']['edit']==1){
    $user = $user_model->getUserByCode($user_code);
    $license = $license_model->getLicenseBy();
    $user_position = $user_position_model->getUserPositionBy();
    $user_status = $user_status_model->getUserStatusBy();
    $add_province = $address_model->getProvinceBy();
    $add_amphur = $address_model->getAmphurByProviceID($user['province_id']);
    $add_district = $address_model->getDistrictByAmphurID($user['amphur_id']); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['user']['delete']==1){
    $user = $user_model->getUserByCode($user_code);
    $target_file = $target_dir .$user['user_image'];
    if (file_exists($target_file)&& $_POST['user_image_o']!='') {
        unlink($target_file);
    }
    $user = $user_model->deleteUserByCode($user_code);
    ?>
    <script> window.location="index.php?app=user"</script>
    <?php

}else if ($_GET['action'] == 'add'&&$menu['user']['add']==1){ 
    
    if ($_POST['user_code'] == ''){
        $user_code = "U".date('y').date('m').date('d');
        $user_code = $user_model->getUserLastCode($user_code,3);  
    }else{
        $user_code = $_POST['user_code'];
    }
    if($user_code!=false){
        $data['user_code'] = $user_code;
        $data['user_prefix'] = $_POST['user_prefix'];
        $data['user_name'] = $_POST['user_name'];
        $data['user_lastname'] = $_POST['user_lastname'];
        $data['user_mobile'] = $_POST['user_mobile'];
        $data['user_email'] = $_POST['user_email'];
        $data['user_username'] = $_POST['user_username'];
        $data['user_password'] = $_POST['user_password'];
        $data['user_address'] = $_POST['user_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['user_zipcode'] = $_POST['user_zipcode'];
        $data['user_position_code'] = $_POST['user_position_code'];
        $data['license_code'] = $_POST['license_code'];
        $data['user_status_code'] = $_POST['user_status_code']; 
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        
        $user = $user_model->insertUser($data);
        
        
        if($user != false){
            ?>
            <script>window.location="index.php?app=user&action=update&code=<?php echo $user_code?>"</script>
            <?php
        }else{
            ?>
            <script>window.location="index.php?app=user"</script>
            <?php
        }
    }else{
        ?>
        <script>
        window.location="index.php?app=user"
        </script>
        <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['user']['edit']==1){
    if(isset($_POST['user_code'])){
        $data = [];  
        $data['user_code'] = $_POST['user_code'];
        $data['user_prefix'] = $_POST['user_prefix'];
        $data['user_name'] = $_POST['user_name'];
        $data['user_lastname'] = $_POST['user_lastname'];
        $data['user_mobile'] = $_POST['user_mobile'];
        $data['user_email'] = $_POST['user_email'];
        $data['user_username'] = $_POST['user_username'];
        $data['user_password'] = $_POST['user_password'];
        $data['user_address'] = $_POST['user_address'];
        $data['province_id'] = $_POST['province_id'];
        $data['amphur_id'] = $_POST['amphur_id'];
        $data['district_id'] = $_POST['district_id'];
        $data['user_zipcode'] = $_POST['user_zipcode'];
        $data['user_position_code'] = $_POST['user_position_code'];
        $data['license_code'] = $_POST['license_code'];
        $data['user_status_code'] = $_POST['user_status_code']; 
        
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        $user = $user_model->updateUserByCode($_POST['user_code'],$data);

        if($user){  
?>
        <script>
        window.location="index.php?app=user"
        </script>
<?php
        }else{
?>
        <script>
        window.location="index.php?app=user"
        </script>
<?php
        }
        
    }else{
        ?>
    <script>
    window.location="index.php?app=user"
    </script>
        <?php
    }
}else if ($menu['user']['view']==1 ){
    $user = $user_model->getUserBy();
    // echo '<pre>';
    // print_r($user);
    // echo '</pre>';
    require_once($path.'view.inc.php');
}
?>