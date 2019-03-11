<?php 
require_once('../models/ProjectModel.php');  
require_once('../models/ProductModel.php');  
require_once('../models/ProjectProductModel.php');  
 
$project_model = new ProjectModel;    
$product_model = new ProductModel;    
$project_product_model = new ProjectProductModel;    

$target_dir = "../upload/project/";
$project_code = $_GET['code'];  

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

 $path = "modules/project/views/";
 $project_code = $_GET['code'];
 $project_product_code = $_GET['project_product_code']; 

if ($_GET['action'] == 'insert' && $menu['project']['add']==1 ){  

    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['project']['edit']==1 ){  
    
    $project = $project_model->getProjectByCode($project_code);  
  
    $product = $product_model->getProductByProjectCode($project_code);  
  
    $project_products = $project_product_model->getProjectProductByProjectCode($project_code);
  

    if($project_product_code != ''){
        $project_product = $project_product_model->getProjectProductByCode($project_product_code);
    } 
    
    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['project']['delete']==1 ){ 
    if($project_product_code != ''){
        $project_product_model->deleteProjectProductByCode($project_product_code);    
        ?>
        <script>window.location="index.php?app=project&action=update&code=<?php echo $project_code;?>"</script>
        <?php 
    }else{
        $project_model->deleteProjectByCode($project_code);     
        ?>
        <script>window.location="index.php?app=project"</script>
        <?php
    }
}else if ($_GET['action'] == 'add' && $menu['project']['add']==1 ){
     
    $project_code = "PJ";
    $project_code = $project_model->getProjectLastCode($project_code,3);  
    if(isset($_POST['project_name'])){

        $data = [];
        $data['project_code'] = $project_code;
        $data['project_name'] = $_POST['project_name'];
        $data['project_logo'] = $_POST['project_logo'];   
        $data['project_price_per_rai'] = $_POST['project_price_per_rai'];   
        $data['project_description'] = $_POST['project_description'];  
        
        $check = true;
        $input_image = array("project_logo");

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
            $code = $project_model->insertProject($data);

            if($code != false){
                ?>
                <script>
                window.location="index.php?app=project&action=update&code=<?php echo $code?>"
                </script>
                <?php
            }else{
                ?>
                <script>
                window.location="index.php?app=project"
                </script>
                <?php
            }
                    
        }
     
    }else{
        ?>
        <script>window.location="index.php?app=project"</script>
        <?php
    }
}else if ($_GET['action'] == 'edit' && $menu['project']['edit']==1 ){   
     
    if(isset($_POST['project_name'])){

        $data = []; 
        $data['project_name'] = $_POST['project_name'];
        $data['project_logo'] = $_POST['project_logo'];   
        $data['project_price_per_rai'] = $_POST['project_price_per_rai'];   
        $data['project_description'] = $_POST['project_description'];   


        $check = true;
 
        $input_image = array("project_logo");

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
            $result = $project_model->updateProjectByCode($_POST['project_code'],$data);

            if($result){
                ?>
                <script>
                window.location="index.php?app=project&action=update&code=<?php echo $_POST['project_code'];?>"</script>
                <?php
            }else{
                ?>
                <script>
                window.location="index.php?app=project&action=update&code=<?php echo $_POST['project_code'];?>"</script>
                <?php
            }
                    
        }

    }else{
        ?>
        <script>window.location="index.php?app=project"</script>
        <?php
    }
    
        
     
}else if ($_GET['action'] == 'add_product' && $menu['project']['edit']==1){
    
    $project_product_code = "PDP";
    $project_product_code = $project_product_model->getProjectProductLastCode($project_product_code,3);  
    
    // echo $project_product_code;
    if($project_product_code!=''&&$project_code!=''){

        $data = [];
        $data['project_product_code'] = $project_product_code;
        $data['project_code'] = $project_code;
        $data['product_code'] = $_POST['product_code']; 
        $data['project_product_amount'] = $_POST['project_product_amount']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $project_product_model->insertProjectProduct($data); 
        ?>
        <script>
        window.location="index.php?app=project&action=update&code=<?php echo $project_code?>"
        </script>
        <?php 
    }else{
        ?>
        <script>
        window.location="index.php?app=project&action=update&code=<?php echo $project_code?>"
        </script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_product' && $menu['project']['edit']==1 ){
    
    if(isset($_POST['product_code'])){
        $data = [];
        $data['product_code'] = $_POST['product_code']; 
        $data['project_product_amount'] = $_POST['project_product_amount']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $project_product_model->updateProjectProductByCode($_POST['project_product_code'],$data);
        
        ?>
            <script>window.location="index.php?app=project&action=update&code=<?php echo $project_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=project?action=update&code=<?php echo $project_code?>"</script>
        <?php
    }
     
}else if ($menu['project']['view']==1){

    $supplier_code = $_GET['supplier_code']; 
    $keyword = $_GET['keyword'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100; 
    // $suppliers = $supplier_model->getSupplierBy();
    $project = $project_model->getProjectBy($supplier_code , $keyword  );

    $page_max = (int)(count($project)/$page_size);
    if(count($project)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>