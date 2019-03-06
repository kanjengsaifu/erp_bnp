<?php  
require_once('../models/SupplierModel.php');   
$path = "modules/supplier/views/";
$target_dir = "../upload/supplier/"; 

$supplier_model = new SupplierModel;
   
$supplier_code = $_GET['code']; 




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





if(!isset($_GET['action'])&&$menu['supplier']['view']==1){

    $supplier = $supplier_model->getSupplierBy();
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'detail'){ 
    
    $supplier = $supplier_model->getSupplierByCode($supplier_code);
    require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'insert'  &&$menu['supplier']['add']==1){
 
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'  &&$menu['supplier']['edit']==1){

    
    $supplier = $supplier_model->getSupplierByCode($supplier_code);
    // $user = $model_user->getUserByCode($supplier['user_code']); 
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete'  &&$menu['supplier']['delete']==1){

    $supplier = $supplier_model->getSupplierByCode($_GET['code']);
    if(count($supplier) > 0){
       
        $target_file = $target_dir . $supplier["supplier_logo"];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        $user = $supplier_model->deleteSupplierById($_GET['code']);
    }
    
?>
    <script>window.location="index.php?app=supplier"</script>
<?php

}else if ($_GET['action'] == 'add'&&$menu['supplier']['add']==1){
    
    $supplier_code = "SP";
    $supplier_code = $supplier_model->getSupplierLastCode($supplier_code,3);  
    if($supplier_code!=""){
        $data = []; 
        $data['supplier_code'] = $supplier_code;
        $data['supplier_name_th'] = $_POST['supplier_name_th'];
        $data['supplier_name_en'] = $_POST['supplier_name_en']; 
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['supplier_address_1'] = $_POST['supplier_address_1'];
        $data['supplier_address_2'] = $_POST['supplier_address_2'];
        $data['supplier_address_3'] = $_POST['supplier_address_3'];
        $data['supplier_zipcode'] = $_POST['supplier_zipcode'];
        $data['supplier_tel'] = $_POST['supplier_tel'];
        $data['supplier_fax'] = $_POST['supplier_fax'];
        $data['supplier_email'] = $_POST['supplier_email']; 
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_remark'] = $_POST['supplier_remark']; 
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];
        $data['account_id'] = $_POST['account_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""){
            $data['supplier_logo'] = 'default.png';
        }else{
            

            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['supplier_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;



            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["supplier_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["supplier_logo"]["tmp_name"], $target_file)) {
                
                
                //-----------------------------------
                $data['supplier_logo'] = $date.$newname;
                //-----------------------------------



                
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
            ?>
                <script>
                    alert('<?php echo $error_msg; ?>');
                    window.history.back();
                </script>
            <?php
        }else{
            $code = $supplier_model->insertSupplier($data);
            if($code != false){
                // $notification_model->setNotification("Supplier Approve","Supplier Approve <br>Name. ".$data['supplier_name_en']."","index.php?app=supplier&action=detail&code=$code","license_manager_page","'High'"); 
                ?>
                    <script>
                        window.location="index.php?app=supplier&action=update&code=<?php echo $code;?>"
                    </script>
                <?php
            }else{
                ?>
                    <script>
                        window.location="index.php?app=supplier&action=add"
                    </script>
                <?php
            }
                    
        }
    }else{
        ?>
            <script>
                window.location="index.php?app=supplier"
            </script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'&&$menu['supplier']['edit']==1){
    if(isset($_POST['supplier_code'])){
        $data = []; 
        $data['supplier_code'] = $_POST['supplier_code'];
        $data['supplier_name_th'] = $_POST['supplier_name_th'];
        $data['supplier_name_en'] = $_POST['supplier_name_en']; 
        $data['supplier_tax'] = $_POST['supplier_tax'];
        $data['supplier_address_1'] = $_POST['supplier_address_1'];
        $data['supplier_address_2'] = $_POST['supplier_address_2'];
        $data['supplier_address_3'] = $_POST['supplier_address_3'];
        $data['supplier_zipcode'] = $_POST['supplier_zipcode'];
        $data['supplier_tel'] = $_POST['supplier_tel'];
        $data['supplier_fax'] = $_POST['supplier_fax'];
        $data['supplier_email'] = $_POST['supplier_email']; 
        $data['supplier_branch'] = $_POST['supplier_branch'];
        $data['supplier_remark'] = $_POST['supplier_remark']; 
        $data['credit_day'] = $_POST['credit_day'];
        $data['condition_pay'] = $_POST['condition_pay'];
        $data['pay_limit'] = $_POST['pay_limit'];
        $data['account_id'] = $_POST['account_id'];
        $data['vat_type'] = $_POST['vat_type'];
        $data['vat'] = $_POST['vat'];
        $data['currency_id'] = $_POST['currency_id'];

        $check = true;

        if($_FILES['supplier_logo']['name'] == ""  ){
            $data['supplier_logo'] = $_POST['supplier_logo_o'];
        }else  {
            

            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['supplier_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["supplier_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["supplier_logo"]["tmp_name"], $target_file)) {

                //-----------------------------------
                $data['supplier_logo'] = $date.$newname;
                //-----------------------------------

                $target_file = $target_dir . $_POST["supplier_logo_o"];
                if($_POST["supplier_logo_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            

            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $code = $supplier_model->updateSupplierByCode($_POST['supplier_code'],$data);
            if($code != false){
    ?>
            <script>window.location="index.php?app=supplier&action=update&code=<?php echo $_POST['supplier_code'];?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=supplier&action=update&code=<?php echo $_POST['supplier_code'];?>"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=supplier"</script>
        <?php
    } 
 
}else{

    $supplier = $supplier_model->getSupplierBy();
    require_once($path.'view.inc.php');

}





?>
