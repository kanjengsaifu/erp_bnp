<?php
require_once('../models/StockModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/StockListModel.php');
require_once('../models/StockTypeModel.php');
require_once('../models/UserModel.php');
require_once('../models/ContractorModel.php');

$path = "modules/stock/views/";

$stock_model = new StockModel;
$stock_group_model = new StockGroupModel;
$stock_list_model = new StockListModel;
$stock_type_model = new StockTypeModel;
$user_model = new UserModel;
$contractor_model = new ContractorModel;

$stock_group_code = $_GET['code'];
$stock_code = $_GET['stock_code'];

if ($_GET['action'] == 'insert' && $menu['stock']['add']){
    $user = $user_model->getUserBy();
    $stock_type = $stock_type_model->getStockTypeBy();
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'add' && $menu['stock']['add']){
    if ($_POST['stock_group_code'] == ''){
        $code = date('y').date('m').date('d');
        $stock_group_code = $stock_group_model->getStockGroupLastCode($code,4);  
    }else{
        $stock_group_code = $_POST['stock_group_code'];
    }

    if(isset($_POST['stock_group_name']) && $stock_group_code != ''){
        $table_name = "tb_stock_".$stock_group_code;
        $stock_model->setTableName($table_name);
        $result = $stock_model->createStockTable();

        if($result){
            $data = [];
            $data['stock_group_code'] = $stock_group_code;
            $data['stock_type_code'] = $_POST['stock_type_code'];
            $data['stock_group_name'] = $_POST['stock_group_name'];
            $data['stock_group_description'] = $_POST['stock_group_description'];
            $data['stock_group_day'] = $_POST['stock_group_day'];
            $data['admin_code'] = $_POST['admin_code'];
            $data['notification'] = $_POST['notification'];
            $data['addby'] = $login_user['user_code'];

            $result = $stock_group_model->insertStockGroup($data);

            if($result){
                $stock_group_model->updateTableName($stock_group_code,$table_name);
                ?><script>window.location="index.php?app=stock&action=update&code=<?php echo $stock_group_code; ?>"</script> <?php
            }else{
                ?> <script> alert('ไม่สามารถเพิ่มข้อมูลได้'); </script> <?php
                ?> <script>window.history.back();</script> <?php
            }
        }else{
            ?> <script> alert('ไม่สามารถเพิ่มข้อมูลได้'); </script> <?php
            ?> <script>window.history.back();</script> <?php
        }
    }else{
        ?> <script> alert('ไม่สามารถเพิ่มข้อมูลได้'); </script> <?php
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'update' && $menu['stock']['edit']){
    $stock_group = $stock_group_model->getStockGroupByCode($stock_group_code);

    if ($stock_group['stock_type_code'] == 'SGU001'){
        $user = $user_model->getUserBy();
    }else{
        $contractor = $contractor_model->getContractorBy();
    }
    $stock_type = $stock_type_model->getStockTypeBy();
    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'edit' && $menu['stock']['edit']){
    if(isset($_POST['stock_group_code'])){
        $data = [];
        $data['stock_group_name'] = $_POST['stock_group_name'];
        $data['stock_group_description'] = $_POST['stock_group_description'];
        $data['stock_group_day'] = $_POST['stock_group_day'];
        $data['admin_code'] = $_POST['admin_code'];
        $data['notification'] = $_POST['notification'];
        $data['updateby'] = $login_user['user_code'];
       
        $result = $stock_group_model->updateStockGroupByCode($_POST['stock_group_code'],$data);

        if($result){
            ?> <script>window.location="index.php?app=stock"</script> <?php
        }else{
            ?> <script> alert('ไม่สามารถอัพเดตข้อมูลได้'); </script> <?php
            ?> <script>window.history.back();</script> <?php
        }
    }else{
        ?> <script> alert('ไม่สามารถอัพเดตข้อมูลได้'); </script> <?php
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'delete' && $menu['stock']['delete']){
    $stock_group = $stock_group_model->getStockGroupByCode($stock_group_code);
    $stock_model->setTableName($stock_group['table_name']);
    if($stock_group_model->deleteStockGroupByCode($stock_group_code)){
        $stock_model->deleteStockTable();
    }
    ?> <script>window.location="index.php?app=stock"</script> <?php
}else if ($_GET['action'] == 'stock_list'){
    $stock_group = $stock_group_model->getStockGroupByCode($stock_group_code);
    $stock_list = $stock_list_model->getStockListByGroup($stock_group_code,$keyword);

    if ($stock_group['stock_type_code'] == 'SGU001'){
        $admin = $user_model->getUserByCode($stock_group['admin_code']);
    }else{
        $admin = $contractor_model->getContractorByCode($stock_group['admin_code']);
    }

    require_once($path.'view-list.inc.php');
}else{
    $stock_type = $stock_type_model->getStockTypeBy();
    require_once($path.'view.inc.php');
}
?>