<?php
require_once('../models/ProductCategoryModel.php');
$path = "modules/product_category/views/";
$product_category_model = new ProductCategoryModel;
$product_category_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['product_category']['delete']==1 ){

    $product_category_model->deleteProductCategoryByCode($product_category_code);
?>
    <script>window.location="index.php?app=product_category"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['product_category']['add']==1 ){
        
    $product_category_code = "PT";
    $product_category_code = $product_category_model->getProductCategoryLastCode($product_category_code,3);  
    if(isset($_POST['product_category_name'])){
        $data = [];
        $data['product_category_code'] = $product_category_code;
        $data['product_category_name'] = $_POST['product_category_name'];
        $data['stock_event'] = $_POST['stock_event'];
       
            $code = $product_category_model->insertProductCategory($data);
            if($code > 0){
                ?>
                <script>window.location="index.php?app=product_category"</script>
                <?php
            }else{
                ?>
                <script>window.location="index.php?app=product_category"</script>
                <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['product_category']['edit']==1 ){
    if(isset($_POST['product_category_name'])){
        $data = [];
        $data['product_category_name'] = $_POST['product_category_name'];
        $data['stock_event'] = $_POST['stock_event'];
            
        $code = $product_category_model->updateProductCategoryByCode($_POST['product_category_code'],$data);
        if($code > 0){
    ?>
            <script>window.location="index.php?app=product_category&action=view&code=<?php echo $product_category_code;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=product_category&action=view&code=<?php echo $product_category_code;?>"</script>
    <?php
            }
                    
        }
    
}else if ($menu['product_category']['view']==1 ){
    $product_category = $product_category_model->getProductCategoryByCode($product_category_code);
    $product_categorys = $product_category_model->getProductCategoryBy();
    require_once($path.'view.inc.php');

}





?>