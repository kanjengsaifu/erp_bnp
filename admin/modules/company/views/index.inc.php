<?php
session_start();
$company = $_SESSION['company'];
require_once('../models/UserModel.php');
require_once('../models/CompanyModel.php');   
$path = "modules/company/views/";
$target_dir = "../upload/company/";
$user_model = new UserModel;
$company_model = new CompanyModel;  
$company_code = '1'; 
// $company_code = $_GET['code']; 




//---------------------ฟังก์ชั่นวันที่------------------------------------
date_default_timezone_set("Asia/Bangkok");
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";
//---------------------------------------------------------------------


//-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
$numrand = (mt_rand());
//-----------------------------------------------




if ($_GET['action'] == 'insert'&&$menu['company']['add']==1){ 
    require_once($path.'insert.inc.php'); 
}else if ($_GET['action'] == 'update'&&$menu['company']['edit']==1){
       
    $company = $company_model->getCompanyByID($company_code); 
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['company']['delete']==1){ 
    $company = $company_model->getCompanyByID($company_code); 
    $input_image = array(
        "company_image",
        "company_image_rectangle" 
    );  
    for($i = 0;$i<count($input_image);$i++){
        if($company[$input_image[$i]] != ""){
            $target_file = $target_dir .$company[$input_image[$i]];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        } 
    }
   


    $company_model->deleteCompanyByID($company_code); 
    ?>
    <script>
        window.location="index.php?app=company";
    </script>
    <?php
}else if ($_GET['action'] == 'add'&&$menu['company']['add']==1){


    
    $company_code = "C";
    $company_code = $company_model->getCompanyLastCode($company_code,3);  
    if($company_code!=false){
        $data['company_code'] = $company_code;
        $data['company_name_th'] = $_POST['company_name_th'];
        $data['company_name_en'] = $_POST['company_name_en'];
        $data['company_address_1'] = $_POST['company_address_1']; 
        $data['company_address_2'] = $_POST['company_address_2']; 
        $data['company_address_3'] = $_POST['company_address_3']; 
        $data['company_tax'] = $_POST['company_tax']; 
        $data['company_tel'] = $_POST['company_tel']; 
        $data['company_fax'] = $_POST['company_fax']; 
        $data['company_email'] = $_POST['company_email']; 
        $data['company_branch'] = $_POST['company_branch'];  
        $data['company_vat_type'] = $_POST['company_vat_type']; 
        $data['updateby'] = $login_user['user_code'];  
        $check = true; 

        

        $input_image = array(
            "company_image",
            "company_image_rectangle" 
        ); 
    
        for($i = 0;$i<count($input_image);$i++){
            if($_FILES[$input_image[$i]]['name'] == ""){
                $data[$input_image[$i]] = $_POST[$input_image[$i].'_o'];
            }else {
                $target_file = $target_dir .$date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"]));
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_msg =  "ขอโทษด้วย. มีไฟล์นี้ในระบบแล้ว";
                    $check = false;
                }else if ($_FILES[$input_image[$i]]["size"] > 5000000) {
                    $error_msg = "ขอโทษด้วย. ไฟล์ของคุณต้องมีขนาดน้อยกว่า 5 MB.";
                    $check = false;
                }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                    $error_msg = "ขอโทษด้วย. ระบบสามารถอัพโหลดไฟล์นามสกุล JPG, JPEG, PNG & GIF เท่านั้น.";
                    $check = false;
                }else if (move_uploaded_file($_FILES[$input_image[$i]]["tmp_name"], $target_file)) {
                    $data[$input_image[$i]] = $date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"]));
                    $target_file = $target_dir . $_POST[$input_image[$i].'_o'];
                    if (file_exists($target_file)&&$_POST[$input_image[$i].'_o']!='') {
                        unlink($target_file);
                    }
                }else{
                    $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                    $check = false;
                } 
            }
        }    
        if($check == false){
                
            ?>
            <script>
                alert('<? echo $error_msg;?>');
                window.history.back();
            </script>
            <?PHP
        }else{
            
            $company = $company_model->insertCompany($data);    
            if($company!=''){ 
                ?> 
                <script>
                    window.location="index.php?app=company&action=update&code=<?=$company?>"
                </script> 
                <?php
            }else{
                $result = "ไม่สามารถบันทึกข้อมูลได้";
                ?>
                <script>
                    alert('<? echo $result;?>');
                    window.history.back();
                </script>
                <?PHP
            } 
        } 
    }else{
        ?>
            <script>
            window.location="index.php?app=company"
            </script>
        <?php
    }




}else if ($_GET['action'] == 'edit'&&$menu['company']['edit']==1){
    
         
    if($_POST['company_code']!=""){
        $data['company_code'] = $_POST['company_code'];
        $data['company_name_th'] = $_POST['company_name_th'];
        $data['company_name_en'] = $_POST['company_name_en'];
        $data['company_address_1'] = $_POST['company_address_1']; 
        $data['company_address_2'] = $_POST['company_address_2']; 
        $data['company_address_3'] = $_POST['company_address_3']; 
        $data['company_tax'] = $_POST['company_tax']; 
        $data['company_tel'] = $_POST['company_tel']; 
        $data['company_fax'] = $_POST['company_fax']; 
        $data['company_email'] = $_POST['company_email']; 
        $data['company_branch'] = $_POST['company_branch'];  
        $data['company_vat_type'] = $_POST['company_vat_type']; 
        $data['updateby'] = $login_user['user_code'];  
        $check = true; 

        

        $input_image = array(
            "company_image",
            "company_image_rectangle" 
        ); 
    
        for($i = 0;$i<count($input_image);$i++){
            if($_FILES[$input_image[$i]]['name'] == ""){
                $data[$input_image[$i]] = $_POST[$input_image[$i].'_o'];
            }else {
                $target_file = $target_dir .$date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"]));
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 
                if (file_exists($target_file)) {
                    $error_msg =  "ขอโทษด้วย. มีไฟล์นี้ในระบบแล้ว";
                    $check = false;
                }else if ($_FILES[$input_image[$i]]["size"] > 5000000) {
                    $error_msg = "ขอโทษด้วย. ไฟล์ของคุณต้องมีขนาดน้อยกว่า 5 MB.";
                    $check = false;
                }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                    $error_msg = "ขอโทษด้วย. ระบบสามารถอัพโหลดไฟล์นามสกุล JPG, JPEG, PNG & GIF เท่านั้น.";
                    $check = false;
                }else if (move_uploaded_file($_FILES[$input_image[$i]]["tmp_name"], $target_file)) {
                    $data[$input_image[$i]] = $date.'-'.strtolower(basename($_FILES[$input_image[$i]]["name"]));
                    $target_file = $target_dir . $_POST[$input_image[$i].'_o'];
                    if (file_exists($target_file)&&$_POST[$input_image[$i].'_o']!='') {
                        unlink($target_file);
                    }
                }else{
                    $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                    $check = false;
                } 
            }
        }    
        if($check == false){
                
            ?>
            <script>
                alert('<? echo $error_msg;?>');
                window.history.back();
            </script>
            <?PHP
        }else{
            
            $company = $company_model->updateCompanyByID($data['company_code'],$data);    
            if($company!=false){ 
                ?> 
                <script>
                    window.location="index.php?app=company&action=update&code=<?=$company?>"
                </script> 
                <?php
            }else{
                $result = "ไม่สามารถบันทึกข้อมูลได้";
                ?>
                <script>
                    alert('<? echo $result;?>');
                    window.history.back();
                </script>
                <?PHP
            } 
        } 
    }else{
        ?>
            <script>
            window.location="index.php?app=company"
            </script>
        <?php
    }

}else if ($menu['company']['view']==1 ){
    $company = $company_model->getCompanyByID($company_code); 
    require_once($path.'update.inc.php');
    // $company = $company_model->getCompanyBy(); 
    // require_once($path.'view.inc.php');
}


?>