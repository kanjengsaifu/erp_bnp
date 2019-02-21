

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management  </h1>
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
                Invoice Supplier Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post"  action="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>&action=approve&id=<?php echo $invoice_supplier_code;?>" >
                    <input type="hidden"  id="invoice_supplier_code" name="invoice_supplier_code" value="<?php echo $invoice_supplier_code; ?>" />
                    <input type="hidden"  id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $invoice_supplier['supplier_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_name_en'] ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $supplier['supplier_address_1'] ."\n". $invoice_supplier['supplier_address_2'] ."\n". $invoice_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_tax'];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_due_day'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_due'];?></p>
                                    </div>
                                </div> 
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับใบกำกับภาษี / Date recieve</label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date_recieve'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $invoice_supplier['user_name'];?> <?PHP echo $invoice_supplier['user_lastname'];?> (<?PHP echo $invoice_supplier['user_position_name'];?>)</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบรับสินค้า  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_stock'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รายการใบสั่งซื้อที่เกี่ยวข้อง </label>
                                        <table width="100%" class="table table-striped table-bordered table-hover" >
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">ลำดับ </th>
                                                    <th style="text-align:center;">หมายเลขใบสั่งซื้อ </th>
                                                    <th style="text-align:center;">สถานะ </th>
                                                   
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php 
                                                        for($i=0; $i < count($purchase_orders); $i++){
                                                        ?>
                                                        <tr class="odd gradeX">
                                                        <td align="center">
                                                            <?php echo $i+1; ?>.
                                                        </td>
                                                        <td align="center">
                                                            <a target="_blank" href="?app=purchase_order&action=detail&purchase_order_code=<?php echo $purchase_orders[$i]['purchase_order_code']; ?>"><?php echo $purchase_orders[$i]['purchase_order_code']; ?></a>
                                                        </td>
                                                        <td align="center"> 
                                                        <?php 
                                                            if($invoice_supplier_model->checkPurchaseOrder($purchase_orders[$i]['purchase_order_code'])>0){ 
                                                                echo " <b class='text-danger'>ยังไม่ครบ</b> ";
                                                            }else{
                                                                echo "<b  class='text-success'>ครบ</b>";    
                                                            }
                                                            ?>

                                                        </td>   
                                                        </tr>
                                                        <?php  } ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>    
                    <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ </th>
                                <th style="text-align:center;">รหัสสินค้า </th>
                                <th style="text-align:center;">รายละเอียดสินค้า </th> 
                                <th style="text-align:center;" width="150">จำนวน </th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>.
                                </td>
                                
                                <td>
                                    <?php echo $invoice_supplier_lists[$i]['material_code']; ?>
                                </td>

                                <td>
                                    <b><?php echo $invoice_supplier_lists[$i]['material_name']; ?></b><br> 
                                    <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                                </td>
 
                                <td align="right"><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                
                            </tr>
                            <?
                                $total += $invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'];
                            }
                            ?>
                        </tbody>
                    </table>


                    <!-- /.row (nested) -->
                    <div class="row">
                    <?php if(($license_purchase_page == "High" || $license_manager_page == "High" ) && $invoice_supplier['invoice_supplier_status'] == 'Request'){ ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right">
                            <!-- <select id="invoice_supplier_accept_status" name="invoice_supplier_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Waitting"){?> selected <?php }?> >Waitting</option>
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select> -->
                        </div>
                        <div class="col-lg-2" align="right">
                            <a href="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    <?php } else { ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right"> 
                            <!-- <select id="invoice_supplier_accept_status" name="invoice_supplier_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Waitting"){?> selected <?php }?> >Waitting</option>
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($invoice_supplier['invoice_supplier_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>  -->
                        </div>
                        <div class="col-lg-2" align="right">
                            <!-- <button type="submit" class="btn btn-success">Save</button>
                            <a href="index.php?app=invoice_supplier&action=balance&id=<?PHP echo $invoice_supplier_code; ?>" class="btn btn-danger">Close</a> -->
                            <a href="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-default">Back</a>
                        </div>
                    <?PHP } ?>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>