<?php
require_once('../models/MaterialModel.php'); 
require_once('../models/ProductModel.php'); 
require_once('../models/UnitModel.php');  
require_once('../models/ProductTypeModel.php');  
require_once('../models/ProductBrandModel.php');  
require_once('../models/ProductSupplierModel.php'); 
require_once('../models/ProductMaterialModel.php'); 
require_once('../models/SupplierModel.php'); 

$material_model = new MaterialModel; 
$product_model = new ProductModel; 
$unit_model = new UnitModel; 
$product_type_model = new ProductTypeModel; 
$product_brand_model = new ProductBrandModel; 
$product_supplier_model = new ProductSupplierModel; 
$product_material_model = new ProductMaterialModel; 
$supplier_model = new SupplierModel; 

$path = "modules/product/views/";
$target_dir = "../upload/product/";
$product_code = $_GET['code'];
$product_supplier_code = $_GET['product_supplier_code']; 
$product_material_code = $_GET['product_material_code']; 


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



if ($_GET['action'] == 'insert' && $menu['product']['add']==1 ){
 
    // $product_type = $product_type_model->getProductTypeBy(); 
    $unit = $unit_model->getUnitBy(); 
    $product_type = $product_type_model->getProductTypeBy(); 
    $product_brand = $product_brand_model->getProductBrandBy(); 
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['product']['edit']==1 ){ 
    
    $product = $product_model->getProductByCode($product_code);  

    $unit = $unit_model->getUnitBy(); 
    $product_type = $product_type_model->getProductTypeBy(); 
    $product_brand = $product_brand_model->getProductBrandBy(); 
    $material = $material_model->getMaterialBy(); 

    // echo '<pre>';
    // print_r($material);
    // echo '</pre>';
 
    $product_suppliers = $product_supplier_model->getProductSupplierByProductCode($product_code);
    $product_materials = $product_material_model->getProductMaterialByProductCode($product_code);
 
    $supplier = $supplier_model->getSupplierBy();

    if($product_supplier_code != ''){
        $product_supplier = $product_supplier_model->getProductSupplierByCode($product_supplier_code);
    } 
    if($product_material_code != ''){
        $product_material = $product_material_model->getProductMaterialByCode($product_material_code);
    } 

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['product']['delete']==1 ){

    if($product_supplier_code != ''){
        $product_supplier_model->deleteProductSupplierByCode($product_supplier_code);    
        ?>
        <script>window.location="index.php?app=product&action=update&code=<?php echo $product_code;?>"</script>
        <?php
    }else if($product_material_code != ''){
        $product_material_model->deleteProductMaterialByCode($product_material_code);   
        ?>
        <script>window.location="index.php?app=product&action=update&code=<?php echo $product_code;?>"</script>
        <?php
    }else{
        $product_model->deleteProductByCode($product_code);     
        ?>
        <script>window.location="index.php?app=product"</script>
        <?php
    }


}else if ($_GET['action'] == 'add' && $menu['product']['add']==1 ){
    $product_code = "MAT";
    $product_code = $product_model->getProductLastCode($product_code,3);  
    if(isset($_POST['product_name'])){

        $data = [];
        $data['product_code'] = $product_code;
        $data['product_name'] = $_POST['product_name'];
        $data['product_price'] = $_POST['product_price'];
        $data['product_logo'] = $_POST['product_logo']; 
        $data['unit_code'] = $_POST['unit_code']; 
        $data['product_type_code'] = $_POST['product_type_code']; 
        $data['product_brand_code'] = $_POST['product_brand_code']; 
        $data['product_description'] = $_POST['product_description']; 
        $data['addby'] = $login_user['user_code'];  
        
        $check = true;
        $input_image = array("product_logo");

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
                    if($_POST[$input_image[$i].'_o']!=""){
                        $target_file = $target_dir . $_POST[$input_image[$i].'_o'];
                        if (file_exists($target_file)&&$_POST[$input_image[$i].'_o']!='') {
                            unlink($target_file);
                        }
                    }
                } else {
                    $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                    $check = false;
                } 
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
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
            $code = $product_model->insertProduct($data);

            if($code != false){
                ?>
                <script>
                window.location="index.php?app=product&action=update&code=<?php echo $code?>"
                </script>
                <?php
            }else{
                ?>
                <script>
                window.location="index.php?app=product"
                </script>
                <?php
            }
                    
        }
     
    }else{
        ?>
        <script>window.location="index.php?app=product"</script>
        <?php
    }
     
    
}else if ($_GET['action'] == 'edit' && $menu['product']['edit']==1 ){
    
    if(isset($_POST['product_name'])){
        $data = [];  
        $data['product_name'] = $_POST['product_name'];
        $data['product_price'] = $_POST['product_price'];
        $data['product_logo'] = $_POST['product_logo']; 
        $data['unit_code'] = $_POST['unit_code']; 
        $data['product_type_code'] = $_POST['product_type_code']; 
        $data['product_brand_code'] = $_POST['product_brand_code']; 
        $data['product_description'] = $_POST['product_description']; 
        $data['updateby'] = $login_user['user_code'];  


        $check = true;
 
        $input_image = array("product_logo");

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
                    if($_POST[$input_image[$i].'_o']!=""){
                        $target_file = $target_dir . $_POST[$input_image[$i].'_o'];
                        if (file_exists($target_file)&&$_POST[$input_image[$i].'_o']!='') {
                            unlink($target_file);
                        }
                    }
                } else {
                    $error_msg =  "ขอโทษด้วย. ระบบไม่สามารถอัพโหลดไฟล์ได้.";
                    $check = false;
                } 
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
            $result = $product_model->updateProductByCode($_POST['product_code'],$data);

            if($result){
            ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $_POST['product_code'];?>"</script>
            <?php
            }else{
            ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $_POST['product_code'];?>"</script>
            <?php
            }
                    
        }

    }else{
        ?>
    <script>window.location="index.php?app=product"</script>
        <?php
    }
    
        
        
    
} 

