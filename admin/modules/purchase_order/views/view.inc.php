
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();
        var view_type = $("#view_type").val();
        if( view_type == 'paper'){
            window.location = "index.php?app=purchase_order&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword+"&view_type=paper"; 
       
        }else{
            window.location = "index.php?app=purchase_order&action=view_list&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword+"&view_type=product";    
        
        }

    }

    function export_excel(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "print.php?app=purchase_order&action=excel&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
 


<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายใบสั่งซื้อ / Purchase Order List
                    </div>
                    <div class="col-md-6"> 
                        <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=purchase_order&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่ออกใบสั่งซื้อ</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ผู้ซื้อ </label>
                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                    
                    <!-- <div class="col-md-2">
                        <div class="form-group">
                            <label>แสดง </label>
                            <select id="view_type" name="view_type" class="form-control select" data-live-search="true"> 
                                <option <?PHP   if($view_type == 'paper'){?> selected <?PHP   }?> value="paper">ตามใบสั่งซื้อ</option> 
                                <option <?PHP   if($view_type == 'product'){?> selected <?PHP   }?> value="product">ตามรายการสั่งซื้อ</option> 
                            </select>
                            <p class="help-block">Example : ตามใบสั่งซื้อ.</p>
                        </div>
                    </div> -->

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

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example" >
                            <thead>
                                <tr>
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="32">No.</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบสั่งซื้อ" width="82" >PO No.</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ออกใบสั่งซื้อ" width="82">PO Date</th>                                    
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ขาย" >Supplier</th>
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ออกใบสั่งซื้อ" width="82">Request by</th> 
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสเอกสาร" width="102">Invoice Code</th>
                                    <!-- <th>สถานะอนุมัติ<br>Accept Status</th>
                                    <th>ผู้อนุมัติ<br>Accept by</th> -->
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" width="82">Remark</th>
                                    
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($purchase_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td class="text-center"><?php echo $i+1; ?></td>

                                    <td class="text-center"><?php echo $purchase_orders[$i]['purchase_order_code']; ?> <b 
                                    <?PHP 
                                        echo ' style="color:#449D44;"';?>
                                        
                                    ></b><?php if($purchase_orders[$i]['purchase_order_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?></td>

                                    <td data-order="<?php echo  $timestamp = strtotime($purchase_orders[$i]['purchase_order_date']) ?>" >
                                        <?php echo ($purchase_orders[$i]['purchase_order_date']); ?>
                                    </td>
                                    
                                    <td><?php echo $purchase_orders[$i]['supplier_name']; ?> </td>
                                    <td class="text-center"><?php echo $purchase_orders[$i]['employee_name']; ?></td>
                                
                                    <td>
                                    <?PHP 

                                        //  $invoice_supplier = $purchase_order_model->getPurchaseOrderInvoiceBy( $purchase_orders[$i]['purchase_order_code']);

                                        //  for($j = 0; $j<count($invoice_supplier); $j++){ 
                                            ?>
                                            <!-- <ul class="list-inline">
                                             <li class="list-inline-item">
                                                    <a href="index.php?app=invoice_supplier&action=detail&purchase_order_code=<?PHP echo $invoice_supplier[$j]['invoice_supplier_id']; ?>" target="_blank">
                                                        <?PHP
                                                        echo   $invoice_supplier[$j]['invoice_supplier_code_gen'];
                                                        ?>
                                                    </a>
                                                </li>
                                            </ul> -->
                                                <?PHP 
                                        //  }
                                         
                                    ?>
                                    </td>
                                    <td><?php echo $purchase_orders[$i]['purchase_order_remark']; ?></td>
                                    <td>
                                        <a href="?app=purchase_order&action=detail&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>

                                        <a href="print.php?app=purchase_order&action=excel&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>" target="_blank"  title="นำออกข้อมูล" style="color:green;">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        </a> 
                                        <a href="print.php?app=purchase_order&action=pdf&lan=th&purchase_order_code=<?PHP echo $purchase_orders[$i]['purchase_order_code'];?>" target="_blank" title="พิมพ์เอกสารภาษาไทย"  style="color:orange;">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                        <a href="print.php?app=purchase_order&action=pdf&lan=en&purchase_order_code=<?PHP echo $purchase_orders[$i]['purchase_order_code'];?>" target="_blank" title="พิมพ์เอกสารภาษาอังกฤษ">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>

                                        <?php if($menu['purchase_order']['edit']==1&&$purchase_orders[$i]['purchase_order_cancelled'] == 0){ ?>
                                        <a href="?app=purchase_order&action=update&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"  title="แก้ไขใบร้องขอ">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?PHP } ?>
                                        <?php if($menu['purchase_order']['delete']==1){ ?>
                                            <?php if($purchase_orders[$i]['purchase_order_cancelled'] == 0){ ?>
                                                <a href="?app=purchase_order&action=cancelled&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"  title="ยกเลิกใบร้องขอ" onclick="return confirm('You want to cancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:#F00;">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </a>
                                            <?PHP }else{ ?>

                                                <a href="?app=purchase_order&action=uncancelled&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>"  title="เรียกคืนใบร้องขอ" onclick="return confirm('You want to uncancelled purchase request : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" >
                                                <i class="fa fa-undo" aria-hidden="true"></i>
                                                </a>
                                            
                                                    <a href="?app=purchase_order&action=delete&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code'];?>" onclick="return confirm('You want to delete Purchase Order : <?php echo $purchase_orders[$i]['purchase_order_code']; ?>');" style="color:red;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                            
                                            <?PHP } ?>
                                        <?PHP } ?>
  
                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                 
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>