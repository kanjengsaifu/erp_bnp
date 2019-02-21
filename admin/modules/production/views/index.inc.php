<?php
require_once('../models/OrderProcressModel.php');

$path = "modules/production/views/";

$order_procress_model = new OrderProcressModel;

$branch_code = $login_user['branch_code'];

if($_GET['status'] == 'wash'){ 
    $wait = $order_procress_model->getWashByStatus(1,$branch_code);
    $production = $order_procress_model->getWashByStatus(2,$branch_code);
    $finished = $order_procress_model->getWashByStatus(3,$branch_code);
}else if($_GET['status'] == 'drying'){ 
    $wait = $order_procress_model->getDryingByStatus(1,$branch_code);
    $production = $order_procress_model->getDryingByStatus(2,$branch_code);
    $finished = $order_procress_model->getDryingByStatus(3,$branch_code);
}else if($_GET['status'] == 'iron'){ 
    $wait = $order_procress_model->getIronByStatus(1,$branch_code);
    $production = $order_procress_model->getIronByStatus(2,$branch_code);
    $finished = $order_procress_model->getIronByStatus(3,$branch_code);
}else if($_GET['status'] == 'fold'){ 
    $wait = $order_procress_model->getFoldByStatus(1,$branch_code);
    $production = $order_procress_model->getFoldByStatus(2,$branch_code);
    $finished = $order_procress_model->getFoldByStatus(3,$branch_code);
}else if($_GET['status'] == 'check'){ 
    $wait = $order_procress_model->getCheckByStatus(1,$branch_code);
    $production = $order_procress_model->getCheckByStatus(2,$branch_code);
    $finished = $order_procress_model->getCheckByStatus(3,$branch_code);
}else{
    $data = array_merge($order_procress_model->getWashByStatus(1,$branch_code), $order_procress_model->getDryingByStatus(1,$branch_code));
    $data = array_merge($data, $order_procress_model->getIronByStatus(1,$branch_code));
    $data = array_merge($data, $order_procress_model->getFoldByStatus(1,$branch_code));
    $wait = array_merge($data, $order_procress_model->getCheckByStatus(1,$branch_code));

    $data = array_merge($order_procress_model->getWashByStatus(2,$branch_code), $order_procress_model->getDryingByStatus(2,$branch_code));
    $data = array_merge($data, $order_procress_model->getIronByStatus(2,$branch_code));
    $data = array_merge($data, $order_procress_model->getFoldByStatus(2,$branch_code));
    $production = array_merge($data, $order_procress_model->getCheckByStatus(2,$branch_code));
    
    $data = array_merge($order_procress_model->getWashByStatus(3,$branch_code), $order_procress_model->getDryingByStatus(3,$branch_code));
    $data = array_merge($data, $order_procress_model->getIronByStatus(3,$branch_code));
    $data = array_merge($data, $order_procress_model->getFoldByStatus(3,$branch_code));
    $finished = array_merge($data, $order_procress_model->getCheckByStatus(3,$branch_code));
}

require_once($path.'view.inc.php');
?>