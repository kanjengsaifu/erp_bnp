<?php
require_once('../models/MaterialModel.php');
// require_once('../models/MaterialGroupModel.php');
// require_once('../models/MaterialTypeModel.php');
// require_once('../models/MaterialCategoryModel.php');
require_once('../models/UnitModel.php');
// require_once('../models/MaterialCustomerModel.php');
require_once('../models/MaterialSupplierModel.php');
// require_once('../models/CustomerModel.php');
require_once('../models/SupplierModel.php');
// require_once('../models/AccountModel.php');

$model_material = new MaterialModel;
// $model_material_group = new MaterialGroupModel;
// $material_type_model = new MaterialTypeModel;
// $model_material_category = new MaterialCategoryModel;
$unit_model = new UnitModel;
// $model_material_customer = new MaterialCustomerModel;
$model_material_supplier = new MaterialSupplierModel;
// $model_customer = new CustomerModel;
$supplier_model = new SupplierModel;
// $account_model = new AccountModel;

$path = "modules/material/views/";
$target_dir = "../upload/material/";
$material_code = $_GET['code'];
$material_supplier_code = $_GET['material_supplier_code']; 


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



if ($_GET['action'] == 'insert' && $menu['material']['add']==1 ){
 
    // $material_type = $material_type_model->getMaterialTypeBy(); 
    $unit = $unit_model->getUnitBy(); 
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['material']['edit']==1 ){

    
    $material = $model_material->getMaterialByCode($material_code);
    // $material_group = $model_material_group->getMaterialGroupBy();
    // $material_type = $material_type_model->getMaterialTypeBy();
    // $material_category = $model_material_category->getMaterialCategoryBy();
    $unit = $unit_model->getUnitBy();
    // $account = $account_model->getAccountAll();

    // $material_customers = $model_material_customer->getMaterialCustomerBy($material_code);
    $material_suppliers = $model_material_supplier->getMaterialSupplierByMaterialCode($material_code);
    // echo '<pre>';
    // print_r($material_code);
    // echo '</pre>';
    

    // $customer = $model_customer->getCustomerBy();
    $supplier = $supplier_model->getSupplierBy();

    if($material_supplier_code != ''){
        $material_supplier = $model_material_supplier->getMaterialSupplierByCode($material_supplier_code);
    }

    // if($material_customer_id != ''){
    //     $material_customer = $model_material_customer->getMaterialCustomerByCode($material_customer_id);
    // }

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['material']['delete']==1 ){

    if($material_supplier_code != ''){
        $model_material_supplier->deleteMaterialSupplierByCode($material_supplier_code);    
        ?>
        <script>window.location="index.php?app=material&action=update&code=<?php echo $material_code;?>"</script>
        <?php
    }else{
        $model_material->deleteMaterialByCode($material_code);     
        ?>
        <script>window.location="index.php?app=material"</script>
        <?php
    }


}else if ($_GET['action'] == 'add' && $menu['material']['add']==1 ){
    $material_code = "MAT";
    $material_code = $model_material->getMaterialLastCode($material_code,3);  
    if(isset($_POST['material_name'])){

        $data = [];
        $data['material_code'] = $material_code;
        $data['material_name'] = $_POST['material_name'];
        $data['material_logo'] = $_POST['material_logo'];
        // $data['material_quantity_per_unit'] = preg_replace('/\D/', '', $_POST['material_quantity_per_unit']);
        $data['unit_code'] = $_POST['unit_code'];
        $data['material_minimum_stock'] = $_POST['material_minimum_stock'];
        $data['material_maximum_stock'] = $_POST['material_maximum_stock'];
        $data['material_description'] = $_POST['material_description']; 
        $data['addby'] = $login_user['user_code'];  
        
        $check = true;


        if($_FILES['material_logo']['name'] == ""){
            $data['material_logo'] = "default.png";
        }else {
            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['material_logo']['name'],".");
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
            }else if ($_FILES["material_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["material_logo"]["tmp_name"], $target_file)) {
               
                //--------------------------------------------------------------------
                $data['material_logo'] = $date.$newname;
                //--------------------------------------------------------------------
                
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
            $code = $model_material->insertMaterial($data);

            if($code != false){
                ?>
                <script>window.location="index.php?app=material&action=update&code=<?php echo $code?>"</script>
                <?php
            }else{
                ?>
                <script>window.location="index.php?app=material"</script>
                <?php
            }
                    
        }
     
    }else{
        ?>
    <script>window.location="index.php?app=material"</script>
        <?php
    }
     
    
}else if ($_GET['action'] == 'edit' && $menu['material']['edit']==1 ){
    
    if(isset($_POST['material_name'])){
        $data = [];  
        $data['material_name'] = $_POST['material_name'];
        $data['material_logo'] = $_POST['material_logo'];
        // $data['material_quantity_per_unit'] = preg_replace('/\D/', '', $_POST['material_quantity_per_unit']);
        $data['unit_code'] = $_POST['unit_code'];
        $data['material_minimum_stock'] = $_POST['material_minimum_stock'];
        $data['material_maximum_stock'] = $_POST['material_maximum_stock'];
        $data['material_description'] = $_POST['material_description']; 
        $data['updateby'] = $login_user['user_code'];  


        $check = true;
 

        
        if($_FILES['material_logo']['name'] == ""){
            $data['material_logo'] = $_POST['material_logo_o'];
        }else {
            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['material_logo']['name'],".");
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
            }else if ($_FILES["material_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["material_logo"]["tmp_name"], $target_file)) {

                
                //--------------------------------------------------------------------
                $data['material_logo'] = $date.$newname;
                //--------------------------------------------------------------------

                $target_file = $target_dir . $_POST["material_logo_o"];
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
            $result = $model_material->updateMaterialByCode($_POST['material_code'],$data);

            if($result){
            ?>
            <script>
            window.location="index.php?app=material&action=update&code=<?php echo $_POST['material_code'];?>"
            </script>
            <?php
            }else{
            ?>
            <script>
            window.location="index.php?app=material&action=update&code=<?php echo $_POST['material_code'];?>"
            </script>
            <?php
            }
                    
        }

    }else{
        ?>
    <script>window.location="index.php?app=material"</script>
        <?php
    }
    
        
        
    
} 

