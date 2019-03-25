<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['purchase_order']['view']==1){ ?> 
        <a href="?app=purchase_order&action=detail&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-primary btn-menu active">ใบสั่งซื้อ (PO) </a> 
        <?PHP }?>
        <?php if($menu['invoice_supplier']['view']==1){ ?> 
        <a href="?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-primary btn-menu">ใบรับวัตถุดิบ (Supplier Invoice)</a> 
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
                Purchase Order Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post"  action="index.php?app=purchase_order&action=approve&id=<?php echo $purchase_order_code;?>" >
                    <input type="hidden"  id="purchase_order_code" name="purchase_order_code" value="<?php echo $purchase_order_code; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12"> 
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_name_en'] ?>  </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_address_1']?><br><?php echo $purchase_order['supplier_address_2']?><br><?php echo $purchase_order['supplier_address_3']?><br></p>
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
                                        <label>รหัสใบสั่งซื้อวัตถุดิบ / Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อวัตถุดิบ / Purchase Order Date</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เครดิต (วัน) / Credit term (Day)</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_credit_term']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบสั่งซื้อ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['user_name'] ?> <?php echo $purchase_order['user_lastname'] ?> (<?php echo $purchase_order['user_position_name'] ?>)</p>
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่จัดส่ง / Delivery Date</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_delivery_term']?></p>
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
								<th style="text-align:center;width:32px;">Item</th>
                                <th style="text-align:center;">Material Code</th>
                                <th style="text-align:center;">Material Name / Description</th>
                                <th style="text-align:center;">Purchase detail</th>
                                <th style="text-align:center;">Order Qty</th>
                                <th style="text-align:center;">Recieve</th>
                                
                                <th style="text-align:center;">@</th>
                                <th style="text-align:center;">Amount</th>
                                 
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
                                    <?php echo $purchase_order_lists[$i]['material_code']?>
                                </td>
                                <td>
								Material name : <?php echo $purchase_order_lists[$i]['material_name']?> <br> 
						 
								</td>
                                
                                

                                <td><?PHP   
                                    //  echo  $purchase_order_lists[$i]['purchase_order_list_code'];
                                   
                                        // echo "<pre>";
                                        // print_r($invoice_material);
                                        // echo"</pre>";

                               
                                    
                                    ?></td> 


                                <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'],0)?></td>
                                <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty_recieve'],0)?></td>
                                
                                <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2)?></td>
                                <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price_sum'],2)?></td>
                                <!-- <td width="80"><a href="index.php?app=purchase_order&action=balance&id=<?PHP echo $purchase_order_code; ?>&purchase_order_list_code=<?PHP echo $purchase_order_lists[$i]['purchase_order_list_code']; ?>" class="btn btn-danger">Close</a></td> -->
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
                        <?PHP
                            if($purchase_order['purchase_order_vat_type'] == 1){
                                $total_val = $sub_total - (($purchase_order['purchase_order_vat']/( 100 + $purchase_order['purchase_order_vat'] )) * $sub_total);
                            } else if($purchase_order['purchase_order_vat_type'] == 2){
                                $total_val = $sub_total;
                            } else {
                                $total_val = $sub_total;
                            }
                        ?>
                            <tr class="odd gradeX">
							<td colspan="4" rowspan="3">
                                    
                                </td>
                                <td colspan="3" align="right">
                                    Sub Total 
                                </td>
                                <td align="right">
                                    <?PHP echo number_format($total_val,2) ;?>
                                    </td>								
                                <!-- <td width="80"></td> -->
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
                                <?PHP 
                                    if($purchase_order['purchase_order_vat_type'] == 1){
                                        $vat_val = ($purchase_order['purchase_order_vat']/( 100 + $purchase_order['purchase_order_vat'] )) * $sub_total;
                                    } else if($purchase_order['purchase_order_vat_type'] == 2){
                                        $vat_val = ($purchase_order['purchase_order_vat']/100) * $sub_total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <?PHP echo number_format($vat_val,2) ;?> 
                                </td>								
                                <!-- <td width="80"></td> -->
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
                                    <?PHP 
                                        if($purchase_order['purchase_order_vat_type'] == 1){
                                            $net_val =  $sub_total;
                                        } else if($purchase_order['purchase_order_vat_type'] == 2){
                                            $net_val = ($purchase_order['purchase_order_vat']/100) * $sub_total + $sub_total;
                                        } else {
                                            $net_val = $sub_total;
                                        }
                                    ?>
                                    <?PHP echo number_format($net_val,2) ;?>
                                </td>								
                                <!-- <td width="80"></td> -->
                                <!--
                                <td></td>
                                <td></td>
                                -->
                            </tr>
                        </tfoot>
                    </table>


                    <!-- /.row (nested) -->
                    <div class="row">
                    <?php if(($license_purchase_page == "High" || $license_manager_page == "High" ) && $purchase_order['purchase_order_status'] == 'Request'){ ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right">
                            <!-- <select id="purchase_order_accept_status" name="purchase_order_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Waitting"){?> selected <?php }?> >Waitting</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select> -->
                        </div>
                        <div class="col-lg-2" align="right">
                            <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    <?php } else { ?>
                        <div class="col-lg-offset-8 col-lg-2" align="right"> 
                            <!-- <select id="purchase_order_accept_status" name="purchase_order_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Waitting"){?> selected <?php }?> >Waitting</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($purchase_order['purchase_order_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>  -->
                        </div>
                        <div class="col-lg-2" align="right">
                            <!-- <button type="submit" class="btn btn-success">Save</button>
                            <a href="index.php?app=purchase_order&action=balance&id=<?PHP echo $purchase_order_code; ?>" class="btn btn-danger">Close</a> -->
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