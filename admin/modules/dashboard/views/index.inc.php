<?php
date_default_timezone_set('Asia/Bangkok');

require_once('../models/DashboardModel.php');

$path = "modules/dashboard/views/";

$dashboard_model = new DashboardModel;

$dashboard = $dashboard_model->getDashboardBy();

require_once($path.'view.inc.php');
?>