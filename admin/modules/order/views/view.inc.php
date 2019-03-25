<script>

    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#search_customer_id").val();
        var keyword = $("#keyword").val();
        var status = $("#status").val();
        var view_type = $("#view_type").val();
        if( view_type == 'paper'){
        window.location = "index.php?app=customer_purchase_order&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&status="+status+"&keyword="+keyword+"&view_type=paper";            
        }else{
        window.location = "index.php?app=customer_purchase_order&action=view_list&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&status="+status+"&keyword="+keyword+"&view_type=product";            
        }
    }

    function changeURL(id){
        var data = $(id).val();
        if(data == ""){
            $("#link").attr("href","?app=customer_purchase_order&action=insert");
        }else{
            $("#link").attr("href","?app=customer_purchase_order&action=insert&quotation_id="+data);
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Order Management</h1>
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
            เปิดใบยืมสินค้าของลูกค้าอ้างอิงตามบริษัท / Delivery Note Customer to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th  class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="64px" > No.</th>
                            <th  class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลูกค้า" > Customer</th>
                            <th  class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เปิดใบยืมสินค้าของลูกค้า"  width="180px" > Open Delivery Note Customer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($customer_orders); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $customer_orders[$i]['customer_name_en']; ?> </td>
                            <td>
                                <a href="?app=customer_purchase_order&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

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


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายการใบสั่งซื้อสินค้าของลูกค้า / Customer Order List
                    </div>
                    <div class="col-md-2" align="right">
                    <span><b>สร้างจากใบเสนอราคา : </b></span>
                    </div>
                    <div class="col-md-3">
                        
                        <select id="customer_id" name="customer_id" class="form-control select" data-live-search="true" onchange="changeURL(this);">
                            <option value="">None</option>
                            <?php 
                            for($i =  0 ; $i < count($quotations) ; $i++){
                            ?>
                            <option value="<?php echo $quotations[$i]['quotation_id'] ?>">
                                <?php echo $quotations[$i]['quotation_code'] ?>
                                <?php if($quotations[$i]['quotation_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $quotations[$i]['quotation_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($quotations[$i]['quotation_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                            </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-success " id="link" style="float:right;" href="?app=customer_purchase_order&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>วันที่รับสั่งซื้อสินค้าของลูกค้า</label>
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
                            <label>ผู้ซื้อ </label>
                            <select id="search_customer_id" name="search_customer_id" class="form-control select"   data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>แยกตามประเภท </label>
                            <select id="status" name="status" class="form-control select" data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <option <?php if($status == '1'){?> selected <?php }?> value="1">ยังไม่มีการสั่งสินค้า</option>
                                <option <?php if($status == '2'){?> selected <?php }?> value="2">สั่งสินค้าแล้ว</option>
                                <option <?php if($status == '3'){?> selected <?php }?> value="3">ส่งสินค้ายังไม่ครบ</option>
                                <option <?php if($status == '4'){?> selected <?php }?> value="4">ส่งสินค้าครบแล้ว</option>
                            </select>
                            <p class="help-block">Example : ยังไม่มีการสั่งสินค้า.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>แสดง </label>
                            <select id="view_type" name="view_type" class="form-control select" data-live-search="true"> 
                                <option <?php if($view_type == 'paper'){?> selected <?php }?> value="paper">ตามใบสั่งซื้อ</option> 
                                <option <?php if($view_type == 'product'){?> selected <?php }?> value="product">ตามรายการสั่งซื้อ</option> 
                            </select>
                            <p class="help-block">Example : ตามใบสั่งซื้อ.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                        <a href="index.php?app=customer_purchase_order" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="dataTables-example_filter" class="dataTables_filter">
                            
                        </div>
                    </div>
                </div>

         

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-view" >
                            <thead>
                                <tr>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="32">No.</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบสั่งซื้อ" width="104">PO No.</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่รับใบสั่งซื้อ" width="82">PO Date</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลูกค้า" >Customer</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="พนักงาน" width="100">Employee</th> 
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสเอกสาร" width="100">Invoice Code</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" width="100">Remark</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ใบกำกับภาษี" width="180">Supplier</th>
                                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ปุ่มควบคุม"  width="72"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=$page * $page_size ; $i < count($customer_purchase_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?></td>
                                    
                                    
                                    <td data-order="<?php echo  $timestamp = strtotime(  $customer_purchase_orders[$i]['customer_purchase_order_date']  ) ?>" >
                                        <?php echo (  $customer_purchase_orders[$i]['customer_purchase_order_date']  ); ?>
                                    </td>
                                    

                                    
                                    <td><?php echo $customer_purchase_orders[$i]['customer_name']; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['employee_name']; ?></td> 
                                    
                                    <td><?PHP   
                                    
                                    $invoice_customers = $invoice_customer_model -> getInvoiceCustomerByCustomerPurchaseId($customer_purchase_orders[$i]['customer_purchase_order_id']);
                                   
                                        // echo "<pre>";
                                        // print_r($invoice_customers);
                                        // echo"</pre>";

                                    for($j = 0; $j<count($invoice_customers); $j++){
                                        ?>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <a href="index.php?app=invoice_customer&action=detail&id=<?PHP echo $invoice_customers[$j]['invoice_customer_id']; ?>" target="_blank">
                                                <?PHP
                                                echo $invoice_customers[$j]['invoice_customer_code']; 
                                                ?>
                                            </a>
                                        </li>
                                    </ul>
                                        <?PHP
                                    }
                                    
                                    
                                    
                                    ?></td> 

                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_remark']; ?></td>
                                    <td>
                                        <?PHP 
                                            
                                            $suppliers = $customer_purchase_order_model->getSupplierByCustomerPurchaseOrder($customer_purchase_orders[$i]['customer_purchase_order_id']);
                                            // echo "<pre>";
                                            // echo $suppliers;
                                            // echo "</pre>";

                                            for($j=0;$j <count($suppliers);$j++){
                                                $name = $suppliers[$j]['supplier_name_en'];                                                     
                                                ?>
                                                    <a target="_BLANK" href="?app=purchase_order&customer_purchase_order_id=<?php echo  $suppliers[$j]['customer_purchase_order_id'];?>&supplier_domestic=<?php echo $suppliers[$j]['supplier_domestic'];?>&action=insert&supplier_id=<?php echo $suppliers[$j]['supplier_id'];?>">
                                                <?php 
                                                echo $name;
                                                ?>
                                                </a>,
                                        <?php
                                            }
                                            ?>
                                            
                                    
                                    </td>

                                    <td>
                                        <?
                                            if($customer_purchase_orders[$i]['customer_purchase_order_file'] != ""){
                                        ?>
                                            <a href="../upload/customer_purchase_order/<?php echo $customer_purchase_orders[$i]['customer_purchase_order_file'];?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a> 
                                        <?
                                            }
                                        ?>
                                        <a href="?app=customer_purchase_order&action=detail&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>

                                    <?php if($customer_purchase_orders[$i]['customer_purchase_order_status'] == "Waiting"){ ?>


                                        <?PHP if ( $license_sale_page == "Medium" || $license_sale_page == "High" ) { ?>
                                        <a href="?app=customer_purchase_order&action=update&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?PHP } ?>


                                        <?PHP if ( $license_sale_page == "High" ) { ?>
                                        <a href="?app=customer_purchase_order&action=delete&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>" onclick="return confirm('You want to delete Customer Purchase Order : <?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
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
             
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


