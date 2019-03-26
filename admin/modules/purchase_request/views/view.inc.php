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
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายการใบร้องขอสั่งซื้อสินค้า / Purchase Request List
            </div>
            <div class="col-md-4">
            <?php if($menu['purchase_request']['add']){?> 
                <a class="btn btn-success" style="float:right;margin:0px 8px;" href="?app=purchase_request&action=insert"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มใบร้องขอสั่งซื้อ</a>
            <?php } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group" style="display: inline-block;">
            <label>วันที่ออกใบสั่งซื้อ</label>
            <br>
            <div class="form-group" style="display: inline-block; width: 150px;">
                <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>" class="form-control calendar" readonly>
            </div>
            -
            <div class="form-group" style="display: inline-block; width: 150px;">
                <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>" class="form-control calendar" readonly>
            </div>
        </div>
        <div class="form-group" style="display: inline-block; width: 300px;">
            <label>คำค้น <font color="#F00"><b>*</b></font></label>
            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
        </div>
        <div class="form-group" style="display: inline-block; width: 150px;">
            <button class="btn btn-primary" onclick="search();">Search</button>
            <a href="index.php?app=purchase_request" class="btn btn-default">Reset</a>
        </div>

        <br>

        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr>
                    <th width="24" class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ">ลำดับ<br>No.</th>
                    <th width="110"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบร้องขอ" >หมายเลขใบร้องขอ<br>PR No.</th>
                    <th width="80"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ออกใบร้องขอ" >วันที่ออก<br>PR Date</th>
                    <th width="160"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ร้องขอโดย" >ร้องขอโดย<br>Request by</th>
                    <th width="120" class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="สถานะอนุมัติ" >สถานะ<br>Status</th>
                    <th width="120" class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้อนุมัติ" >ผู้อนุมัติ<br>Approve by</th>
                    <th width="120"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสคำสั่งซื้อ" >รหัสคำสั่งซื้อ<br>PO Code</th>
                    <th width="160"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เลขที่ใบรับสินค้า" >เลขที่ใบรับสินค้า<br>Invoice Supplier Code</th>
                    <th width="100"class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" >หมายเหตุ<br>Remark</th>
                    <th width="10">
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($purchase_request); $i++){
                ?>
                <tr class="odd gradeX">
                    <td class="text-center"><?php echo $i+1; ?></td>
                    <td><?php echo $purchase_request[$i]['purchase_request_code']; ?> <?php if($purchase_request[$i]['revise_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_request[$i]['revise_no']; ?></font></b> <?PHP } ?> <?php if($purchase_request[$i]['request_cancelled']){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>
                    <td class="text-center" data-order="<?php echo $timestamp = strtotime($purchase_request[$i]['request_date']); ?>">
                        <?php if ($purchase_request[$i]['request_date'] != ''){ echo date("d-m-y", strtotime($purchase_request[$i]['request_date'])); } ?>
                    </td>
                    <td><?php echo $purchase_request[$i]['request_name']; ?></td>
                    <td class="text-center"><?php echo $purchase_request[$i]['approve_status']; ?></td>
                    <td><?php echo $purchase_request[$i]['accept_name']; ?></td>
                    <td class="text-center"><?php
                        $purchase_orders = $purchase_request_model->getPurchaseOrderByPurchaseRequestCode($purchase_request[$i]['purchase_request_code']);

                        for($j=0; $j < count($purchase_orders); $j++){ ?>
                            <a href="?app=purchase_order&action=detail&code=<?php echo $purchase_orders[$j]['purchase_order_code'];?>" target = "_blank" title="ดูรายละเอียดใบสั่งซื้อ">
                            <?php echo $purchase_orders[$j]['purchase_order_code']; ?>
                            </a><br>
                            <?php
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php 
                        $purchase_invoices = $purchase_request_model->getInvoiceSuppliertByPurchaseRequestCode($purchase_request[$i]['purchase_request_code']);

                        for($k=0; $k < count($purchase_invoices); $k++){ 
                        ?>
                            <a href="?app=invoice_supplier&action=detail&code=<?php echo $purchase_invoices[$k]['invoice_supplier_code'];?>" target = "_blank" title="ดูรายละเอียดใบรับสินค้า">
                                <?php echo $purchase_invoices[$k]['invoice_supplier_code_gen']; ?>
                            </a>
                            <br>
                        <?php
                        }
                        ?>
                    </td>
                    <td><?php echo $purchase_request[$i]['request_remark']; ?></td>
                    <td class="text-center">
                        <a href="?app=purchase_request&action=detail&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="ดูรายละเอียดใบร้องขอ">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a>
                    <?php if($purchase_request[$i]['approve_status'] == "Waiting"){ ?>
                        <?php if(!$purchase_request[$i]['request_cancelled']){ ?>
                            <?php if($menu['purchase_request']['cancel']){ ?> 
                            <a href="?app=purchase_request&action=cancelled&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                            <?php if($menu['purchase_request']['edit']){ ?> 
                            <a href="?app=purchase_request&action=revise&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to revise purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                <i class="fa fa-registered" aria-hidden="true"></i>
                            </a>
                            <a href="?app=purchase_request&action=update&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="แก้ไขใบร้องขอ">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a> 
                            <?PHP } ?>
                        <?php } else if(!$purchase_request[$i]['count_revise']) { ?>
                            <?php if($menu['purchase_request']['cancel']){ ?> 
                            <a href="?app=purchase_request&action=uncancelled&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" >
                                <i class="fa fa-undo" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                            <?php if($menu['purchase_request']['edit']){ ?> 
                            <a href="?app=purchase_request&action=revise&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="เขียนใบร้องขอใหม่" onclick="return confirm('You want to revise purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:#F00;">
                                <i class="fa fa-registered" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                            <?php if($menu['purchase_request']['delete']){ ?> 
                            <a href="?app=purchase_request&action=delete&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="ลบใบร้องขอ" onclick="return confirm('You want to delete purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" style="color:red;">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                            <?PHP } ?>
                        <?PHP } else { ?> 
                            <?php if($menu['purchase_request']['cancel']){ ?> 
                            <a href="?app=purchase_request&action=uncancelled&code=<?php echo $purchase_request[$i]['purchase_request_code'];?>" title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_request[$i]['purchase_request_code']; ?>');" >
                                <i class="fa fa-undo" aria-hidden="true"></i>
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