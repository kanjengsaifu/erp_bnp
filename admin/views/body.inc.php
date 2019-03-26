<?php
    /* ----------------------------------- ระบบงาน --------------------------------- */
    if(!isset($_GET['app']) && $menu['dashboard']['view']){
        require_once("modules/dashboard/views/index.inc.php");
    }else if($_GET['app'] == "order" && $menu['order']['view']){
        require_once("modules/order/views/index.inc.php");   
    }else if($_GET['app'] == "job" && $menu['job']['view']){
        require_once("modules/job/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบงาน// ----------------------------------- */

    /* ----------------------------------- ระบบจัดซื้อ --------------------------------- */
    else if($_GET['app'] == "purchase_request" && $menu['purchase_request']['view']){
        require_once("modules/purchase_request/views/index.inc.php");
    }else if($_GET['app'] == "purchase_order" && $menu['purchase_order']['view']){
        require_once("modules/purchase_order/views/index.inc.php");   
    }else if($_GET['app'] == "invoice_supplier" && $menu['invoice_supplier']['view']){
        require_once("modules/invoice_supplier/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบจัดซื้อ// ----------------------------------- */


    /* ----------------------------------- ระบบพื้นฐาน --------------------------------- */
    else if($_GET['app'] == "user" && $menu['user']['view']){
        require_once("modules/user/views/index.inc.php");
    }else if($_GET['app'] == "license" && $menu['license']['view']){
        require_once("modules/license/views/index.inc.php");   
    }else if($_GET['app'] == "company" && $menu['company']['view']){
        require_once("modules/company/views/index.inc.php");   
    }else if($_GET['app'] == "supplier" && $menu['supplier']['view']){
        require_once("modules/supplier/views/index.inc.php");   
    }else if($_GET['app'] == "material" && $menu['material']['view']){
        require_once("modules/material/views/index.inc.php");   
    }else if($_GET['app'] == "unit" && $menu['unit']['view']){
        require_once("modules/unit/views/index.inc.php");   
    }else if($_GET['app'] == "material_type" && $menu['material_type']['view']){
        require_once("modules/material_type/views/index.inc.php");   
    }else if($_GET['app'] == "production" && $menu['production']['view']){
        require_once("modules/production/views/index.inc.php");
    }else if($_GET['app'] == "product" && $menu['product']['view']){
        require_once("modules/product/views/index.inc.php");   
    }else if($_GET['app'] == "product_type" && $menu['product_type']['view']){
        require_once("modules/product_type/views/index.inc.php");   
    }else if($_GET['app'] == "product_brand" && $menu['product_brand']['view']){
        require_once("modules/product_brand/views/index.inc.php");   
    }else if($_GET['app'] == "project" && $menu['project']['view']){
        require_once("modules/project/views/index.inc.php");   
    }else if($_GET['app'] == "satisfaction" && $menu['satisfaction']['view']){
        require_once("modules/satisfaction/views/index.inc.php");   
    }else if($_GET['app'] == "contact_way" && $menu['contact_way']['view']){
        require_once("modules/contact_way/views/index.inc.php");   
    }else if($_GET['app'] == "contact_type" && $menu['contact_type']['view']){
        require_once("modules/contact_type/views/index.inc.php");   
    }else if($_GET['app'] == "address" && $menu['address']['view']){
        require_once("modules/address/views/index.inc.php");   
    }else if($_GET['app'] == "zone" && $menu['zone']['view']){
        require_once("modules/zone/views/index.inc.php");   
    }else if($_GET['app'] == "check_in" && $menu['check_in']['view']){
        require_once("modules/check_in/views/index.inc.php");   
    }
    /* ----------------------------------- //ระบบพื้นฐาน// ----------------------------------- */

    /* -----------------------------------   ระบบคลังสินค้า   ----------------------------------- */
    else if($_GET['app'] == "search_product" && $menu['stock']['view']){
        require_once("modules/search_product/views/index.inc.php");
    }else if($_GET['app'] == "stock" && $menu['stock']['view']){
        require_once("modules/stock/views/index.inc.php");
    }else if($_GET['app'] == "stock_move" && $menu['stock']['view']){
        require_once("modules/stock_move/views/index.inc.php");
    }else if($_GET['app'] == "stock_issue" && $menu['stock']['view']){
        require_once("modules/stock_issue/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบคลังสินค้า// ----------------------------------- */

    /* ----------------------------------- ระบบจัดการตัวเเทน ผู้รับเหมา ----------------------------------- */
    else if($_GET['app'] == "farmer" && $menu['farmer']['view']){
        require_once("modules/farmer/views/index.inc.php");   
    }else if($_GET['app'] == "agent" && $menu['agent']['view']){
        require_once("modules/agent/views/index.inc.php");   
    }else if($_GET['app'] == "dealer" && $menu['dealer']['view']){
        require_once("modules/dealer/views/index.inc.php");   
    }else if($_GET['app'] == "contractor" && $menu['contractor']['view']){
        require_once("modules/contractor/views/index.inc.php");
    }else if($_GET['app'] == "songserm" && $menu['songserm']['view']){
        require_once("modules/songserm/views/index.inc.php");   
    }
    /* ----------------------------------- //ระบบจัดการตัวเเทน ผู้รับเหมา// ---------------------------------------------- */
?>