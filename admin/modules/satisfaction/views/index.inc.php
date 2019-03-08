<?php
 
$path = "modules/satisfaction/views/";

if ($_GET['action'] == 'insert' && $menu['satisfaction']['add']==1 ){
 
    // $satisfaction_type = $satisfaction_type_model->getSatisfactionTypeBy(); 
    // $unit = $unit_model->getUnitBy(); 
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['satisfaction']['edit']==1 ){

    
    $satisfaction = $model_satisfaction->getSatisfactionByCode($satisfaction_code);
    // $satisfaction_group = $model_satisfaction_group->getSatisfactionGroupBy();
    // $satisfaction_type = $satisfaction_type_model->getSatisfactionTypeBy();
    // $satisfaction_category = $model_satisfaction_category->getSatisfactionCategoryBy();
    $unit = $unit_model->getUnitBy();
    // $account = $account_model->getAccountAll();

    // $satisfaction_customers = $model_satisfaction_customer->getSatisfactionCustomerBy($satisfaction_code);
    $satisfaction_suppliers = $model_satisfaction_supplier->getSatisfactionSupplierBySatisfactionCode($satisfaction_code);
    // echo '<pre>';
    // print_r($satisfaction_code);
    // echo '</pre>';
    

    // $customer = $model_customer->getCustomerBy();
    $supplier = $supplier_model->getSupplierBy();

    if($satisfaction_supplier_code != ''){
        $satisfaction_supplier = $model_satisfaction_supplier->getSatisfactionSupplierByCode($satisfaction_supplier_code);
    }

    // if($satisfaction_customer_id != ''){
    //     $satisfaction_customer = $model_satisfaction_customer->getSatisfactionCustomerByCode($satisfaction_customer_id);
    // }

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['satisfaction']['delete']==1 ){

    if($satisfaction_supplier_code != ''){
        $model_satisfaction_supplier->deleteSatisfactionSupplierByCode($satisfaction_supplier_code);    
        ?>
        <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code;?>"</script>
        <?php
    }else{
        $model_satisfaction->deleteSatisfactionByCode($satisfaction_code);     
        ?>
        <script>window.location="index.php?app=satisfaction"</script>
        <?php
    }


}else if ($_GET['action'] == 'add' && $menu['satisfaction']['add']==1 ){
    $satisfaction_code = "SF";
    $satisfaction_code = $model_satisfaction->getSatisfactionLastCode($satisfaction_code,3);  
    if($satisfaction_code!=""){

        $data = [];
        $data['satisfaction_code'] = $satisfaction_code;
        $data['member_type_code'] = $_POST['member_type_code'];
        $data['member_code'] = $_POST['member_code']; 
        $data['contact_way_code'] = $_POST['contact_way_code'];
        $data['contact_type_code'] = $_POST['contact_type_code'];
        $data['satisfaction_detail'] = $_POST['satisfaction_detail'];
        $data['satisfaction_score'] = $_POST['satisfaction_score']; 
        $data['user_code'] = $login_user['user_code'];  
        
        $check = true;

  
        $code = $model_satisfaction->insertSatisfaction($data);

        if($code != false){
            ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $code?>"</script>
            <?php
        }else{
            ?>
            <script>window.location="index.php?app=satisfaction"</script>
            <?php
        }
          
     
    }else{
        ?>
        <script>window.location="index.php?app=satisfaction"</script>
        <?php
    }
     
    
}else if ($_GET['action'] == 'edit' && $menu['satisfaction']['edit']==1 ){
    
    if(isset($_POST['satisfaction_name'])){
        $data = [];  
        $data['satisfaction_name'] = $_POST['satisfaction_name'];
        $data['satisfaction_logo'] = $_POST['satisfaction_logo'];
        // $data['satisfaction_quantity_per_unit'] = preg_replace('/\D/', '', $_POST['satisfaction_quantity_per_unit']);
        $data['unit_code'] = $_POST['unit_code'];
        $data['satisfaction_minimum_stock'] = $_POST['satisfaction_minimum_stock'];
        $data['satisfaction_maximum_stock'] = $_POST['satisfaction_maximum_stock'];
        $data['satisfaction_description'] = $_POST['satisfaction_description']; 
        $data['updateby'] = $login_user['user_code'];  


        $check = true;
 

        
        if($_FILES['satisfaction_logo']['name'] == ""){
            $data['satisfaction_logo'] = $_POST['satisfaction_logo_o'];
        }else {
            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['satisfaction_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------


            //-----------------------------------------
            $target_file = $target_dir .$date.$newname;
            //-----------------------------------------

            
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["satisfaction_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["satisfaction_logo"]["tmp_name"], $target_file)) {

                
                //--------------------------------------------------------------------
                $data['satisfaction_logo'] = $date.$newname;
                //--------------------------------------------------------------------

                $target_file = $target_dir . $_POST["satisfaction_logo_o"];
                if (file_exists($target_file)) {
                    unlink($target_file);
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
            $result = $model_satisfaction->updateSatisfactionByCode($_POST['satisfaction_code'],$data);

            if($result){
            ?>
            <script>
            window.location="index.php?app=satisfaction&action=update&code=<?php echo $_POST['satisfaction_code'];?>"
            </script>
            <?php
            }else{
            ?>
            <script>
            window.location="index.php?app=satisfaction&action=update&code=<?php echo $_POST['satisfaction_code'];?>"
            </script>
            <?php
            }
                    
        }

    }else{
        ?>
    <script>window.location="index.php?app=satisfaction"</script>
        <?php
    }
    
        
        
    
} 

else if ($_GET['action'] == 'add_supplier' && $menu['satisfaction']['edit']==1){
    
    $satisfaction_supplier_code = "MATS";
    $satisfaction_supplier_code = $model_satisfaction_supplier->getSatisfactionSupplierLastCode($satisfaction_supplier_code,3);  
    if($satisfaction_supplier_code!=''&&$satisfaction_code!=''){

        $data = [];
        $data['satisfaction_supplier_code'] = $satisfaction_supplier_code;
        $data['satisfaction_code'] = $satisfaction_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['satisfaction_supplier_buyprice'] = $_POST['satisfaction_supplier_buyprice'];
        $data['satisfaction_supplier_lead_time'] = $_POST['satisfaction_supplier_lead_time'];
        // $data['satisfaction_supplier_status'] = $_POST['satisfaction_supplier_status']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $model_satisfaction_supplier->insertSatisfactionSupplier($data); 
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php 
    }else{
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_supplier' && $menu['satisfaction']['edit']==1 ){
    
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['satisfaction_supplier_buyprice'] = $_POST['satisfaction_supplier_buyprice'];
        $data['satisfaction_supplier_lead_time'] = $_POST['satisfaction_supplier_lead_time']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $model_satisfaction_supplier->updateSatisfactionSupplierByCode($_POST['satisfaction_supplier_code'],$data);
        
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=satisfaction?action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
    }
     
}

else if ($menu['satisfaction']['view']==1){

    // $supplier_code = $_GET['supplier_code']; 
    // $keyword = $_GET['keyword'];

    // if($_GET['page'] == '' || $_GET['page'] == '0'){
    //     $page = 0;
    // }else{
    //     $page = $_GET['page'] - 1;
    // }

    // $page_size = 100;
     
    // $suppliers = $supplier_model->getSupplierBy();
    // $satisfaction = $model_satisfaction->getSatisfactionBy($supplier_code , $keyword  );

    // $page_max = (int)(count($satisfaction)/$page_size);
    // if(count($satisfaction)%$page_size > 0){
    //     $page_max += 1;
    // }

    require_once($path.'view.inc.php');

}





?>