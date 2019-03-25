<?php
require_once('../models/UserModel.php');
require_once('../models/ProductModel.php');
require_once('../models/StockGroupModel.php');
require_once('../models/StockMoveModel.php');
require_once('../models/StockMoveListModel.php');

$path = "modules/stock_move/views/";

$user_model = new UserModel;
$product_model = new ProductModel;
$stock_group_model = new StockGroupModel;
$stock_move_model = new StockMoveModel;
$stock_move_list_model = new StockMoveListModel;
 
$stock_move_code = $_GET['code'];

if(!isset($_GET['date_start'])){
    $date_start = $_SESSION['date_start'];
}else{
    $date_start = $_GET['date_start'];
    $_SESSION['date_start'] = $date_start;
}

if(!isset($_GET['date_end'])){
    $date_end = $_SESSION['date_end'];
}else{
    $date_end = $_GET['date_end'];
    $_SESSION['date_end'] = $date_end;
}
    $keyword = $_GET['keyword'];

if($date_start == ""){
    $date_start = date('01-m-Y'); 
}

if($date_end == ""){ 
    $date_end  = date('t-m-Y');
}

if ($_GET['action'] == 'insert'){
    $products = $product_model->getProductBy();
    $stock_groups = $stock_group_model->getStockGroupBy();
    $users = $user_model->getUserBy();
    require_once($path.'insert.inc.php');
}else if ($_GET['action'] == 'update'){
    $products = $product_model->getProductBy();
    $stock_groups = $stock_group_model->getStockGroupBy();
    $users = $user_model->getUserBy();
    $stock_move = $stock_move_model->getStockMoveByCode($stock_move_code);
    $stock_group = $stock_group_model->getStockGroupByCode($stock_move['stock_group_code']);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_code);

    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);

    for($i = 0 ; $i < count($stock_moves) ; $i++){
        if($stock_move_code == $stock_moves[$i]['stock_move_code']){ 
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];
        }
    }

    require_once($path.'update.inc.php');
}else if ($_GET['action'] == 'detail'){
    $stock_move = $stock_move_model->getStockMoveViewByCode($stock_move_code);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_code);

    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);

    for($i = 0 ; $i < count($stock_moves) ; $i++){
        if($stock_move_code == $stock_moves[$i]['stock_move_code']){ 
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $previous_code = $stock_moves[$i-1]['stock_move_code'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];
            $next_code = $stock_moves[$i+1]['stock_move_code'];

        }
    }

    require_once($path.'detail.inc.php');
}else if ($_GET['action'] == 'print'){
    $stock_move = $stock_move_model->getStockMoveViewByCode($stock_move_code);
    $stock_move_lists = $stock_move_list_model->getStockMoveListBy($stock_move_code);
    require_once($path.'print.inc.php');
}else if ($_GET['action'] == 'delete'){    
    $stock_move = $stock_move_model->getStockMoveByCode($stock_move_code);
    $stock_moves = $stock_move_model->deleteStockMoveByCode($stock_move_code);

    $maintenance_stock_model->runMaintenance($stock_move['stock_move_date']);

    ?> <script>window.location="index.php?app=stock_move"</script> <?php
}else if ($_GET['action'] == 'add'){
    if(isset($_POST['stock_move_code'])){
        $data = [];
        $data['stock_move_date'] = $_POST['stock_move_date'];
        $data['stock_move_code'] = $_POST['stock_move_code'];
        $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
        $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['stock_move_remark'] = $_POST['stock_move_remark'];

        $stock_move_code = $stock_move_model->insertStockMove($data);

        if($stock_move_code > 0){

            $product_code = $_POST['product_code'];
            $stock_move_list_code = $_POST['stock_move_list_code'];
            $stock_move_list_qty = $_POST['stock_move_list_qty'];
            $stock_move_list_remark = $_POST['stock_move_list_remark'];

            $stock_move_list_model->deleteStockMoveListByStockMoveIDNotIN($stock_move_code,$stock_move_list_code);

            if(is_array($product_code)){
                for($i=0; $i < count($product_code) ; $i++){
                    $data = [];
                    $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
                    $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
                    $data['stock_date'] = $_POST['stock_move_date'];
                    $data['stock_move_code'] = $stock_move_code;
                    $data['product_code'] = $product_code[$i];
                    $data['stock_move_list_qty'] = $stock_move_list_qty[$i];
                    $data['stock_move_list_remark'] = $stock_move_list_remark[$i];

                    if ($stock_move_list_code[$i] != "" && $stock_move_list_code[$i] != '0'){
                        $stock_move_list_model->updateStockMoveListByCode($data,$stock_move_list_code[$i]);
                    }else{
                        $stock_move_list_model->insertStockMoveList($data);
                    }
                }
            }else{
                $data = [];
                $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
                $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
                $data['stock_date'] = $_POST['stock_move_date'];
                $data['stock_move_code'] = $stock_move_code;
                $data['product_code'] = $product_code;
                $data['stock_move_list_qty'] = $stock_move_list_qty;
                $data['stock_move_list_remark'] = $stock_move_list_remark;
                if ($stock_move_list_code != "" && $stock_move_list_code != '0'){
                    $stock_move_list_model->updateStockMoveListByCode($data,$stock_move_list_code);
                }else{
                    $stock_move_list_model->insertStockMoveList($data);
                }
            }

            $maintenance_stock_model->runMaintenance($_POST['stock_move_date']);

            ?> <script>window.location="index.php?app=stock_move&action=update&id=<?php echo $stock_move_code;?>"</script> <?php
        }
    }else{
        ?> <script>window.history.back();</script> <?php
    }
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['stock_move_code'])){

        $stock_move = $stock_move_model->getStockMoveByCode($stock_move_code);

        $data = [];
        $data['stock_move_date'] = $_POST['stock_move_date'];
        $data['stock_move_code'] = $_POST['stock_move_code'];
        $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
        $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
        $data['employee_code'] = $_POST['employee_code'];
        $data['stock_move_remark'] = $_POST['stock_move_remark'];
    
        $output = $stock_move_model->updateStockMoveByCode($stock_move_code,$data);

        $product_code = $_POST['product_code'];
        $stock_move_list_code = $_POST['stock_move_list_code'];
        $stock_move_list_qty = $_POST['stock_move_list_qty'];
        $stock_move_list_remark = $_POST['stock_move_list_remark'];

        $stock_move_list_model->deleteStockMoveListByStockMoveIDNotIN($stock_move_code,$stock_move_list_code);

        if(is_array($product_code)){
            for($i=0; $i < count($product_code) ; $i++){
                $data = [];
                $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
                $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
                $data['stock_date'] = $_POST['stock_move_date'];
                $data['stock_move_code'] = $stock_move_code;
                $data['product_code'] = $product_code[$i];
                $data['stock_move_list_qty'] = $stock_move_list_qty[$i];
                $data['stock_move_list_remark'] = $stock_move_list_remark[$i];

                if ($stock_move_list_code[$i] != "" && $stock_move_list_code[$i] != '0'){
                    $stock_move_list_model->updateStockMoveListByCode($data,$stock_move_list_code[$i]);
                }else{
                    $stock_move_list_model->insertStockMoveList($data);
                }
            }
        }else{
            $data = [];
            $data['stock_group_code_out'] = $_POST['stock_group_code_out'];
            $data['stock_group_code_in'] = $_POST['stock_group_code_in'];
            $data['stock_date'] = $_POST['stock_move_date'];
            $data['stock_move_code'] = $stock_move_code;
            $data['product_code'] = $product_code;
            $data['stock_move_list_qty'] = $stock_move_list_qty;
            $data['stock_move_list_remark'] = $stock_move_list_remark;
            if ($stock_move_list_code != "" && $stock_move_list_code != '0'){
                $stock_move_list_model->updateStockMoveListByCode($data,$stock_move_list_code);
            }else{
                $stock_move_list_model->insertStockMoveList($data);
            }
            
        }
        
        if($output){
            $old_date = DateTime::createFromFormat('d-m-Y',$stock_move['stock_move_date']);
            $new_date =  DateTime::createFromFormat('d-m-Y',$_POST['stock_move_date']);

            if($old_date < $new_date){
                $maintenance_stock_model->runMaintenance($stock_move['stock_move_date']);
            }else{
                $maintenance_stock_model->runMaintenance($_POST['stock_move_date']);
            }
            ?> <script>window.location="index.php?app=stock_move"</script> <?php
        }
    }else{
        ?> <script>window.history.back();</script> <?php
    }
}else{
    $stock_moves = $stock_move_model->getStockMoveBy($date_start,$date_end,$keyword);
    require_once($path.'view.inc.php');
}
?>