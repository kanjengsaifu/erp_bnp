<?php 
require_once('../models/LicenseModel.php'); 
require_once('../models/MenuModel.php'); 
require_once('../models/LicensePermissionModel.php'); 

$path = "modules/license/views/";
 
$license_model = new LicenseModel; 
$license_permission_model = new LicensePermissionModel; 
$menus_model = new MenuModel; 

 
$license_code = $_GET['code'];
// if ($_GET['action'] == 'insert'&&$menus['license']['add']==1){  
if ($_GET['action'] == 'insert'){  
    $menus = $menus_model->getMenuBy();
    require_once($path.'insert.inc.php');
// }else if ($_GET['action'] == 'update'&&$menus['license']['edit']==1){ 
}else if ($_GET['action'] == 'update'){ 
    
    $menus = $menus_model->getMenuBy();
    $license = $license_model->getLicenseByID($license_code);
    $license_permission = $license_permission_model->getLicensePermissionByID($license_code);
    // echo '<pre>';
    // print_r($license_permission);
    // echo '</pre>';
    require_once($path.'update.inc.php');
// }else if ($_GET['action'] == 'delete'&&$menus['license']['delete']==1){  
}else if ($_GET['action'] == 'delete'){  
    $license_permission_model->deleteLicensePermissionByID($license_code);
    $license_model->deleteLicenseByID($license_code);
    $license = $license_model->getLicenseBy();
    require_once($path.'view.inc.php');
// }else if ($_GET['action'] == 'add'&&$menus['license']['add']==1){
}else if ($_GET['action'] == 'add'){


 
    $check_result = $license_model->checkLicenseBy(trim($_POST['license_name']),'');
    // echo '<pre>';
    // print_r($check_result);
    // echo '</pre>';
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
                if($_POST['license_permission_view_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_view'] = $_POST['license_permission_view_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_view'] = 0; 
                }
                if($_POST['license_permission_add_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_add'] = $_POST['license_permission_add_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_add'] = 0; 
                }
                if($_POST['license_permission_edit_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_edit'] = $_POST['license_permission_edit_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_edit'] = 0; 
                }
                if($_POST['license_permission_cancel_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_cancel'] = $_POST['license_permission_cancel_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_cancel'] = 0; 
                }
                if($_POST['license_permission_delete_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_delete'] = $_POST['license_permission_delete_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_delete'] = 0; 
                }
    
                $license_permission_model->insertLicensePermission($data_per[$i]);
    
            } 
        } 
        ?>
        <script>window.location="index.php?app=license"</script>
        <?php
        
    }else{
        // echo '<script>alert("ข้อมูลซ้ำ");window.history.back();</script>';
    }
    
    

// }else if ($_GET['action'] == 'edit'&&$menus['license']['edit']==1){
}else if ($_GET['action'] == 'edit'){
    $license_code = $_POST['license_code'];
    $check_result = $license_model->checkLicenseBy(trim($_POST['license_name']),trim($_POST['license_code']));
    if(count($check_result)<1){
        // echo $license_code;
        $data = [];
        $data['license_name'] = $_POST['license_name']; 
        $license_model->updateLicenseByID($license_code,$data);
        if($license_code!=false){
            $menus = $menus_model->getMenuBy();
            $data_per =[];
            for($i = 0;$i<count($menus);$i++){  
                $data_per[$i]['license_permission_code'] = $_POST['license_permission_code_'.$menus[$i]['menu_code']]; 
                $data_per[$i]['license_code'] = $license_code; 
                $data_per[$i]['menu_code'] = $menus[$i]['menu_code']; 
                if($_POST['license_permission_view_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_view'] = $_POST['license_permission_view_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_view'] = 0; 
                }
                if($_POST['license_permission_add_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_add'] = $_POST['license_permission_add_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_add'] = 0; 
                }
                if($_POST['license_permission_edit_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_edit'] = $_POST['license_permission_edit_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_edit'] = 0; 
                }
                if($_POST['license_permission_cancel_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_cancel'] = $_POST['license_permission_cancel_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_cancel'] = 0; 
                }
                if($_POST['license_permission_delete_'.$menus[$i]['menu_code']]!=''){
                    $data_per[$i]['license_permission_delete'] = $_POST['license_permission_delete_'.$menus[$i]['menu_code']]; 
                }else{
                    $data_per[$i]['license_permission_delete'] = 0; 
                }
                if($data_per[$i]['license_permission_code']!=''){
                    $license_permission_model->updateLicensePermissionByID($data_per[$i]['license_permission_code'],$data_per[$i]);
                }else{
                    
                    $license_permission_code = "LP";
                    $license_permission_code = $license_permission_model->getLicensePermissionLastCode($license_permission_code,5); 
                    $data_per[$i]['license_permission_code'] = $license_permission_code; 
                    $license_permission_model->insertLicensePermission($data_per[$i]);
                }
                // echo '<pre>';
                // print_r($data_per[$i]['license_permission_code']);
                // echo '</pre>';

            } 
        } 
        // echo '<pre>';
        // print_r($data_per);
        // echo '</pre>';
        ?>
        <script>window.location="index.php?app=license&action=update&code=<?PHP echo $license_code;?>"</script>
        <?php
    
    }else{
        echo '<script>alert("ข้อมูลซ้ำ");window.history.back();</script>';
    } 
// }else if ($menus['license']['view']==1 ){
}else{
    $license = $license_model->getLicenseBy();
    require_once($path.'view.inc.php');
}
?>