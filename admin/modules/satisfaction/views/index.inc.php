<?php

require_once('../models/SatisfactionModel.php'); 
require_once('../models/ContactWayModel.php'); 
require_once('../models/ContactTypeModel.php'); 
require_once('../models/ContractorModel.php');
require_once('../models/FarmerModel.php');
require_once('../models/FundAgentModel.php');
require_once('../models/AgentModel.php');

$satisfaction_model = new SatisfactionModel;  
$contact_way_model = new ContactWayModel;  
$contact_type_model = new ContactTypeModel; 
$contractor_model = new ContractorModel; 
$farmer_model = new FarmerModel;
$fund_agent_model = new FundAgentModel;
$agent_model = new AgentModel;

date_default_timezone_set("Asia/Bangkok");

$satisfaction_code = $_GET['code'];
 
$path = "modules/satisfaction/views/";

if ($_GET['action'] == 'insert' && $menu['satisfaction']['add']==1 ){
 
    $contact_way = $contact_way_model->getContactWayBy(); 
    $contact_type = $contact_type_model->getContactTypeBy(); 
    // $satisfaction_type = $satisfaction_type_model->getSatisfactionTypeBy(); 
    // $unit = $unit_model->getUnitBy(); 
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update' && $menu['satisfaction']['edit']==1 ){  
    
    $satisfaction = $satisfaction_model->getSatisfactionByCode($satisfaction_code);   
    // echo '<pre>';
    // print_r($satisfaction);
    // echo '</pre>'; 
    if($satisfaction['member_type']=="ผู้รับเหมา"){
        $member = $contractor_model->getContractorByUserCode($satisfaction['user_code']); 
    }else if($satisfaction['member_type']=="ตัวแทน"){
        $member = $fund_agent_model->getFundAgentByUserCode($satisfaction['user_code']);
    }else if($satisfaction['member_type']=="นายหน้า"){
        $member = $agent_model->getAgentByUserCode($satisfaction['user_code']);
    }else if($satisfaction['member_type']=="เกษตรกร"){ 
        $member = $farmer_model->getFarmerByUserCode($satisfaction['user_code']); 
    } 

    $contact_way = $contact_way_model->getContactWayBy(); 
    $contact_type = $contact_type_model->getContactTypeBy(); 

    require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'delete' && $menu['satisfaction']['delete']==1 ){
 
    $satisfaction_model->deleteSatisfactionByCode($satisfaction_code);     
    ?>
    <script>window.location="index.php?app=satisfaction"</script>
    <?php 


}else if ($_GET['action'] == 'add' && $menu['satisfaction']['add']==1 ){
    $satisfaction_code = "SF".date('y').date('m').date('d');
    $satisfaction_code = $satisfaction_model->getSatisfactionLastCode($satisfaction_code,3);  
    if($satisfaction_code!=""){

        $data = [];
        $data['satisfaction_code'] = $satisfaction_code;
        $data['member_type'] = $_POST['member_type'];
        $data['member_code'] = $_POST['member_code']; 
        $data['contact_way_code'] = $_POST['contact_way_code'];
        $data['contact_type_code'] = $_POST['contact_type_code'];
        $data['satisfaction_detail'] = $_POST['satisfaction_detail'];
        $data['satisfaction_score'] = $_POST['satisfaction_score']; 
        $data['user_code'] = $login_user['user_code'];  
        
        $check = true;

  
        $code = $satisfaction_model->insertSatisfaction($data);

        if($code != false){
            ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $code?>"</script>
            <?php
        }else{
            ?>
            <script>window.location="index.php?app=satisfaction"</script>
            <?php
        }
          
     
    }else{
        ?>
        <script>window.location="index.php?app=satisfaction"</script>
        <?php
    }
     
    
}else if ($_GET['action'] == 'edit' && $menu['satisfaction']['edit']==1 ){
    
    if($satisfaction_code!=""){
        $data = [];   
        $data['member_type'] = $_POST['member_type'];
        $data['member_code'] = $_POST['member_code']; 
        $data['contact_way_code'] = $_POST['contact_way_code'];
        $data['contact_type_code'] = $_POST['contact_type_code'];
        $data['satisfaction_detail'] = $_POST['satisfaction_detail'];
        $data['satisfaction_score'] = $_POST['satisfaction_score']; 
 
         
            $result = $satisfaction_model->updateSatisfactionByCode($satisfaction_code,$data);

            if($result){
            ?>
            <script>
            window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code;?>"
            </script>
            <?php
            }else{
            ?>
            <script>
            window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code;?>"
            </script>
            <?php
            }
                    
        

    }else{
        ?>
    <script>window.location="index.php?app=satisfaction"</script>
        <?php
    }
    
        
        
    
} 

else if ($_GET['action'] == 'add_supplier' && $menu['satisfaction']['edit']==1){
    
    $satisfaction_supplier_code = "MATS";
    $satisfaction_supplier_code = $model_satisfaction_supplier->getSatisfactionSupplierLastCode($satisfaction_supplier_code,3);  
    if($satisfaction_supplier_code!=''&&$satisfaction_code!=''){

        $data = [];
        $data['satisfaction_supplier_code'] = $satisfaction_supplier_code;
        $data['satisfaction_code'] = $satisfaction_code;
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['satisfaction_supplier_buyprice'] = $_POST['satisfaction_supplier_buyprice'];
        $data['satisfaction_supplier_lead_time'] = $_POST['satisfaction_supplier_lead_time'];
        // $data['satisfaction_supplier_status'] = $_POST['satisfaction_supplier_status']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $model_satisfaction_supplier->insertSatisfactionSupplier($data); 
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php 
    }else{
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit_supplier' && $menu['satisfaction']['edit']==1 ){
    
    if(isset($_POST['supplier_code'])){
        $data = [];
        $data['supplier_code'] = $_POST['supplier_code']; 
        $data['satisfaction_supplier_buyprice'] = $_POST['satisfaction_supplier_buyprice'];
        $data['satisfaction_supplier_lead_time'] = $_POST['satisfaction_supplier_lead_time']; 

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>'
        $model_satisfaction_supplier->updateSatisfactionSupplierByCode($_POST['satisfaction_supplier_code'],$data);
        
        ?>
            <script>window.location="index.php?app=satisfaction&action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
                
    }else{
        ?>
            <script>window.location="index.php?app=satisfaction?action=update&code=<?php echo $satisfaction_code?>"</script>
        <?php
    }
     
}

else if ($menu['satisfaction']['view']==1){ 

    $date_start =  $_GET['date_start'] ;
    $date_end = $_GET['date_end'];
    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    if($date_start!=""){
        $date_start_search = date_format(date_create($date_start),"Y-m-d");
    }else{
        $date_start_search ='';
    }
    if($date_end!=""){
        $date_end_search = date_format(date_create($date_end),"Y-m-d");
    }else{
        $date_end_search ='';
    }

    $page_size = 100;
     
    $satisfaction = $satisfaction_model->getSatisfactionBy($login_user['user_code'],$date_start_search,$date_end_search);
    // echo '<pre>';
    // print_r($satisfaction);
    // echo '</pre>';
    // $satisfaction = $model_satisfaction->getSatisfactionBy($supplier_code , $keyword  );

    $page_max = (int)(count($satisfaction)/$page_size);
    if(count($satisfaction)%$page_size > 0){
        $page_max += 1;
    }

    require_once($path.'view.inc.php');

}





?>