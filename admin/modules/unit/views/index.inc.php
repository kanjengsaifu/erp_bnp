<?php
require_once('../models/MaterialUnitModel.php');
$path = "modules/unit/views/";
$unit_model = new MaterialUnitModel;
$unit_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['unit']['delete']==1 ){

    $unit_model->deleteMaterialUnitByID($unit_code);
?>
    <script>window.location="index.php?app=unit"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['unit']['add']==1 ){
        
    $unit_code = "MU";
    $unit_code = $unit_model->getMaterialUnitLastCode($unit_code,3);  
    if(isset($_POST['unit_name'])){
        $data = [];
        $data['unit_code'] = $unit_code;
        $data['unit_name'] = $_POST['unit_name'];
        $data['unit_detail'] = $_POST['unit_detail'];
       
            $code = $unit_model->insertMaterialUnit($data);
            if($code > 0){
    ?>
            <script>window.location="index.php?app=unit"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=unit"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['unit']['edit']==1 ){
    if(isset($_POST['unit_name'])){
        $data = [];
        $data['unit_name'] = $_POST['unit_name'];
        $data['unit_detail'] = $_POST['unit_detail'];
            
        $code = $unit_model->updateMaterialUnitByID($_POST['unit_code'],$data);
        if($code > 0){
    ?>
            <script>window.location="index.php?app=unit&action=view&code=<?php echo $unit_code;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=unit&action=view&code=<?php echo $unit_code;?>"</script>
    <?php
            }
                    
        }
    
}else if ($menu['unit']['view']==1 ){
    $unit = $unit_model->getMaterialUnitByID($unit_code);
    $units = $unit_model->getMaterialUnitBy();
    require_once($path.'view.inc.php');

}





?>