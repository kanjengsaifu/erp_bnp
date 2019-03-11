<?php
require_once('../models/ContactTypeModel.php');
$path = "modules/contact_type/views/";
$contact_type_model = new ContactTypeModel;
$contact_type_code = $_GET['code'];

if ($_GET['action'] == 'delete'&& $menu['contact_type']['delete']==1 ){

    $contact_type_model->deleteContactTypeByCode($contact_type_code);
?>
    <script>window.location="index.php?app=contact_type"</script>
<?php

}else if ($_GET['action'] == 'add' && $menu['contact_type']['add']==1 ){
        
    $contact_type_code = "CT";
    $contact_type_code = $contact_type_model->getContactTypeLastCode($contact_type_code,3);  
    if(isset($_POST['contact_type_name'])){
        $data = [];
        $data['contact_type_code'] = $contact_type_code;
        $data['contact_type_name'] = $_POST['contact_type_name'];
        $data['contact_type_detail'] = $_POST['contact_type_detail'];
       
            $code = $contact_type_model->insertContactType($data);
            if($code > 0){
                ?>
                <script>window.location="index.php?app=contact_type"</script>
                <?php
            }else{
                ?>
                <script>window.location="index.php?app=contact_type"</script>
                <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && $menu['contact_type']['edit']==1 ){
    if(isset($_POST['contact_type_name'])){
        $data = [];
        $data['contact_type_name'] = $_POST['contact_type_name'];
        $data['contact_type_detail'] = $_POST['contact_type_detail'];
            
        $code = $contact_type_model->updateContactTypeByCode($_POST['contact_type_code'],$data);
        if($code > 0){
                ?>
                <script>window.location="index.php?app=contact_type&action=view&code=<?php echo $contact_type_code;?>"</script>
                <?php
            }else{
                ?>
                <script>window.location="index.php?app=contact_type&action=view&code=<?php echo $contact_type_code;?>"</script>
                <?php
            }
                    
        }
    
}else if ($menu['contact_type']['view']==1 ){
    $contact_type = $contact_type_model->getContactTypeByCode($contact_type_code);
    $contact_types = $contact_type_model->getContactTypeBy();
    require_once($path.'view.inc.php');

}





?>