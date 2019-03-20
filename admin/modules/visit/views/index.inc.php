<?php
require_once('../models/VisitModel.php');
require_once('../models/VisitListModel.php');

$path = "modules/visit/views/";

$visit_model = new VisitModel;
$visit_list_model = new VisitListModel; 

$visit_code = $_GET['code'];
$visit_list_code = $_GET['list'];

// foreach ($_POST as $key => $value) {
//     echo "<div>";
//     echo $key;
//     echo " : ";
//     echo $value;
//     echo "</div>";
// }

if ($_GET['action'] == 'insert'&&$menu['visit']['add']){ 
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'add'&&$menu['visit']['add']){
    if ($_POST['visit_code'] == ''){
        $code = "VS".date('y').date('m').date('d');
        $visit_code = $visit_model->getVisitLastCode($code,5);  
    }else{
        $visit_code = $_POST['visit_code'];
    }

    if($visit_code != '' && isset($_POST['visit_name'])){
        $data = [];  
        $data['visit_code'] = $visit_code;
        $data['visit_name'] = $_POST['visit_name'];
        $data['visit_description'] = $_POST['visit_description'];
        $data['addby'] = $login_user['user_code'];  

        $result = $visit_model->insertVisit($data);

        if($result){
            ?> <script> window.location="index.php?app=visit&action=update&code=<?php echo $visit_code; ?>" </script> <?php
        }else{
            ?> <script> window.history.back(); </script> <?php
        }
    }else{
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'update'&&$menu['visit']['edit']){
    $visit = $visit_model->getVisitByCode($visit_code);
    $visit_list = $visit_list_model->getVisitListByVisit($visit_code);
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'edit'&&$menu['visit']['edit']){
    if(isset($_POST['visit_code'])){
        $data = [];  
        $data['visit_name'] = $_POST['visit_name'];
        $data['visit_description'] = $_POST['visit_description'];
        $data['updateby'] = $login_user['user_code'];  

        $result = $visit_model->updateVisitByCode($_POST['visit_code'],$data);

        if(!$result){
            ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่ได้'); </script> <?php
        }
        ?> <script> window.history.back(); </script> <?php
    }else{
        ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่ได้'); </script> <?php
        ?> <script> window.history.back(); </script> <?php
    }
}else if ($_GET['action'] == 'insert-list'&&$menu['visit']['add']){
    $visit = $visit_model->getVisitByCode($visit_code);
    require_once($path.'insert-list.inc.php');
}else if ($_GET['action'] == 'add-list'&&$menu['visit']['add']){
    if(isset($_POST['visit_code'])){
        $data = [];  
        $data['visit_list_code'] = $_POST['visit_code'].$_POST['village_id'];
        $data['visit_code'] = $_POST['visit_code'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_code'] = $_POST['agent_code'];
        $data['dealer_code'] = $_POST['dealer_code'];
        $data['addby'] = $login_user['user_code'];

        $result = $visit_list_model->insertVisitList($data);

        if($result){
            ?> <script> window.location="index.php?app=visit&action=update&code=<?php echo $_POST['visit_code']; ?>" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเพิ่มพื้นที่หมู่บ้านได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }
    ?> <script> alert('ไม่สามารถเพิ่มพื้นที่หมู่บ้านได้'); </script> <?php
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'update-list'&&$menu['visit']['edit']){
    $visit_list = $visit_list_model->getVisitListByCode($visit_list_code);
    require_once($path.'update-list.inc.php');
}else if ($_GET['action'] == 'edit-list'&&$menu['visit']['add']){
    if(isset($_POST['visit_list_code'])){
        $data = [];  
        $data['visit_list_code'] = $_POST['visit_code'].$_POST['village_id'];
        $data['village_id'] = $_POST['village_id'];
        $data['agent_code'] = $_POST['agent_code'];
        $data['dealer_code'] = $_POST['dealer_code'];
        $data['updateby'] = $login_user['user_code'];

        $result = $visit_list_model->updateVisitListByCode($_POST['visit_list_code'],$data);

        if($result){
            ?> <script> window.location="index.php?app=visit&action=update&code=<?php echo $_POST['visit_code']; ?>" </script> <?php
        }else{
            ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่หมู่บ้านได้'); </script> <?php
            ?> <script> window.history.back(); </script> <?php
        }
    }
    ?> <script> alert('ไม่สามารถเเก้ไขข้อมูลพื้นที่หมู่บ้านได้'); </script> <?php
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete-list'&&$menu['visit']['delete']){
    $result = $visit_list_model->deleteVisitlistByCode($visit_list_code);
    ?> <script> window.history.back(); </script> <?php
}else if ($_GET['action'] == 'delete'&&$menu['visit']['delete']){
    $result = $visit_model->deleteVisitByCode($visit_code);
    ?> <script> window.location="index.php?app=visit"</script> <?php
}else{
    $visit = $visit_model->getVisitBy();
    require_once($path.'view.inc.php');
}
?>