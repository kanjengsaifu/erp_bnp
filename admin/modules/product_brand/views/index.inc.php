<?php
require_once('../models/ProductBrandModel.php');
$path = "modules/product_brand/views/";
$product_brand_model = new ProductBrandModel;
$product_brand_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['product_brand']['delete']==1 ){

    $product_brand_model->deleteProductBrandByCode($product_brand_code);
?>
    <script>window.location="index.php?app=product_brand"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['product_brand']['add']==1 ){
        
    $product_brand_code = "PB";
    $product_brand_code = $product_brand_model->getProductBrandLastCode($product_brand_code,3);  
    if(isset($_POST['product_brand_name'])){
        $data = [];
        $data['product_brand_code'] = $product_brand_code;
        $data['product_brand_name'] = $_POST['product_brand_name'];
        $data['product_brand_detail'] = $_POST['product_brand_detail'];
       
            $code = $product_brand_model->insertProductBrand($data);
            if($code > 0){
    ?>
            <script>window.location="index.php?app=product_brand"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_brand"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['product_brand']['edit']==1 ){
    if(isset($_POST['product_brand_name'])){
        $data = [];
        $data['product_brand_name'] = $_POST['product_brand_name'];
        $data['product_brand_detail'] = $_POST['product_brand_detail'];
            
        $code = $product_brand_model->updateProductBrandByCode($_POST['product_brand_code'],$data);
        if($code > 0){
    ?>
            <script>window.location="index.php?app=product_brand&action=view&code=<?php echo $product_brand_code;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_brand&action=view&code=<?php echo $product_brand_code;?>"</script>
    <?php
            }
                    
        }
    
}else if ($menu['product_brand']['view']==1 ){
    $product_brand = $product_brand_model->getProductBrandByCode($product_brand_code);
    $product_brands = $product_brand_model->getProductBrandBy();
    require_once($path.'view.inc.php');

}





?>