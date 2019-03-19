<?PHP 
require_once('../models/MenuModel.php');  
require_once('../models/LicensePermissionModel.php'); 

$menu_model = new MenuModel;
$license_permission_model = new LicensePermissionModel; 

$menu_list = $menu_model->getMenuBy();
$license_permission = $license_permission_model->getLicensePermissionByUserCode($login_user['user_code']); 

$menu = [];
for($i = 0 ; $i < count($menu_list); $i++){

    $menu_name_eng = $menu_list[$i]['menu_name_eng'];  
    $id = '1';   
    $action = 'view';  
    $menu[$menu_list[$i]['menu_name_eng']][$action] = count(array_filter($license_permission, function ($var) use ($menu_name_eng,$action,$id) { 
        return ($var['menu_name_eng'] == trim($menu_name_eng)&&$var['license_permission_'.$action] ==$id); 
    }));      
    $action = 'add';  
    $menu[$menu_list[$i]['menu_name_eng']][$action] = count(array_filter($license_permission, function ($var) use ($menu_name_eng,$action,$id) { 
        return ($var['menu_name_eng'] == trim($menu_name_eng)&&$var['license_permission_'.$action] ==$id); 
    }));      
    $action = 'edit';  
    $menu[$menu_list[$i]['menu_name_eng']][$action] = count(array_filter($license_permission, function ($var) use ($menu_name_eng,$action,$id) { 
        return ($var['menu_name_eng'] == trim($menu_name_eng)&&$var['license_permission_'.$action] ==$id); 
    }));      
    $action = 'delete';  
    $menu[$menu_list[$i]['menu_name_eng']][$action] = count(array_filter($license_permission, function ($var) use ($menu_name_eng,$action,$id) { 
        return ($var['menu_name_eng'] == trim($menu_name_eng)&&$var['license_permission_'.$action] ==$id); 
    }));      
    
}
// echo "<pre>";
// print_r($menu);
// echo "</pre>";
// echo "<pre>";
// print_r($license_permission);
// echo "</pre>";
?>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="background-color: #70b451; margin-bottom: 0px;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="?" style="color: #fff;">
            BNP Group
        </a>
    </div>

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" style="color: #fff; cursor: pointer;">
                <i class="fa fa-bell fa-fw">
                    <?php if(count($notifications_new) > 0){?>
                    <span class="alert">
                        <?php echo count($notifications_new);?>
                    </span>
                    <?php } ?>
                </i> 
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-alerts scroll1">
                <?php 
                for($i=0 ; $i < count($notifications) ;$i++){ ?>
                    <li <?php if($notifications[$i]['notification_seen_date'] == ""){ ?>class="notify-active"<?php }else{ ?> class="notify" <?php } ?> >
                        <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" >
                            <div>
                            <?php 
                                if($notifications[$i]['notification_type'] =='Purchase Request'){ ?><i class="fa fa-comments fa-fw fa-notify"></i> <?php }
                                else if ($notifications[$i]['notification_type'] =='Purchase Order'){?><i class="fa fa-tasks fa-fw fa-notify"></i> <?php }
                                else if ($notifications[$i]['notification_type'] =='Customer Order'){?><i class="fa fa-cart-plus fa-fw fa-notify"></i> <?php }
                                else {?><i class="fa fa-support fa-fw fa-notify"></i> <?php }
                            ?>
                                <?php echo $notifications[$i]['notification_detail'];?> 
                                <div class=" text-muted small"><?php echo $notifications[$i]['notification_date'];?></div>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                <?php
                } 
                ?>
                <li class="sticky-bot">
                    <a class="see_all" href="?app=notification">
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" style="color: #fff; cursor: pointer;">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="?app=user_profile"><i class="fa fa-user fa-fw"></i> โปรไฟล์ผู้ใช้</a>
                </li>
                <li><a style="cursor: pointer;"><i class="fa fa-gear fa-fw"></i> การตั้งค่า</a>
                </li>
                <li class="divider"></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> ออกจากระบบ</a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <div class="text-center" style="padding: 10px 15px;">
                        <div style="font-size: 24px;color: #999999;">ระบบจัดการ<br>โครงการปรับปรุงดิน</div>
                        <div style="font-size: 0.75em;color: #a9a9a9;">enterprise resource planning</div>
                    </div>
                </li>
                <li>
                    <a href="?" <?PHP if(!isset($_GET['app'])){ ?> class="active" <?PHP } ?>><i class="fa fa-line-chart" aria-hidden="true"></i> ภาพรวม</a>
                </li>   
                <li> 
                    <a href="?app=order" <?PHP if($_GET["app"]=='order'){ ?> class="active" <?PHP } ?>><i class="fa fa-file-text-o" aria-hidden="true"></i> ใบรับงาน</a>
                </li>
                <li>
                    <a href="?app=job" <?PHP if($_GET["app"]=='job'){ ?> class="active" <?PHP } ?>><i class="fa fa-gears" aria-hidden="true"></i> งาน</a>
                </li>
                <!-- <li>
                    <a href="?app=product" <?PHP if($_GET["app"]=='product'){ ?> class="active" <?PHP } ?>><i class="fa fa-th" aria-hidden="true"></i> สินค้า</a>
                </li> -->
                <li>
                    <a href="?app=stock" <?PHP if($_GET["app"]=='stock'){ ?> class="active" <?PHP } ?>><i class="fa fa-cubes" aria-hidden="true"></i> คลัง</a>
                </li>
                <?php if ($menu['zone']['view']){ ?>
                <li>
                    <a href="?app=zone" <?PHP if($_GET['app'] == "zone"){?> class="active" <?PHP } ?> ><i class="fa fa-map-marker" aria-hidden="true"></i> เขตการขาย</a>
                </li>
                <?php } ?>
                <?php if ($menu['farmer']['view']){ ?>
                <li>
                    <a href="?app=farmer" <?PHP if($_GET['app'] == "farmer"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> เกษตรกร</a>
                </li>
                <?php } ?>
                <?php if ($menu['agent']['view']){ ?>
                <li>
                    <a href="?app=agent" <?PHP if($_GET['app'] == "agent"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> นายหน้า</a>
                </li>
                <?php } ?>
                <?php if ($menu['dealer']['view']){ ?>
                <li>
                    <a href="?app=dealer" <?PHP if($_GET['app'] == "dealer"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ตัวเเทนจำหน่าย</a>
                </li>
                <?php } ?>
                <?php if ($menu['contractor']['view']){ ?>
                <li>
                    <a href="?app=contractor" <?PHP if($_GET['app'] == "contractor"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ผู้รับเหมา</a>
                </li>
                <?php } ?>
                <?php if ($menu['songserm']['view']){ ?>
                <li>
                    <a href="?app=songserm" <?PHP if($_GET['app'] == "songserm"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ส่งเสริม</a>
                </li>
                <?php } ?>
                <?php if ($menu['satisfaction']['view']){ ?>
                <li>
                    <a href="?app=satisfaction" <?PHP if($_GET['app'] == "satisfaction"
                                                        || $_GET["app"]=='contact_type'
                                                        || $_GET["app"]=='contact_way'
                                                        ){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ความพึงพอใจ</a>
                </li>
                <?php } ?>
                <li
                <?PHP 
                    if(
                        $_GET["app"]=='account' 
                    ){
                        echo ' class="active" ';
                    }
                ?> 
                >
                    <a style="cursor: pointer;" class="nav-title">
                        <i class="fa fa-calculator" aria-hidden="true"></i> การบัญชี<span class="glyphicon arrow"></span>
                    </a>
                    <ul class="collapse">  
                        <li>
                            <a href="?app=invoice"><i class="fa fa-book fa-fw" aria-hidden="true"></i> อินวอย</a>
                        </li>
                        <li>
                            <a href="?app=billing"><i class="fa  fa-flag-o fa-fw" aria-hidden="true"></i> วางบิล</a>
                        </li>
                        <li>
                            <a href="?app=billing"><i class="fa  fa-file-text-o fa-fw" aria-hidden="true"></i> ใบเสร็จ</a>
                        </li>
                        <li>
                            <a href="?app=billing"><i class="fa  fa-outdent fa-fw" aria-hidden="true"></i> จ่ายเงิน</a>
                        </li>
                        <li>
                            <a href="?app=billing"><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> ค่าใช้จ่าย</a>
                        </li>
                    </ul>
                </li>
                <li
                <?PHP 
                    if($_GET["app"]=='user'
                        || $_GET["app"]=='supplier'
                        || $_GET["app"]=='company' 
                        || $_GET["app"]=='license' 
                        || $_GET["app"]=='material' 
                        || $_GET["app"]=='unit' 
                        || $_GET["app"]=='material_type' 
                        || $_GET["app"]=='product' 
                        || $_GET["app"]=='product_type'
                        || $_GET["app"]=='product_brand'
                        || $_GET["app"]=='project'
                        || $_GET["app"]=='address'
                    ){
                        echo ' class="active" ';
                    }
                ?> 
                >
                    <a style="cursor: pointer;" class="nav-title">
                        <i class="fa fa-database" aria-hidden="true"></i> ข้อมูลหลัก <span class="glyphicon arrow"></span>
                    </a>
                    <ul class="collapse"> 
                        <?PHP if($menu['material']['view']==1){ ?>
                        <li>
                            <a href="?app=material" <?PHP if(
                                $_GET['app'] == "material"
                            || $_GET["app"]=='unit'
                            || $_GET["app"]=='material_type'
                            ){?> class="active" <?PHP } ?> ><i class="fa fa-briefcase" aria-hidden="true"></i> วัตถุดิบ</a>
                        </li>
                        <?PHP }?> 
                        <?PHP if($menu['product']['view']==1){ ?>
                        <li>
                            <a href="?app=product" <?PHP if(
                                $_GET['app'] == "product" 
                            || $_GET["app"]=='product_type'
                            || $_GET["app"]=='product_brand'
                            ){?> class="active" <?PHP } ?> ><i class="fa fa-product-hunt" aria-hidden="true"></i> สินค้า</a>
                        </li>
                        <?PHP }?> 
                        <?PHP if($menu['project']['view']==1){ ?>
                        <li>
                            <a href="?app=project" <?PHP if($_GET['app'] == "project"){?> class="active" <?PHP } ?> ><i class="fa fa-building" aria-hidden="true"></i> โครงการปรับปรุงดิน</a>
                        </li> 
                        <?PHP }?> 
                        <?PHP if($menu['company']['view']==1){ ?>
                        <li>
                            <a href="?app=company" <?PHP if($_GET['app'] == "company"|| $_GET["app"]=='branch' ){?> class="active" <?PHP } ?> ><i class="fa fa-gears" aria-hidden="true"></i> ตั้งค่าระบบ (System Setting)</a>
                        </li>
                        <?PHP }?> 
                        <?PHP if($menu['user']['view']==1){ ?>
                        <li>
                            <a href="?app=user" <?PHP if($_GET['app'] == "user" || $_GET["app"]=='license'){?> class="active" <?PHP } ?> ><i class="fa fa-user" aria-hidden="true"></i> พนักงาน (Employee)</a>
                        </li>
                        <?PHP }?>  
                        <?PHP if($menu['supplier']['view']==1){ ?>
                        <li>
                            <a href="?app=supplier" <?PHP if($_GET['app'] == "supplier"){?> class="active" <?PHP } ?> ><i class="fa fa-building-o" aria-hidden="true"></i> ผู้ขาย (Supplier)</a>
                        </li> 
                        <?PHP }?> 
                        <?PHP if($menu['address']['view']==1){ ?>
                        <li>
                            <a href="?app=address" <?PHP if($_GET['app'] == "address"){?> class="active" <?PHP } ?> ><i class="fa fa-map-marker" aria-hidden="true"></i> ข้อมูลพื้นที่ (Area)</a>
                        </li> 
                        <?PHP }?> 
                    </ul>
                </li>
                <li
                <?PHP 
                    if(
                        substr($_GET["app"],0,13) =='report_debtor'  || 
                        substr($_GET["app"],0,15) =='report_creditor' ||
                        substr($_GET["app"],0,10) =='report_tax' ||
                        substr($_GET["app"],0,12) =='report_stock'||
                        substr($_GET["app"],0,14) =='report_account'
                    ){
                        echo ' class="active" ';
                    }
                ?> 
                >
                    <a style="cursor: pointer;" class="nav-title">
                        <i class="fa fa-file-text-o" aria-hidden="true"></i> รายงาน<span class="glyphicon arrow"></span>
                    </a>
                    <ul class="collapse">
                        <li
                        <?PHP 
                            if(
                                substr($_GET["app"],0,13) =='report_debtor'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a style="cursor: pointer;" >
                                <i class="fa fa-line-chart" aria-hidden="true"></i> ลูกหนี้ <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" > 
                                <li>
                                    <a href="?app=report_debtor_01" <?PHP if($_GET['app'] == "report_debtor_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบกำกับภาษี</a>
                                </li> 
                                <li>
                                    <a href="?app=report_debtor_02" <?PHP if($_GET['app'] == "report_debtor_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบวางบิล</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_03" <?PHP if($_GET['app'] == "report_debtor_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบลดหนี้/รับคืน</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_04" <?PHP if($_GET['app'] == "report_debtor_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รับชำระหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_05" <?PHP if($_GET['app'] == "report_debtor_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ลูกหนี้คงค้าง</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_06" <?PHP if($_GET['app'] == "report_debtor_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สถานะลูกหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_07" <?PHP if($_GET['app'] == "report_debtor_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> วิเคราะห์อายุลูกหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_08" <?PHP if($_GET['app'] == "report_debtor_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายการเคลื่อนไหวลูกหนี้</a>
                                </li> 
                                <li>
                                    <a href="?app=report_debtor_09" <?PHP if($_GET['app'] == "report_debtor_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดลูกค้า</a>
                                </li>
                                <li>
                                    <a href="?app=report_debtor_10" <?PHP if($_GET['app'] == "report_debtor_10"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบเสนอราคา</a>
                                </li>
                                
                            </ul>
                        </li>

                        <li
                        <?PHP 
                            if(
                                substr($_GET["app"],0,15) =='report_creditor'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a style="cursor: pointer;" >
                                <i class="fa fa-line-chart" aria-hidden="true"></i> เจ้าหนี้ <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" > 
                                <li>
                                    <a href="?app=report_creditor_01" <?PHP if($_GET['app'] == "report_creditor_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบสั่งซื้อ</a>
                                </li>
                                <li>
                                    <a href="?app=report_creditor_02" <?PHP if($_GET['app'] == "report_creditor_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ซื้อเงินเชื่อ(ใบรับสินค้า)</a>
                                </li>
                                <li>
                                    <a href="?app=report_creditor_03" <?PHP if($_GET['app'] == "report_creditor_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ค่าใช้จ่ายอื่นๆ</a>
                                </li> 
                                <li>
                                    <a href="?app=report_creditor_04" <?PHP if($_GET['app'] == "report_creditor_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> การจ่ายชำระหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_creditor_05" <?PHP if($_GET['app'] == "report_creditor_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> เจ้าหนี้คงค้าง</a>
                                </li> 
                                <li>
                                    <a href="?app=report_creditor_06" <?PHP if($_GET['app'] == "report_creditor_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สถานะเจ้าหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_creditor_07" <?PHP if($_GET['app'] == "report_creditor_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> วิเคราะห์อายุเจ้าหนี้</a>
                                </li>
                                <li>
                                    <a href="?app=report_creditor_08" <?PHP if($_GET['app'] == "report_creditor_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายการเคลื่อนไหวเจ้าหนี้</a>
                                </li> 
                                <li>
                                    <a href="?app=report_creditor_09" <?PHP if($_GET['app'] == "report_creditor_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดผู้จำหน่าย</a>
                                </li>
                            </ul>
                        </li>

                        <li
                        <?PHP 
                            if(
                                substr($_GET["app"],0,10) =='report_tax'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a style="cursor: pointer;" >
                                <i class="fa fa-line-chart" aria-hidden="true"></i> ภาษี <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" >
                                <li>
                                    <a href="?app=report_tax_01" <?PHP if($_GET['app'] == "report_tax_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ภาษีซื้อ </a>
                                </li>
                                <li>
                                    <a href="?app=report_tax_02" <?PHP if($_GET['app'] == "report_tax_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ภาษีขาย </a>
                                </li>
                                <li>
                                    <a href="?app=report_tax_03" <?PHP if($_GET['app'] == "report_tax_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> มูลค่าฐานภาษี</a>
                                </li>
                                
                            </ul>
                        </li>

                        <li
                        <?PHP 
                            if(
                                substr($_GET["app"],0,12) =='report_stock'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a style="cursor: pointer;" >
                                <i class="fa fa-line-chart" aria-hidden="true"></i> สินค้าคงคลัง <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" >
                                <li>
                                    <a href="?app=report_stock_01" <?PHP if($_GET['app'] == "report_stock_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าและวัตถุดิบ </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_02" <?PHP if($_GET['app'] == "report_stock_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าคงเหลือ </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_03" <?PHP if($_GET['app'] == "report_stock_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สรุปยอดเคลื่อนไหวสินค้า </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_04" <?PHP if($_GET['app'] == "report_stock_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดสินค้า </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_05" <?PHP if($_GET['app'] == "report_stock_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานราคาขายสินค้า </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_06" <?PHP if($_GET['app'] == "report_stock_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานรายการประจำวัน </a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_07" <?PHP if($_GET['app'] == "report_stock_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> จุดสั่งซื้อ</a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_08" <?PHP if($_GET['app'] == "report_stock_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าที่ไม่เคลื่อนไหว</a>
                                </li>
                                <li>
                                    <a href="?app=report_stock_09" <?PHP if($_GET['app'] == "report_stock_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าไม่มีการขาย</a>
                                </li>  
                            </ul>
                        </li>

                        <li
                        <?PHP 
                            if(
                                substr($_GET["app"],0,14) =='report_account'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a style="cursor: pointer;" >
                                <i class="fa fa-line-chart" aria-hidden="true"></i> บัญชี <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" >
                                <li>
                                    <a href="?app=report_account_01" <?PHP if($_GET['app'] == "report_account_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ผังบัญชี </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_02" <?PHP if($_GET['app'] == "report_account_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ยอดเคลื่อนไหว </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_03" <?PHP if($_GET['app'] == "report_account_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> งบเเสดงสถานะการเงิน </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_04" <?PHP if($_GET['app'] == "report_account_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สมุดรายวัน </a>
                                </li> 
                                <li>
                                    <a href="?app=report_account_05" <?PHP if($_GET['app'] == "report_account_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> งบทดลอง </a>
                                </li> 
                                <li>
                                    <a href="?app=report_account_06" <?PHP if($_GET['app'] == "report_account_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> บัญชีแยกประเภท </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_07" <?PHP if($_GET['app'] == "report_account_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานเช็คจ่ายคงเหลือ </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_08" <?PHP if($_GET['app'] == "report_account_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานเช็ครับ </a>
                                </li>
                                <li>
                                    <a href="?app=report_account_09" <?PHP if($_GET['app'] == "report_account_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานยอดขาย </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>