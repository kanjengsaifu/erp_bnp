<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management <b style="color:red;">[
                <?PHP echo  $purchase_order['purchase_order_type'];?>]</b> </h1>
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
                Purchase Order Detail
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Type <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block">
                                            <?php echo $purchase_order['purchase_order_type']?>
                                            
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Supplier <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_name_en'] ?> </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block">
                                            <?php echo $purchase_order['supplier_address_1']?><br><?php echo $purchase_order['supplier_address_2']?><br><?php echo $purchase_order['supplier_address_3']?><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Code <font color="#F00"><b>*</b></font>
                                        <?php if($purchase_order['purchase_order_rewrite_no'] > 0){ ?><b>
                                                <font color="#F00">Revise
                                                    <?PHP echo $purchase_order['purchase_order_rewrite_no']; ?>
                                                </font>
                                            </b>
                                            <?PHP } ?> <?php if($purchase_order['purchase_order_cancelled'] == 1){ ?><b>
                                                <font color="#F00">Cancelled</font>
                                            </b>
                                            <?PHP } ?>
                                        
                                        </label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_code']?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <form method="post" name="from2">
                                        <div class="form-group">
                                            <label>Purchase Order Code Online <font color="#F00"><b>*</b></font></label>
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <input id="purchase_order_code_online"name="purchase_order_code_online" class="form-control"value=" <?php echo $purchase_order['purchase_order_code_online']?>">
                                                    <input id="purchase_check" type="hidden" value="" />
                                                </div>
                                                <div class="col-lg-4">
                                                    <button type="submit" class="btn btn-success" formaction="index.php?app=purchase_order&action=edit_code_online&id=<?php echo $purchase_order_id;?>">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Purchase Order Code Online <font color="#F00"><b>*</b></font></label>
                                            <p class="help-block">
                                                <?php echo $purchase_order['purchase_order_code_online']?></p>
                                        </div>
                                        <a href="?app=purchase_order&action=update&id=<?php echo $purchase_order_id; ?>"title="แก้ไขใบร้องขอ">แก้ไข</a>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_credit_term']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['user_name'] ?>
                                            <?php echo $purchase_order['user_lastname'] ?>(<?php echo $purchase_order['user_position_name'] ?>)
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_delivery_by']?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="text-align:center;width:32px;">Item</th>
                                <th style="text-align:center;">Product Code</th>
                                <th style="text-align:center;">Product Name / Description</th>
                                <th style="text-align:center;">Purchase detail</th>
                                <th style="text-align:center;">Order Qty</th>
                                <th style="text-align:center;">Recieve</th>

                                <th style="text-align:center;">@</th>
                                <th style="text-align:center;">Amount</th>
                                <th width="80"></th>
                                <!--
                                <th style="text-align:center;">Delivery Min</th>
                                <th style="text-align:center;">Delivery Max</th>
                                <th style="text-align:center;">Remark</th>
								-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
							$sub_total = 0;
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?php echo $i + 1; ?>.
                                </td>
                                <td>
                                    <?php echo $purchase_order_lists[$i]['product_code']?>
                                </td>
                                <td>
                                    Product name : <?php echo $purchase_order_lists[$i]['product_name']?> <br>
                                    Delivery :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']?> -
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_max']?> <br>
                                    Remark : <?php echo $purchase_order_lists[$i]['purchase_order_list_remark']?> <br>
                                    <br><label>Supplier Confirm</label><br>
                                    Qty : <?php  echo $purchase_order_lists[$i]['purchase_order_list_supplier_qty']?>
                                    <br>
                                    Delivery Date :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_min']?>
                                    -
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_max']?>
                                    <br>
                                    Supplier Remark :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_remark']?> <br>
                                </td>



                                <td>
                                    <?PHP   
                                    //  echo  $purchase_order_lists[$i]['purchase_order_list_id'];
                                    $invoice_product = $purchase_order_model -> getPurchaseOrderInvoiceProductBy($purchase_order_lists[$i]['purchase_order_list_id']);
                                   
                                        // echo "<pre>";
                                        // print_r($invoice_product);
                                        // echo"</pre>";

                                    for($j = 0; $j<count($invoice_product); $j++){ 
                                            # code...
                                        
                                        ?>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <a href="index.php?app=invoice_supplier&action=detail&id=<?PHP echo $invoice_product[$j]['invoice_supplier_id']; ?>"
                                                target="_blank">
                                                <?PHP
                                                echo $invoice_product[$j]['invoice_supplier_code_gen']; 
                                                ?>
                                            </a>
                                            จำนวน
                                            <?PHP
                                                echo $invoice_product[$j]['invoice_supplier_list_qty']; 
                                                ?>
                                            pcs
                                        </li>
                                    </ul>
                                    <?PHP 
                                    
                                }
                                    
                                    ?>
                                </td>


                                <td align="right">
                                    <?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'],0)?>
                                </td>
                                <td align="right">
                                    <?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty_recieve'],0)?>
                                </td>

                                <td align="right">
                                    <?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2)?>
                                </td>
                                <td align="right">
                                    <?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price_sum'],2)?>
                                </td>
                                <td width="80"><a
                                        href="index.php?app=purchase_order&action=balance&id=<?PHP echo $purchase_order_id; ?>&purchase_order_list_id=<?PHP echo $purchase_order_lists[$i]['purchase_order_list_id']; ?>"
                                        class="btn btn-danger">Close</a></td>
                                <!--
                                <td align="center"><?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']?></td>
                                <td align="center"><?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_max']?></td>
                                <td><?php echo $purchase_order_lists[$i]['purchase_order_list_remark']?></td>
								-->
                            </tr>
                            <?
							$sub_total += $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="4" rowspan="3">

                                </td>
                                <td colspan="3" align="right">
                                    Sub Total
                                </td>
                                <td align="right">
                                    <?php echo number_format($sub_total,2);?>
                                </td>
                                <td width="80"></td>
                                <!--
                                <td></td>
                                <td></td>
                                -->
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="right">
                                    Vat
                                </td>
                                <td align="right">
                                    <?php echo number_format($sub_total * $vat/100,2);?>
                                </td>
                                <td width="80"></td>
                                <!--
                                <td></td>
                                <td></td>
                                -->
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="right">
                                    Net Total
                                </td>
                                <td align="right">
                                    <?php echo number_format($sub_total+($sub_total * $vat/100),2);?>
                                </td>
                                <td width="80"></td>
                                <!--
                                <td></td>
                                <td></td>
                                -->
                            </tr>
                        </tfoot>
                    </table>

                    <form role="form" method="post"
                    action="index.php?app=purchase_order&action=approve&id=<?php echo $purchase_order_id;?>">
                    <input type="hidden" id="purchase_order_id" name="purchase_order_id"
                        value="<?php echo $purchase_order_id; ?>" />
                    <!-- /.row (nested) -->
                    <div class="row">
                        <?php if(($license_purchase_page == "High" || $license_manager_page == "High" ) && $purchase_order['purchase_order_status'] == 'Request'){ ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right">
                            <select id="purchase_order_accept_status" name="purchase_order_accept_status"
                                class="form-control" data-live-search="true">
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Waitting"){?>
                                    selected <?php }?>>Waitting</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Approve"){?>
                                    selected <?php }?>>Approve</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Not Approve"){?>
                                    selected <?php }?>>Not Approve</option>
                            </select>
                        </div>

                        <div class="col-lg-2" align="right">
                            <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                        
                        <?php } else { ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right">
                            <select id="purchase_order_accept_status" name="purchase_order_accept_status"
                                class="form-control" data-live-search="true">
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Waitting"){?>
                                    selected <?php }?>>Waitting</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Approve"){?>
                                    selected <?php }?>>Approve</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Not Approve"){?>
                                    selected <?php }?>>Not Approve</option>
                            </select>
                        </div>
                        <div class="col-lg-2" align="right">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="index.php?app=purchase_order&action=balance&id=<?PHP echo $purchase_order_id; ?>"
                                class="btn btn-danger">Close</a>
                            <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
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