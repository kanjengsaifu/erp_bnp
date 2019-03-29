

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
    <div class="col-lg-12">
        <h1 class="page-header">Invoice Supplier Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายการใบกำกับภาษีรับเข้าตามผู้ขายในประเทศ
            </div>
            <div class="col-md-4" align="right">
                <a class="btn btn-danger " style="margin:4px;" href="?app=invoice_supplier&action=import-view" ><i class="fa fa-plus" aria-hidden="true"></i> Import</a>
                <a class="btn btn-success " style="margin:4px;" href="?app=invoice_supplier&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
        </div>
    </div>
    
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div style="font-size:18px;padding: 8px 0px;">แยกตามผู้ขาย</div>
                <table width="100%" class="table table-striped table-bordered table-hover" id="FIA">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Supplier</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($supplier_orders_in); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $supplier_orders_in[$i]['supplier_name_en']; ?></td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&supplier_code=<?php echo $supplier_orders_in[$i]['supplier_code'];?>">
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
            <div class="col-lg-6">
                <div style="font-size:18px;padding: 8px 0px;">แยกตามใบสั่งซื้อ</div>
                <table width="100%" class="table table-striped table-bordered table-hover" id="FIB">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Purchase Order</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($purchase_orders_in); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $purchase_orders_in[$i]['purchase_order_code']; ?> </td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&supplier_code=<?php echo $purchase_orders_in[$i]['supplier_code'];?>&purchase_order_code=<?php echo $purchase_orders_in[$i]['purchase_order_code'];?>">
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
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">รายการใบกำกับภาษีรับเข้า / Invoice Supplier List</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>คัดกรองตาม วันที่รับใบกำกับภาษี</label>
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start; ?>" class="form-control calendar" readonly/>
                        </div>
                        <div class="col-md-1" align="center">
                            -
                        </div>
                        <div class="col-md-5">
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end; ?>" class="form-control calendar" readonly/>
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
                        for($i=0; $i<count($suppliers); $i++){
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
                <a href="index.php?app=invoice_supplier" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
            </div>
        </div>
        <br> 

        <div class="row">
            <div class="col-sm-12">
                <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr>
                            <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="24">No.</th>
                            <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขรับสินค้า" width="84">Recieve Code</th>
                            <th class="datatable-th" data-original-title="วันที่รับสินค้า" data-container="body" data-toggle="tooltip" data-placement="top" title="" width="84">Recieve Date</th>
                            <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบกำกับภาษี" width="90">Invoice Code</th>
                            <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ตามใบกำกับภาษี" width="80">Invoice Date</th>                                    
                            <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เอกสารที่เกี่ยวข้อง" width="90">Purchase Order</th>
                            <th class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ขาย" >Supplier</th>
                            <th  class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จำนวนเงิน" >amount</th>
                            <th class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ภาษีซื้อ" >Tax</th>
                            <th  class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จำนวนเงินสุทธิ" >Net amount</th>
                            <th width="100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_price =0;
                        $vat_price =0;
                        $net_price =0;
                        for($i=0; $i < count($invoice_suppliers); $i++){
                            $total_price +=$invoice_suppliers[$i]['total_price'];
                            $vat_price +=$invoice_suppliers[$i]['vat_price'];
                            $net_price +=$invoice_suppliers[$i]['net_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td class=" text-center"><?php echo $i+1; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code_gen']; ?></td>
                            <td data-order="<?php echo  $timestamp = strtotime( $invoice_suppliers[$i]['recieve_date']) ?>" >
                                <?php echo ( $invoice_suppliers[$i]['recieve_date']); ?>
                            </td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?></td>
                            <td data-order="<?php echo  $timestamp = strtotime(  $invoice_suppliers[$i]['craete_date']  ) ?>" >
                                <?php echo (  $invoice_suppliers[$i]['craete_date']  ); ?>
                            </td>
                            <td>
                                <?php 
                                $purchase_orders = $invoice_supplier_model->getPurchaseOrderByInvoiceSupplierId($invoice_suppliers[$i]['invoice_supplier_code']);

                                for($j=0; $j < count($purchase_orders); $j++){ 
                                ?>
                                    <a href="?app=purchase_order&action=detail&code=<?php echo $purchase_orders[$j]['purchase_order_code'];?>" target = "_blank" title="ดูรายละเอียดใบสั่งซื้อ">
                                    <?php echo $purchase_orders[$j]['purchase_order_code']; ?>
                                    </a><br>
                                <?php
                                } 
                                ?>
                            </td>
                            <td><?php echo $invoice_suppliers[$i]['supplier_name']; ?> </td>
                            <td align="right"><?php echo number_format($invoice_suppliers[$i]['total_price'],2); ?></td>
                            <td align="right"><?php echo number_format($invoice_suppliers[$i]['vat_price'],2); ?></td>
                            <td align="right"><?php echo number_format($invoice_suppliers[$i]['net_price'],2); ?></td>
                            <td>
                                <a href="?app=invoice_supplier&action=detail&code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" style="color:#0045E6;">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>
                                <a href="print.php?app=invoice_supplier&action=pdf&lan=en&code=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" target="_blank" title="พิมพ์ใบรับสินค้า">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a> 

                                <?PHP if($license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                <a href="?app=invoice_supplier&action=update&sort=<?PHP echo $invoice_suppliers[$i]['supplier_domestic'];?>&code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" style="color:orange;">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <?PHP } ?>

                                <?PHP if( $license_purchase_page == "High"){ ?>
                                <a href="?app=invoice_supplier&action=delete&code=<?php echo $invoice_suppliers[$i]['invoice_supplier_code'];?>" onclick="return confirm('You want to delete Invoice Supplier : <?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                <?PHP } ?>
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="odd gradeX">
                            <td colspan ="7"><b>จำนวนเงินรวม</b></td>
                            <td align="right"><?php echo number_format($total_price,2); ?></td>
                            <td align="right"><?php echo number_format($vat_price,2); ?></td>
                            <td align="right"><?php echo number_format($net_price,2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>