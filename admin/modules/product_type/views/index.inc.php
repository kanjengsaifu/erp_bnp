<?php
require_once('../models/ProductTypeModel.php');
$path = "modules/product_type/views/";
$product_type_model = new ProductTypeModel;
$product_type_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['product_type']['delete']==1 ){

    $product_type_model->deleteProductTypeByCode($product_type_code);
?>
    <script>window.location="index.php?app=product_type"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['product_type']['add']==1 ){
        
    $product_type_code = "PT";
    $product_type_code = $product_type_model->getProductTypeLastCode($product_type_code,3);  
    if(isset($_POST['product_type_name'])){
        $data = [];
        $data['product_type_code'] = $product_type_code;
        $data['product_type_name'] = $_POST['product_type_name'];
        $data['product_type_detail'] = $_POST['product_type_detail'];
       
            $code = $product_type_model->insertProductType($data);
            if($code > 0){
                ?>
                <script>window.location="index.php?app=product_type"</script>
                <?php
            }else{
                ?>
                <script>window.location="index.php?app=product_type"</script>
                <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['product_type']['edit']==1 ){
    if(isset($_POST['product_type_name'])){
        $data = [];
        $data['product_type_name'] = $_POST['product_type_name'];
        $data['product_type_detail'] = $_POST['product_type_detail'];
            
        $code = $product_type_model->updateProductTypeByCode($_POST['product_type_code'],$data);
        if($code > 0){
    ?>
            <script>window.location="index.php?app=product_type&action=view&code=<?php echo $product_type_code;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_type&action=view&code=<?php echo $product_type_code;?>"</script>
    <?php
            }
                    
        }
    
}else if ($menu['product_type']['view']==1 ){
    $product_type = $product_type_model->getProductTypeByCode($product_type_code);
    $product_types = $product_type_model->getProductTypeBy();
    require_once($path.'view.inc.php');

}





?>