<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management
    </div>
    <div class="col-lg-6" align="right">

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Purchase Order Detail
    </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="row">
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
                                <?php if($purchase_order['purchase_revise_no']){ ?><b>
                                        <font color="#F00">Revise
                                            <?PHP echo $purchase_order['purchase_revise_no']; ?>
                                        </font>
                                    </b>
                                    <?PHP } ?> <?php if($purchase_order['purchase_order_cancelled']){ ?><b>
                                        <font color="#F00">Cancelled</font>
                                    </b>
                                    <?PHP } ?>
                                
                                </label>
                                <p class="help-block"><?php echo $purchase_order['purchase_order_code']?></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Purchase Order Date</label>
                                <p class="help-block"><?php if ($purchase_order['purchase_order_date'] != ''){ echo date("d-m-Y", strtotime($purchase_order['purchase_order_date'])); } ?></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Credit term (Day)</label>
                                <p class="help-block"><?php echo $purchase_order['purchase_credit_term']?>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Employee <font color="#F00"><b>*</b></font> </label>
                                <p class="help-block"><?php echo $purchase_order['user_name'] ?>
                                    <?php echo $purchase_order['user_lastname'] ?> (<?php echo $purchase_order['user_position_name'] ?>)
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Delivery by</label>
                                <p class="help-block"><?php echo $purchase_order['purchase_delivery_by']?>
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
                        <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <th width="80"></th>
                        <?PHP } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sub_total = 0;
                    for($i=0; $i < count($purchase_order_lists); $i++){
                    ?>
                    <tr class="odd gradeX">
                        <td><?php echo $i + 1; ?>.</td>
                        <td><?php echo $purchase_order_lists[$i]['product_code']?></td>
                        <td>
                            Product name : <?php echo $purchase_order_lists[$i]['product_name']?> <br>
                            Remark : <?php echo $purchase_order_lists[$i]['purchase_order_list_remark']?> <br>
                            <br><label>Supplier Confirm</label><br>
                            Qty : <?php  echo $purchase_order_lists[$i]['supplier_qty']?>
                            <br>
                            Supplier Remark :
                            <?php echo $purchase_order_lists[$i]['supplier_remark']?> <br>
                        </td>
                        <td>
                            <?PHP   
                            $invoice_product = $purchase_order_model -> getPurchaseOrderInvoiceProductBy($purchase_order_lists[$i]['purchase_order_list_code']);
                            
                            for($j = 0; $j<count($invoice_product); $j++){                                 
                            ?>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="index.php?app=invoice_supplier&action=detail&code=<?PHP echo $invoice_product[$j]['invoice_supplier_code']; ?>"
                                        target="_blank">
                                        <?PHP
                                        echo $invoice_product[$j]['invoice_supplier_code']; 
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
                        <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'],0)?></td>
                        <td align="right"><?php echo number_format($purchase_order_lists[$i]['list_recieve_qty'],0)?></td>
                        <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2)?></td>
                        <td align="right"><?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price_sum'],2)?></td>
                        <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <td>
                            <a href="index.php?app=purchase_order&action=balance&code=<?PHP echo $purchase_order_code; ?>&list=<?PHP echo $purchase_order_lists[$i]['purchase_order_list_code']; ?>" class="btn btn-danger">Close</a>
                        </td>
                        <?PHP } ?>
                    </tr>
                    <?
                    $sub_total += $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="odd gradeX">
                        <td colspan="4" rowspan="3"></td>
                        <td colspan="3" align="right">Sub Total</td>
                        <td align="right"><?php echo number_format($sub_total,2);?></td>
                        <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <td></td>
                        <?PHP } ?>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="3" align="right">Vat</td>
                        <td align="right"><?php echo number_format($sub_total * $vat/100,2);?></td>
                        <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <td></td>
                        <?PHP } ?>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="3" align="right">Net Total</td>
                        <td align="right"><?php echo number_format($sub_total+($sub_total * $vat/100),2);?></td>
                        <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <td></td>
                        <?PHP } ?>
                    </tr>
                </tfoot>
            </table>

            <form role="form" method="post" action="index.php?app=purchase_order&action=approve&code=<?php echo $purchase_order_code;?>">
                <input type="hidden" id="purchase_order_code" name="purchase_order_code" value="<?php echo $purchase_order_code; ?>">
                <div class="row">
                    <div class="col-lg-offset-8 col-lg-2" align="right">
                    <?php if($menu['purchase_order']['purchase_approve'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <select id="purchase_approve_status" name="purchase_approve_status" class="form-control" data-live-search="true">
                            <option <?php if($purchase_order['purchase_approve_status'] == "Waitting"){?> selected <?php }?>>Waitting</option>
                            <option <?php if($purchase_order['purchase_approve_status'] == "Approve"){?> selected <?php }?>>Approve</option>
                            <option <?php if($purchase_order['purchase_approve_status'] == "Not Approve"){?> selected <?php }?>>Not Approve</option>
                        </select>
                    <?PHP } ?>
                    </div>
                    
                    <div class="col-lg-2" align="right">
                    <?php if($menu['purchase_request']['approve'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <button type="submit" class="btn btn-success">Save</button>
                    <?PHP } ?>
                    <?php if($menu['purchase_request']['edit'] && !$purchase_order['purchase_order_cancelled']){ ?>
                        <a href="index.php?app=purchase_order&action=balance&code=<?PHP echo $purchase_order_code; ?>" class="btn btn-danger">Close</a>
                    <?PHP } ?>
                        <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>