else if ($_GET['action'] == 'add_supplier' && $menu['material']['edit']==1){
    
    $material_supplier_code = "MATS";
    $material_supplier_code = $model_material_supplier->getMaterialSupplierLastCode($material_supplier_code,3);  
    if($material_supplier_code!=''&&$material_code!=''){

        $data = [];
        $data['material_supplier_code'] = $material_supplier_code;
        $data['material_code'] = $material_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['material_supplier_buyprice'] = $_POST['material_supplier_buyprice'];
        $data['material_supplier_lead_time'] = $_POST['material_supplier_lead_time'];
        // $data['material_supplier_status'] = $_POST['material_supplier_status']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $model_material_supplier->insertMaterialSupplier($data); 
        ?>
            <script>window.location="index.php?app=material&action=update&code=<?php echo $material_code?>"</script>
        <?php 
    }else{
        ?>
            <script>window.location="index.php?app=material&action=update&code=<?php echo $material_code?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_supplier' && $menu['material']['edit']==1 ){
    
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['material_supplier_buyprice'] = $_POST['material_supplier_buyprice'];
        $data['material_supplier_lead_time'] = $_POST['material_supplier_lead_time']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $model_material_supplier->updateMaterialSupplierByCode($_POST['material_supplier_code'],$data);
        
        ?>
            <script>window.location="index.php?app=material&action=update&code=<?php echo $material_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=material?action=update&code=<?php echo $material_code?>"</script>
        <?php
    }
     
}

else if ($menu['material']['view']==1){

    $supplier_code = $_GET['supplier_code']; 
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    // $material_type = $material_type_model->getMaterialTypeBy();
    // $material_category = $model_material_category->getMaterialCategoryBy();
    $suppliers = $supplier_model->getSupplierBy();
    $material = $model_material->getMaterialBy($supplier_code , $keyword  );

    $page_max = (int)(count($material)/$page_size);
    if(count($material)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>