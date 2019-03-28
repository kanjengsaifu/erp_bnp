<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_code = $("#supplier_code").val();
        var keyword = $("#keyword").val();
        window.location = "index.php?app=purchase_order&date_start="+date_start+"&date_end="+date_end+"&supplier_code="+supplier_code+"&keyword="+keyword; 
    }

    function export_excel(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_code = $("#supplier_code").val();
        var keyword = $("#keyword").val();

        window.location = "print.php?app=purchase_order&action=excel&date_start="+date_start+"&date_end="+date_end+"&supplier_code="+supplier_code+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เปิดใบสั่งซื้ออ้างอิงตามบริษัท / Purchase order to do
    </div>
    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="64px"> No.</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ขาย"> Supplier</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เปิดใบสั่งซื้อ" width="180px"> Open purchase order</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($supplier_orders); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php  echo $supplier_orders[$i]['supplier_name_en']; ?></td>
                    <td>
                        <a href="?app=purchase_order&action=insert&supplier_code=<?php echo $supplier_orders[$i]['supplier_code'];?>"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
 
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                รายใบสั่งซื้อ / Purchase Order List
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="panel-search">
            <div class="form-group" style="display: inline-block;">
                <label>วันที่ออกใบสั่งซื้อ</label>
                <div>
                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>" class="form-control calendar" readonly style="display: inline-block; width: 150px;">
                    -
                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>" class="form-control calendar" readonly style="display: inline-block; width: 150px;">
                </div>
                <p class="help-block">01-01-2018 - 31-12-2018</p>
            </div>
            
            <div class="form-group" style="display: inline-block;">
                <label>ผู้ขาย </label>
                <select id="supplier_code" name="supplier_code" class="form-control select" data-live-search="true">
                    <option value="">ทั้งหมด</option>
                    <?php 
                    for($i =  0 ; $i < count($suppliers) ; $i++){
                    ?>
                    <option <?php if($suppliers[$i]['supplier_code'] == $supplier_code){?> selected <?php }?>
                        value="<?php echo $suppliers[$i]['supplier_code'] ?>">
                        <?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                    <?
                    }
                    ?>
                </select>
                <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
            </div>

            <div class="form-group" style="display: inline-block; width: 200px;">
                <label>คำค้น <font color="#F00"><b>*</b></font></label>
                <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>">
                <p class="help-block">Example : T001.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="export_excel();"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                <a href="index.php?app=purchase_order" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
            </div>
        </div>

        <br>

        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="ลำดับ" width="32">No.</th>
                    <th class="datatable-th text-center" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="หมายเลขใบสั่งซื้อ"
                        width="82">PO No.</th>
                    <th class="datatable-th text-center" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="วันที่ออกใบสั่งซื้อ"
                        width="82">PO Date</th>
                    <th class="datatable-th text-center" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="ผู้ขาย">Supplier</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="ผู้ออกใบสั่งซื้อ" width="82">
                        Request by</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="สถานะสั่งซื้อ" width="90">PO
                        Status</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="รหัสเอกสาร" width="102">
                        Invoice Code</th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="สถานะ" width="102">Status
                    </th>
                    <th class="datatable-th" data-container="body" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="หมายเหตุ" width="82">Remark
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($purchase_orders); $i++){
                ?>
                <tr class="odd gradeX">
                    <td class="text-center"><?php echo $i+1; ?></td>
                    <td><?php echo $purchase_orders[$i]['purchase_order_code']; ?>
                        <?php if($purchase_orders[$i]['revise_no'] > 0){ ?><b>
                            <font color="#F00">Revise
                                <?PHP echo $purchase_orders[$i]['revise_no']; ?>
                            </font>
                        </b>
                        <?PHP } ?>
                        <?php if($purchase_orders[$i]['order_cancelled']){ ?><b>
                            <font color="#F00">Cancelled</font>
                        </b>
                        <?PHP } ?>
                    </td>
                    <td class="text-center" data-order="<?php echo $timestamp = strtotime($purchase_orders[$i]['order_date']) ?>">
                        <?php if ($purchase_orders[$i]['order_date'] != ''){ echo date("d-m-Y", strtotime($purchase_orders[$i]['order_date'])); } ?>
                    </td>
                    <td><?php echo $purchase_orders[$i]['supplier_name']; ?> </td>
                    <td class="text-center"><?php echo $purchase_orders[$i]['employee_name']; ?></td>
                    <td class="text-center">
                        <?php if($purchase_orders[$i]['order_status'] =="New"){ ?>
                        <b class="font-weight-bold text-success text-center"><i class="fa fa-plus" aria-hidden="true"></i>
                            <?php echo $purchase_orders[$i]['order_status']; ?>
                        </b>
                        <?php
                        }else if($purchase_orders[$i]['order_status'] =="Request"){ ?>
                        <b class="font-weight-bold text-danger text-center"><i class="fa fa-registered" aria-hidden="true"></i>
                            <?php echo $purchase_orders[$i]['order_status']; ?>
                        </b>
                        <?php
                        }else if($purchase_orders[$i]['order_status'] =="Approved" && !$purchase_orders[$i]['order_cancelled']){ 
                        ?>
                            <b class="font-weight-bold text-info text-center"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <?php echo $purchase_orders[$i]['order_status']; ?>
                            </b>
                            <br>
                            <b>
                                <a class="text-success" href="?app=purchase_order&action=sending&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>&supplier_code=<?php echo $purchase_orders[$i]['supplier_code'];?>" title="Send">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </a>
                            </b>
                            <b>
                                <a class="text-danger mg-l-4" href="?app=purchase_order&action=update_sending&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>&supplier_code=<?php echo $purchase_orders[$i]['supplier_code'];?>" title="Confirm">
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>
                            </b>
                        <?php
                        }else if($purchase_orders[$i]['order_status'] =="Sending"){ 
                        ?>
                        <div>
                            <b class="font-weight-bold text-warning text-center"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                <?php echo $purchase_orders[$i]['order_status']; ?>
                            </b>
                        </div>
                        <div>
                            <b>
                                <a style="float: left;" class="text-success"
                                    href="?app=purchase_order&action=sending&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>&supplier_code=<?php echo $purchase_orders[$i]['supplier_code'];?>"
                                    title="Re-send">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </a>
                            </b>
                            <b>
                                <a href="?app=purchase_order&action=update_sending&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                    title="Confirm">
                                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                </a>
                            </b>
                            <b>
                                <a style="float: right;" class="text-danger"
                                    href="?app=purchase_order&action=cancel_sending&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                    title="Cancel">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </a>
                            </b>
                        </div>
                        <?php
                        }else if($purchase_orders[$i]['order_status'] =="Confirm"){ ?>
                        <b class="font-weight-bold text-secondary text-center"><i
                                class="fa fa-check-square-o" aria-hidden="true"></i>
                            <?php echo $purchase_orders[$i]['order_status']; ?>
                        </b>
                        <?php
                        }
                        ?>
                    </td>

                    <td>
                        <?PHP
                        $invoice_supplier = $purchase_order_model->getPurchaseOrderInvoiceBy( $purchase_orders[$i]['purchase_order_code']);

                        for($j = 0; $j<count($invoice_supplier); $j++){ 
                        ?>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href="index.php?app=invoice_supplier&action=detail&code=<?PHP echo $invoice_supplier[$j]['invoice_supplier_code']; ?>"
                                    target="_blank">
                                    <?PHP echo $invoice_supplier[$j]['invoice_supplier_code_gen']; ?>
                                </a>
                            </li>
                        </ul>
                        <?PHP 
                        }
                        ?>
                    </td>
                    
                    <td align="center"> 
                        <?php 
                            $invoice_supplier = $purchase_order_model->getPurchaseOrderInvoiceBy( $purchase_orders[$i]['purchase_order_code']);

                            for($j = 0; $j<count($invoice_supplier); $j++){ 
                                if ($invoice_supplier =! null ||$invoice_supplier=! "" ) {
                                
                                    if($invoice_supplier_model->checkPurchaseOrder($purchase_orders[$i]['purchase_order_code'])>0){ 
                                        echo " <b class='text-danger'>ยังไม่ครบ</b> ";
                                    }else{
                                        echo "<b  class='text-success'>ครบ</b>";    
                                    }
                                }
                            }
                        ?>
                    </td>   

                    <td><?php echo $purchase_orders[$i]['purchase_order_remark']; ?></td>
                    <td>
                        <a
                            href="?app=purchase_order&action=detail&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a>

                        <a href="print.php?app=purchase_order&action=excel&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                            target="_blank" title="นำออกข้อมูล" style="color:green;">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        </a>
                        <a href="print.php?app=purchase_order&action=pdf&lan=th&code=<?PHP echo $purchase_orders[$i]['purchase_order_code'];?>"
                            target="_blank" title="พิมพ์เอกสารภาษาไทย" style="color:orange;">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </a>
                        <a href="print.php?app=purchase_order&action=pdf&lan=en&code=<?PHP echo $purchase_orders[$i]['purchase_order_code'];?>"
                            target="_blank" title="พิมพ์เอกสารภาษาอังกฤษ">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </a>

                        <?php if($purchase_orders[$i]['order_status'] == "New" || $purchase_orders[$i]['order_status'] == "Approved"){ ?>

                        <?php if($purchase_orders[$i]['order_cancelled'] == 0){ ?>


                        <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                        <a href="?app=purchase_order&action=cancelled&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                            title="ยกเลิกใบร้องขอ"
                            onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');"
                            style="color:#F00;">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </a>
                        <a href="?app=purchase_order&action=rewrite&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                            title="เขียนใบร้องขอใหม่"
                            onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');"
                            style="color:#F00;">
                            <i class="fa fa-registered" aria-hidden="true"></i>
                        </a>
                        <a href="?app=purchase_order&action=update&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                            title="แก้ไขใบร้องขอ">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <?PHP } ?>

                        <?php } else if($purchase_orders[$i]['count_rewrite'] == 0) { ?>
                            <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                            <a href="?app=purchase_order&action=uncancelled&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                title="เรียกคืนใบร้องขอ"
                                onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');">
                                <i class="fa fa-undo" aria-hidden="true"></i>
                            </a>
                            <a href="?app=purchase_order&action=rewrite&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                title="เขียนใบร้องขอใหม่"
                                onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');"
                                style="color:#F00;">
                                <i class="fa fa-registered" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                            <?PHP if( $license_purchase_page == "High"){ ?>
                            <a href="?app=purchase_order&action=delete&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');"
                                style="color:red;" title="ลบ">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                        <?PHP }else{ ?>
                            <?PHP if( $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                            <a href="?app=purchase_order&action=uncancelled&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                title="เรียกคืนใบร้องขอ"
                                onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');">
                                <i class="fa fa-undo" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                            <?PHP if( $license_purchase_page == "High"){ ?>
                            <a href="?app=purchase_order&action=delete&code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"
                                onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');"
                                style="color:red;" title="ลบ">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                        <?PHP } ?>
                    <?php } ?>
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>