<?php
require_once('../models/CheckInModel.php');
require_once('../models/CheckInTypeModel.php');

$path = "modules/check_in/views/";

$check_in_model = new CheckInModel;
$check_in_type_model = new CheckInTypeModel;

$check_in_code = $_GET['code'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['check_in']['add']){ 
    $check_in_type = $check_in_type_model->getCheckInTypeBy();  
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'add'&&$menu['check_in']['add']){
    if ($_POST['check_in_code'] == ''){
        $code = "VQS".date('y').date('m').date('d');
        $check_in_code = $check_in_model->getCheckInLastCode($code,4);  
    }else{
        $check_in_code = $_POST['check_in_code'];
    }

    if($check_in_code != '' && isset($_POST['check_in_topic'])){
        $data = [];  
        $data['check_in_code'] = $check_in_code;
        $data['check_in_topic'] = $_POST['check_in_topic'];
        $data['check_in_type_code'] = $_POST['check_in_type_code'];
        $data['score'] = $_POST['score'];
        $data['addby'] = $login_user['user_code'];  

        $result = $check_in_model->insertCheckIn($data);

        if($result){
            ?> <script> window.location="index.php?app=check_in" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเพิ่มการเช็คอินได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> alert('ไม่สามารถเพิ่มการเช็คอินได้'); </script> <?php
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'update'&&$menu['check_in']['edit']){
    $check_in = $check_in_model->getCheckInByCode($check_in_code);
    $check_in_type = $check_in_type_model->getCheckInTypeBy();  
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'edit'&&$menu['check_in']['edit']){
    if(isset($_POST['check_in_code'])){
        $data = [];  
        $data['check_in_topic'] = $_POST['check_in_topic'];
        $data['check_in_type_code'] = $_POST['check_in_type_code'];
        $data['score'] = $_POST['score'];
        $data['updateby'] = $login_user['user_code'];  

        $result = $check_in_model->updateCheckInByCode($_POST['check_in_code'],$data);

        if($result){
            ?> <script> window.location="index.php?app=check_in" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลการเช็คอินได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลการเช็คอินได้'); </script> <?php
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'delete'&&$menu['check_in']['delete']){
    $result = $check_in_model->deleteCheckInByCode($check_in_code);
    ?> <script> window.location="index.php?app=check_in"</script> <?php
}else{
    $check_in = $check_in_model->getCheckInBy();
    require_once($path.'view.inc.php');
}
?>