<?php
require_once('../models/UnitModel.php');
$path = "modules/unit/views/";
$unit_model = new UnitModel;
$unit_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['unit']['delete']==1 ){

    $unit_model->deleteUnitByCode($unit_code);
?>
    <script>window.location="index.php?app=unit"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['unit']['add']==1 ){
        
    $unit_code = "UN";
    $unit_code = $unit_model->getUnitLastCode($unit_code,3);  
    if(isset($_POST['unit_name'])){
        $data = [];
        $data['unit_code'] = $unit_code;
        $data['unit_name'] = $_POST['unit_name'];
        $data['unit_detail'] = $_POST['unit_detail'];
       
            $code = $unit_model->insertUnit($data);
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
            
        $code = $unit_model->updateUnitByCode($_POST['unit_code'],$data);
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
    $unit = $unit_model->getUnitByCode($unit_code);
    $units = $unit_model->getUnitBy();
    require_once($path.'view.inc.php');

}





?>