<?php
require_once('../models/JobModel.php');

$path = "modules/job/views/";

$job_model = new JobModel;

if ($_GET['action'] == 'insert'&&$menu['job']['add']==1){ 
    require_once($path.'insert.inc.php'); 
}else if ($_GET['action'] == 'update'&&$menu['job']['edit']==1){
    $job = $job_model->getJobByCode($job_code);
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'delete'&&$menu['job']['delete']==1){ 
    $job_model->deleteJobByCode($job_code);
    ?>
    <script>
        window.location="index.php?app=job";
    </script>
    <?php
}else if ($_GET['action'] == 'add'&&$menu['job']['add']==1){
    $job_code = "D";
    $job_code = $job_model->getJobLastCode($job_code,3);  
    if($job_code!=false){
        $data['job_code'] = $job_code;
        $data['job_name_th'] = $_POST['job_name_th'];
        $data['job_name_en'] = $_POST['job_name_en'];
        $data['job_address_1'] = $_POST['job_address_1']; 
        $data['job_address_2'] = $_POST['job_address_2']; 
        $data['job_address_3'] = $_POST['job_address_3']; 
        $data['job_tax'] = $_POST['job_tax']; 
        $data['job_tel'] = $_POST['job_tel']; 
        $data['job_fax'] = $_POST['job_fax']; 
        $data['job_email'] = $_POST['job_email']; 
        $data['job_branch'] = $_POST['job_branch'];  
        $data['job_vat_type'] = $_POST['job_vat_type']; 
        $data['updateby'] = $login_user['user_code'];  
    
        $job = $job_model->insertJob($data);

        if($job!=''){ 
            ?> 
            <script>
                window.location="index.php?app=job&action=update&code=<?=$job?>"
            </script> 
            <?php
        }else{
            ?>
            <script>
                alert('ไม่สามารถบันทึกข้อมูลได้');
                window.history.back();
            </script>
            <?PHP
        } 
    }else{
        ?>
        <script>
            window.location="index.php?app=job"
        </script>
        <?php
    }
}else if ($_GET['action'] == 'edit'&&$menu['job']['edit']==1){
    if($_POST['job_code']!=""){
        $data['job_code'] = $_POST['job_code'];
        $data['job_name_th'] = $_POST['job_name_th'];
        $data['job_name_en'] = $_POST['job_name_en'];
        $data['job_address_1'] = $_POST['job_address_1']; 
        $data['job_address_2'] = $_POST['job_address_2']; 
        $data['job_address_3'] = $_POST['job_address_3']; 
        $data['job_tax'] = $_POST['job_tax']; 
        $data['job_tel'] = $_POST['job_tel']; 
        $data['job_fax'] = $_POST['job_fax']; 
        $data['job_email'] = $_POST['job_email']; 
        $data['job_branch'] = $_POST['job_branch'];  
        $data['job_vat_type'] = $_POST['job_vat_type']; 
        $data['updateby'] = $login_user['user_code'];  
    
        $result = $job_model->updateJobByCode($data['job_code'],$data);

        if($result){ 
            ?> 
            <script>
                window.location="index.php?app=job&action=update&code=<?=$_POST['job_code']?>"
            </script> 
            <?php
        }else{
            ?>
            <script>
                alert('ไม่สามารถบันทึกข้อมูลได้');
                window.history.back();
            </script>
            <?PHP
        } 
    }else{
        ?>
        <script>
            window.location="index.php?app=job"
        </script>
        <?php
    }
}else if ($menu['job']['view']==1 ){
    $job = $job_model->getJobBy(); 
    require_once($path.'view.inc.php');
}
?>