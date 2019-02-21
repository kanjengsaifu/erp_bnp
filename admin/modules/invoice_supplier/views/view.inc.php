<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_code = $("#supplier_code").val();
        var keyword = $("#keyword").val();
        window.location = "index.php?app=invoice_supplier&date_start="+date_start+"&date_end="+date_end+"&supplier_code="+supplier_code+"&keyword="+keyword;
    }
</script>
 

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['purchase_order']['view']==1){ ?> 
        <a href="?app=purchase_order&action=detail&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-primary btn-menu ">ใบสั่งซื้อ (PO) </a> 
        <?PHP }?>
        <?php if($menu['invoice_supplier']['view']==1){ ?> 
        <a href="?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-primary btn-menu active">ใบรับวัตถุดิบ (Supplier Invoice)</a> 
        <?PHP }?> 
        <a href="#" class="btn btn-primary btn-menu ">จ่ายเงิน (Pay)</a> 
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
                        รายการใบกำกับภาษีรับเข้า / Invoice Supplier List
                    </div>
                    <div class="col-md-6"> 
                        <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=invoice_supplier&action=insert&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คัดกรองตาม วันที่รับใบกำกับภาษี</label>
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
                            <label>คัดกรองตาม ผู้ขาย </label>
                            <select id="supplier_code" name="supplier_code" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_code'] == $supplier_code){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คัดกรองตาม คำค้น <font color="#F00"><b>*</b></font></label>
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
                        <a href="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br> 

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example">
                            <thead>
                                <tr>
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="24">No.</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขรับสินค้า" width="84">Recieve Code</th>
                                    <th class="datatable-th" data-original-title="วันที่รับสินค้า" data-container="body" data-toggle="tooltip" data-placement="top" title="" width="84">Recieve Date</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบกำกับภาษี" width="90">Invoice Code</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ตามใบกำกับภาษี" width="80">Invoice Date</th>                                    
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เอกสารที่เกี่ยวข้อง" width="90">Purchase Order</th>
                                    <th class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ขาย" >Supplier</th>
                                    <!--
                                    <th width="150" >Recieve by</th>
                                    --> 
                                    
                                    <th width="100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $invoice_supplier_total_price =0;
                                $invoice_supplier_vat_price =0;
                                $invoice_supplier_net_price =0;
                                for($i=0; $i < count($invoice_suppliers); $i++){
                                    $invoice_supplier_total_price +=$invoice_suppliers[$i]['invoice_supplier_total_price'];
                                    $invoice_supplier_vat_price +=$invoice_suppliers[$i]['invoice_supplier_vat_price'];
                                    $invoice_supplier_net_price +=$invoice_suppliers[$i]['invoice_supplier_net_price'];
                                ?>
                                <tr class="odd gradeX">
                                    <td class=" text-center"><?php echo $i+1; ?></td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?></td>
                                    
                                    <td data-order="<?php echo  $timestamp = strtotime( $invoice_suppliers[$i]['invoice_supplier_date_recieve']) ?>" >
                                        <?php echo ( $invoice_suppliers[$i]['invoice_supplier_date_recieve']); ?>
                                    </td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code_receive']; ?></td>

                                    
                                    <td data-order="<?php echo  $timestamp = strtotime(  $invoice_suppliers[$i]['invoice_supplier_date']  ) ?>" >
                                        <?php echo (  $invoice_suppliers[$i]['invoice_supplier_date']  ); ?>
                                    </td>
                                    
                                    <td><?php 

                                        $purchase_orders = $invoice_supplier_model->getPurchaseOrderByInvoiceSupplierId($invoice_suppliers[$i]['invoice_supplier_code']);
                                        // echo '<pre>';
                                        // print_r ($purchase_orders);
                                        // echo '</pre>';
                                        for($j=0; $j < count($purchase_orders); $j++){ 
                                            ?>
                                            <a href="?app=purchase_order&action=detail&purchase_order_code=<?php echo $purchase_orders[$j]['purchase_order_code'];?>&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" target = "_blank" title="ดูรายละเอียดใบสั่งซื้อ">
                                            <?php echo $purchase_orders[$j]['purchase_order_code']; ?>
                                            </a><br>
                                            <?php
                                        } 
                                    
                                    ?>
                                    
                                    </td>
                                    <td><?php echo $invoice_suppliers[$i]['supplier_name']; ?> </td>
                                    <!--
                                    <td><?php echo $invoice_suppliers[$i]['employee_name']; ?></td>
                                    --> 

                                    <td>
                                    
                                        <?PHP if($menu['invoice_supplier']['view']==1){ ?>
                                        <a href="?app=invoice_supplier&action=detail&invoice_supplier_code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" style="color:#0045E6;">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>
                                        <?PHP } ?>
                                       
                                        
                                        <?PHP /*
                                        <a href="print.php?app=invoice_supplier&action=pdf&lan=th&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" target="_blank" title="พิมพ์เอกสารภาษาไทย"  style="color:orange;">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a> */
                                        ?>

                                        <a href="print.php?app=invoice_supplier&action=pdf&lan=en&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" target="_blank" title="พิมพ์ใบรับสินค้า">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a> 

                                         

 
                                        <?PHP if($menu['invoice_supplier']['edit']==1){ ?>
                                        <a href="?app=invoice_supplier&action=update&invoice_supplier_code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" style="color:orange;">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?PHP } ?>


                                        <?PHP if($menu['invoice_supplier']['delete']==1){ ?>
                                        <a href="?app=invoice_supplier&action=delete&invoice_supplier_code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" onclick="return confirm('You want to delete Invoice Supplier : <?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
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
