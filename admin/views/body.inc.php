<?php
    /* ----------------------------------- ระบบพื้นฐาน --------------------------------- */
    if(!isset($_GET['app'])){
        require_once("modules/dashboard/views/index.inc.php");
    }else if($_GET['app'] == "user"){
        require_once("modules/user/views/index.inc.php");
    }else if($_GET['app'] == "license"){
        require_once("modules/license/views/index.inc.php");   
    }else if($_GET['app'] == "company"){
        require_once("modules/company/views/index.inc.php");   
    }else if($_GET['app'] == "supplier"){
        require_once("modules/supplier/views/index.inc.php");   
    }else if($_GET['app'] == "purchase_order"){
        require_once("modules/purchase_order/views/index.inc.php");   
    }else if($_GET['app'] == "material"){
        require_once("modules/material/views/index.inc.php");   
    }else if($_GET['app'] == "unit"){
        require_once("modules/unit/views/index.inc.php");   
    }else if($_GET['app'] == "material_type"){
        require_once("modules/material_type/views/index.inc.php");   
    }else if($_GET['app'] == "invoice_supplier"){
        require_once("modules/invoice_supplier/views/index.inc.php");   
    }else if($_GET['app'] == "production"){
        require_once("modules/production/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบพื้นฐาน// ---------------------------------------------- */
    /* ----------------------------------- ระบบจัดการเขต --------------------------------- */
    else if($_GET['app'] == "zone" && $menu['zone']['view']){
        require_once("modules/zone/views/index.inc.php");   
    }
    /* ----------------------------------- //ระบบจัดการเขต// ---------------------------------------------- */

    /* ----------------------------------- ระบบจัดการตัวเเทน ผู้รับเหมา --------------------------------- */
    else if($_GET['app'] == "agent" && $menu['agent']['view']){
        require_once("modules/agent/views/index.inc.php");   
    }else if($_GET['app'] == "contractor" && $menu['contractor']['view']){
        require_once("modules/contractor/views/index.inc.php");
    }else if($_GET['app'] == "songserm" && $menu['songserm']['view']){
        require_once("modules/songserm/views/index.inc.php");   
    }
    /* ----------------------------------- //ระบบจัดการตัวเเทน ผู้รับเหมา// ---------------------------------------------- */
    
?>