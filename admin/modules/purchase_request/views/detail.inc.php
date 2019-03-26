

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management [<?PHP echo $purchase_request['approve_status']; ?>]</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        รายละเอียดใบร้องขอสั่งซื้อสินค้า / Purchase Request Detail 
    </div>

    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=approve&code=<?php echo $purchase_request_code;?>" >
            <input type="hidden" id="purchase_request_code" name="purchase_request_code" value="<?php echo $purchase_request_code; ?>">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>หมายเลขสั่งซื้อสินค้า / PR Code <font color="#F00"><b>*</b></font> </label>
                        <p class="help-block">
                            <? echo $purchase_request['purchase_request_code'];?>
                            <?php if($purchase_request['revise_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_request['revise_no']; ?></font></b> <?PHP } ?> <?php if($purchase_request['request_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>ผู้ร้องขอ / Request by <font color="#F00"><b>*</b></font> </label>
                        <p class="help-block"><? echo $purchase_request['request_name'];?> (<? echo $purchase_request['user_position_name'];?>)</p>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>หมายเหคุ / Remark</label>
                        <p class="help-block"><? echo $purchase_request['request_remark'];?></p>
                    </div>
                </div>
            </div>

            <table width="100%" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th style="text-align:center;">ลำดับ <br>(No.)</th>
                        <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                        <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                        <th style="text-align:center;">จำนวน<br>(Qty)</th>
                        <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for($i=0; $i < count($purchase_request_lists); $i++){
                    ?>
                    <tr class="odd gradeX">
                        <td><?php echo $i+1; ?>.</td>
                        <td><?php echo $purchase_request_lists[$i]['product_code']; ?></td>
                        <td><?php echo $purchase_request_lists[$i]['product_name']; ?></td>
                        <td style="text-align:center;"><?php echo $purchase_request_lists[$i]['request_list_qty']; ?></td>
                        <td><?php echo $purchase_request_lists[$i]['request_list_remark']; ?></td>
                    </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-lg-offset-8 col-lg-2" align="right">
                <?php if($menu['purchase_request']['approve'] && !$purchase_request['request_cancelled']){ ?>
                    <select id="approve_status" name="approve_status" class="form-control" data-live-search="true" >
                        <option <?php if($purchase_request['approve_status'] == "Waiting"){?> selected <?php }?> >Waiting</option>
                        <option <?php if($purchase_request['approve_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                        <option <?php if($purchase_request['approve_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                    </select>
                <?php } ?>
                </div>
                <div class="col-lg-2" align="right">
                    <a href="index.php?app=purchase_request" class="btn btn-default">Back</a>

                    <?php if($menu['purchase_request']['approve'] && !$purchase_request['request_cancelled']){ ?>
                    <button type="submit" class="btn btn-success">Save</button>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>