<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();
        var list = $("#list").val();
        var status = $("#status").val();
        
        window.location = "index.php?app=purchase_request&list="+list+"&status="+status+"&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
    </div>
    <!-- /.col-lg-12 -->
</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    รายการใบร้องขอสั่งซื้อสินค้า / Purchase Request List
                                </div>
                                <div class="col-md-4">

                                <?PHP if($license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                    <a class="btn btn-danger " style="float:right;margin:0px 8px;" href="?app=purchase_request&action=insert&type=blanked" ><i class="fa fa-plus" aria-hidden="true"></i> Add Blanked Request</a>
                                <?PHP } ?>

                                <?PHP if($license_purchase_page == "Low" || $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                    <a class="btn btn-success " style="float:right;margin:0px 8px;" href="?app=purchase_request&action=insert&type=standard" ><i class="fa fa-plus" aria-hidden="true"></i> Add Standard Request</a>
                                <?PHP } ?>
                               
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>วันที่ออกใบร้องขอสั่งซื้อสินค้า</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                            </div>
                                            <div class="col-md-1" align="center">
                                                -
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                            </div>
                                        </div>
                                        <p class="help-block">01-01-2018 - 31-12-2018</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>แสดงผล </label>
                                            <select id="list" name="list" class="form-control select" data-live-search="true">
                                                <option value="">ทั้งหมด</option>
                                                <option <?php if($list == '1'){?> selected <?php }?> value="1">แสดงตามรายการ</option>
                                                <option <?php if($list == '2'){?> selected <?php }?> value="2">แสดงตามเอกสาร</option>
                                            </select>
                                            <p class="help-block">Example : แสดงตามรายการ</p>
                                     </div>
                                </div>

                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                        <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                        <p class="help-block">Example : T001.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                                    <a href="index.php?app=purchase_request" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                            </div>
                            <br>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>หมายเลขเอกสาร<br>PR No.</th>
                                        <th>วันที่ออกเอกสาร<br>PR Date</th>
                                        <th>รหัสสินค้า<br>Product Code</th>
                                        <th>ชื่อสินค้า<br>Product Name</th>
                                        <th>จำนวน<br>Accept by</th>
                                        <th>รหัสคำสั่งซื้อ<br>Purchase Order Code</th>
                                        <th>เลขที่ใบรับสินค้า<br>Invoice Supplier Code</th>
                                        <th>หมายเหตุ<br>Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($purchase_request); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>

                                        <td><?php echo $purchase_request[$i]['purchase_request_code']; ?> <?php if($purchase_request[$i]['purchase_request_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_request[$i]['purchase_request_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($purchase_request[$i]['purchase_request_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                                       

                                        <td data-order="<?php echo  $timestamp = strtotime(   $purchase_request[$i]['purchase_request_date'] )?>" >
                                            <?php echo (   $purchase_request[$i]['purchase_request_date'] ); ?>
                                        </td>
                                    

                                        <td><?php echo $purchase_request[$i]['product_code']; ?></td>
                                        <td><?php echo $purchase_request[$i]['product_name']; ?></td>
                                        <td><?php echo $purchase_request[$i]['purchase_request_list_qty']; ?></td>
                                        
                                        
                                        <td><?php
                                        $purchase_orders = $purchase_request_model->getPurchaseOrderByPurchaseRequestListId($purchase_request[$i]['purchase_request_list_code']);
                                        // echo '<pre>';
                                        // print_r ($purchase_orders);
                                        // echo '</pre>';
                                        for($j=0; $j < count($purchase_orders); $j++){ ?>
                                            <a href="?app=purchase_order&action=detail&id=<?php echo $purchase_orders[$j]['purchase_order_code'];?>" target = "_blank" title="ดูรายละเอียดใบสั่งซื้อ">
                                            <?php echo $purchase_orders[$j]['purchase_order_code']; ?>
                                            </a><br>
                                            <?php
                                        }
                                        ?></td>
                                        
                                       
                                        <td><?php 
                                            $purchase_invoices = $purchase_request_model->getInvoiceSuppliertByPurchaseRequestListId($purchase_request[$i]['purchase_request_list_code']);
                                            // echo '<pre>';
                                            // print_r ($purchase_invoices);
                                            // echo '</pre>';
                                            for($k=0; $k < count($purchase_invoices); $k++){ ?>
                                                <a href="?app=invoice_supplier&action=detail&id=<?php echo $purchase_invoices[$k]['invoice_supplier_code'];?>" target = "_blank" title="ดูรายละเอียดใบรับสินค้า">
                                                <?php echo $purchase_invoices[$k]['invoice_supplier_code_gen']; ?>
                                                </a><br>
                                                <?php
                                            }
                                            ?></td>

                                        <td><?php echo $purchase_request[$i]['purchase_request_remark']; ?></td>
                                        
                                        <td>
                                            <a href="?app=purchase_request&action=detail&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="ดูรายละเอียดใบร้องขอ">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a>

                                        <?php if($purchase_request[$i]['purchase_request_accept_status'] == "Waiting"){ ?>
                                            
                                            <?php if($purchase_request[$i]['purchase_request_cancelled'] == 0){ ?>


                                                <?PHP if(($license_purchase_page == "Low" && $admin_code == $purchase_request[$i]['employee_code']) || $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                                <a href="?app=purchase_request&action=cancelled&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=rewrite&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=update&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="แก้ไขใบร้องขอ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> 
                                                <?PHP } ?>


                                            <?php } else if($purchase_request[$i]['count_rewrite'] == 0) { ?>


                                                <?PHP if(($license_purchase_page == "Low" && $admin_code == $purchase_request[$i]['employee_code']) || $license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                                <a href="?app=purchase_request&action=uncancelled&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                                <a href="?app=purchase_request&action=rewrite&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to rewrite purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                                <?PHP if(($license_purchase_page == "Low" && $admin_code == $purchase_request[$i]['employee_code']) || $license_purchase_page == "High"){ ?>
                                                <a href="?app=purchase_request&action=delete&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                                <?PHP } ?>

                                            <?PHP } else { ?> 


                                                <?PHP if(($license_purchase_page == "Low" && $admin_code == $purchase_request[$i]['employee_code']) || $license_purchase_page == "Medium" ||  $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_request&action=uncancelled&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" >
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a> 
                                                <?PHP } ?>


                                                <?PHP if(($license_purchase_page == "Low" && $admin_code == $purchase_request[$i]['employee_code']) || $license_purchase_page == "High"){ ?> 
                                                <a href="?app=purchase_request&action=delete&id=<?php echo $purchase_request[$i]['purchase_request_code'];?>"  title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:red;">
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
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
