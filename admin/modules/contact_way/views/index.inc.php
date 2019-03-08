<?php
require_once('../models/ContactWayModel.php');
$path = "modules/contact_way/views/";
$contact_way_model = new ContactWayModel;
$contact_way_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['contact_way']['delete']==1 ){

    $contact_way_model->deleteContactWayByCode($contact_way_code);
?>
    <script>window.location="index.php?app=contact_way"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['contact_way']['add']==1 ){
        
    $contact_way_code = "CW";
    $contact_way_code = $contact_way_model->getContactWayLastCode($contact_way_code,3);  
    if(isset($_POST['contact_way_name'])){
        $data = [];
        $data['contact_way_code'] = $contact_way_code;
        $data['contact_way_name'] = $_POST['contact_way_name'];
        $data['contact_way_detail'] = $_POST['contact_way_detail'];
       
            $code = $contact_way_model->insertContactWay($data);
            if($code > 0){
    ?>
            <script>window.location="index.php?app=contact_way"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=contact_way"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['contact_way']['edit']==1 ){
    if(isset($_POST['contact_way_name'])){
        $data = [];
        $data['contact_way_name'] = $_POST['contact_way_name'];
        $data['contact_way_detail'] = $_POST['contact_way_detail'];
            
        $code = $contact_way_model->updateContactWayByCode($_POST['contact_way_code'],$data);
        if($code > 0){
    ?>
            <script>window.location="index.php?app=contact_way&action=view&code=<?php echo $contact_way_code;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=contact_way&action=view&code=<?php echo $contact_way_code;?>"</script>
    <?php
            }
                    
        }
    
}else if ($menu['contact_way']['view']==1 ){
    $contact_way = $contact_way_model->getContactWayByCode($contact_way_code);
    $contact_ways = $contact_way_model->getContactWayBy();
    require_once($path.'view.inc.php');

}





?>