else if ($_GET['action'] == 'add_supplier' && $menu['product']['edit']==1){
    
    $product_supplier_code = "PDS";
    $product_supplier_code = $product_supplier_model->getProductSupplierLastCode($product_supplier_code,3);  
    if($product_supplier_code!=''&&$product_code!=''){

        $data = [];
        $data['product_supplier_code'] = $product_supplier_code;
        $data['product_code'] = $product_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['product_supplier_buyprice'] = $_POST['product_supplier_buyprice'];
        $data['product_supplier_lead_time'] = $_POST['product_supplier_lead_time'];
        // $data['product_supplier_status'] = $_POST['product_supplier_status']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $product_supplier_model->insertProductSupplier($data); 
        ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"
            </script>
        <?php 
    }else{
        ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"
            </script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_supplier' && $menu['product']['edit']==1 ){
    
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['product_supplier_buyprice'] = $_POST['product_supplier_buyprice'];
        $data['product_supplier_lead_time'] = $_POST['product_supplier_lead_time']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $product_supplier_model->updateProductSupplierByCode($_POST['product_supplier_code'],$data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=product?action=update&code=<?php echo $product_code?>"</script>
        <?php
    }
     
}
else if ($_GET['action'] == 'add_material' && $menu['product']['edit']==1){
   
    $product_material_code = "PDM";
    $product_material_code = $product_material_model->getProductMaterialLastCode($product_material_code,3);  
    
    // echo $product_material_code;
    if($product_material_code!=''&&$product_code!=''){

        $data = [];
        $data['product_material_code'] = $product_material_code;
        $data['product_code'] = $product_code;
        $data['material_code'] = $_POST['material_code']; 
        $data['product_material_amount'] = $_POST['product_material_amount']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $product_material_model->insertProductMaterial($data); 
        ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"
            </script>
        <?php 
    }else{
        ?>
            <script>
            window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"
            </script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_material' && $menu['product']['edit']==1 ){
    
    if(isset($_POST['material_code'])){
        $data = [];
        $data['material_code'] = $_POST['material_code']; 
        $data['product_material_amount'] = $_POST['product_material_amount']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $product_material_model->updateProductMaterialByCode($_POST['product_material_code'],$data);
        
        ?>
            <script>window.location="index.php?app=product&action=update&code=<?php echo $product_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=product?action=update&code=<?php echo $product_code?>"</script>
        <?php
    }
     
}

else if ($menu['product']['view']==1){

    $supplier_code = $_GET['supplier_code']; 
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100; 
    $suppliers = $supplier_model->getSupplierBy();
    $product = $product_model->getProductBy($supplier_code , $keyword  );

    $page_max = (int)(count($product)/$page_size);
    if(count($product)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>