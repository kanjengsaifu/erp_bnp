<?php 
require_once('../models/LicenseModel.php'); 
require_once('../models/MenuModel.php'); 
require_once('../models/LicensePermissionModel.php'); 

$path = "modules/license/views/";
 
$license_model = new LicenseModel; 
$license_permission_model = new LicensePermissionModel; 
$menus_model = new MenuModel; 
 
$license_code = $_GET['code'];

if ($_GET['action'] == 'insert' && $menu['license']['add']){  
    $menus = $menus_model->getMenuBy();
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update' && $menu['license']['edit']){ 
    $menus = $menus_model->getMenuBy();
    $license = $license_model->getLicenseByCode($license_code);
    $license_permission = $license_permission_model->getLicensePermissionByCode($license_code);

    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete' && $menu['license']['delete']){  
    $license_permission_model->deleteLicensePermissionByCode($license_code);
    $license_model->deleteLicenseByCode($license_code);
    $license = $license_model->getLicenseBy();
    require_once($path.'view.inc.php');
}else if ($_GET['action'] == 'add' && $menu['license']['add']){
    $check_result = $license_model->checkLicenseBy(trim($_POST['license_name']),'');

    if(count($check_result)<1){
        $license_code = "L";
        $license_code = $license_model->getLicenseLastCode($license_code,3);  
        $data = [];
        $data['license_code'] = $license_code; 
        $data['license_name'] = $_POST['license_name']; 
        $license_code = $license_model->insertLicense($data);

        if($license_code!=false){
            $menus = $menus_model->getMenuBy();
            $data_per =[];
            for($i = 0;$i<count($menus);$i++){ 
                $license_permission_code = "LP";
                $license_permission_code = $license_permission_model->getLicensePermissionLastCode($license_permission_code,5);  
                $data_per[$i]['license_permission_code'] = $license_permission_code; 
                $data_per[$i]['license_code'] = $license_code; 
                $data_per[$i]['menu_code'] = $menus[$i]['menu_code']; 
                $data_per[$i]['permission_view'] = $_POST['permission_view_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_add'] = $_POST['permission_add_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_edit'] = $_POST['permission_edit_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_approve'] = $_POST['permission_approve_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_cancel'] = $_POST['permission_cancel_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_delete'] = $_POST['permission_delete_'.$menus[$i]['menu_code']]; 

                $license_permission_model->insertLicensePermission($data_per[$i]);
            } 
        } 

        ?> <script>window.location="index.php?app=license"</script> <?php
    }else{
        // echo '<script>alert("ข้อมูลซ้ำ");window.history.back();</script>';
    }
}else if ($_GET['action'] == 'edit' && $menu['license']['edit']){
    $license_code = $_POST['license_code'];
    $check_result = $license_model->checkLicenseBy(trim($_POST['license_name']),trim($_POST['license_code']));

    if(count($check_result)<1){
        $data = [];
        $data['license_name'] = $_POST['license_name']; 
        $license_model->updateLicenseByCode($license_code,$data);
        if($license_code!=false){
            $menus = $menus_model->getMenuBy();
            $data_per =[];
            for($i = 0;$i<count($menus);$i++){  
                $data_per[$i]['license_permission_code'] = $_POST['license_permission_code_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['license_code'] = $license_code; 
                $data_per[$i]['menu_code'] = $menus[$i]['menu_code']; 
                $data_per[$i]['permission_view'] = $_POST['permission_view_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_add'] = $_POST['permission_add_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_edit'] = $_POST['permission_edit_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_approve'] = $_POST['permission_approve_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_cancel'] = $_POST['permission_cancel_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['permission_delete'] = $_POST['permission_delete_'.$menus[$i]['menu_code']]; 

                if($data_per[$i]['license_permission_code']!=''){
                    $license_permission_model->updateLicensePermissionByCode($data_per[$i]['license_permission_code'],$data_per[$i]);
                }else{
                    $license_permission_code = "LP";
                    $license_permission_code = $license_permission_model->getLicensePermissionLastCode($license_permission_code,5); 
                    $data_per[$i]['license_permission_code'] = $license_permission_code; 
                    $license_permission_model->insertLicensePermission($data_per[$i]);
                }
            } 
        } 

        ?>
        <script>window.location="index.php?app=license&action=update&code=<?PHP echo $license_code;?>"</script>
        <?php
    
    }else{
        echo '<script>alert("ข้อมูลซ้ำ");window.history.back();</script>';
    } 
}else{
    $license = $license_model->getLicenseBy();
    require_once($path.'view.inc.php');
}
?>