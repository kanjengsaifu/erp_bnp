<script>
    function check(){
        var stock_type_code = document.getElementById("stock_type_code").value;
        var stock_group_name = document.getElementById("stock_group_name").value;
        var admin_code = document.getElementById("admin_code").value;

        stock_type_code = $.trim(stock_type_code);
        stock_group_name = $.trim(stock_group_name);
        
        if(stock_group_name.length == 0){
            alert("Please input stock group name");
            document.getElementById("stock_group_name").focus();
            return false;
        }else if(stock_type_code.length == 0){
            alert("Please select stock type");
            document.getElementById("stock_type_code").focus();
            return false;
        }else if(admin_code.length == 0){
            alert("Please select Warehouse administrator");
            document.getElementById("admin_code").focus();
            return false;
        }else{
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1>ระบบจัดการคลังสินค้า / Stock</h1>
        <h4 class="page-sub-header">เพิ่ม ลบ เเก้ไขข้อมูลคลังสินค้า</h4>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขข้อมูลคลังสินค้า / Edit Stock 
    </div>

    <div class="panel-body">
        <form id="form_target" role="form" method="post" onsubmit="return check();" action="?app=stock&action=edit" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>รหัสคลังสินค้า / Stock Group Code.</label>
                        <input id="stock_group_code" name="stock_group_code" class="form-control" value="<?PHP echo $stock_group['stock_group_code'];?>" autocomplete="off" readonly>
                        <p class="help-block">Example : 0000001</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>ชื่อคลังสินค้า / Stock Name. <font color="#F00"><b>*</b></font></label>
                        <input id="stock_group_name" name="stock_group_name" class="form-control" value="<?PHP echo $stock_group['stock_group_name'];?>" autocomplete="off"  maxlength="150">
                        <p class="help-block">Example : Main Stock</p>
                    </div>
                </div>
    
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>ประเภทคลังสินค้า / Stock Type </label>
                        <select id="stock_type_code" name="stock_type_code" class="form-control select" data-live-search="true" disabled>
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($stock_type); $i++){
                            ?>
                            <option <?PHP if($stock_type[$i]['stock_type_code'] == $stock_group['stock_type_code']){ ?> SELECTED <?PHP }?> value="<?php echo $stock_type[$i]['stock_type_code'] ?>"><?php echo $stock_type[$i]['stock_type_code'] ?> - <?php echo $stock_type[$i]['stock_type_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : คลังสินค้าบริษัท.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>ผู้ดูแล / Warehouse administrator <font color="#F00"><b>*</b></font> </label>
                        <select id="admin_code" name="admin_code" class="form-control select" data-live-search="true">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($user); $i++){
                            ?>
                            <option <?PHP if($user[$i]['user_code'] == $stock_group['admin_code']){ ?> SELECTED <?PHP }?> value="<?php echo $user[$i]['user_code'] ?>"><?php echo $user[$i]['user_code'] ?> - <?php echo $user[$i]['name'] ?> (<?php echo $user[$i]['user_position_name'] ?>)</option>
                            <?
                            }
                            ?>
                            <?php 
                            for($i=0; $i<count($contractor); $i++){
                            ?>
                            <option <?PHP if($contractor[$i]['contractor_code'] == $stock_group['admin_code']){ ?> SELECTED <?PHP }?> value="<?php echo $contractor[$i]['contractor_code'] ?>"><?php echo $contractor[$i]['contractor_code'] ?> - <?php echo $contractor[$i]['name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-4">
                    <div class="form-group">
                        <label>แจ้งเตือนทุกวันที่ / Daily alerts </label>
                        <input id="stock_group_day" name="stock_group_day" type="text" class="form-control integer" value="<?PHP if($stock_group['stock_group_day']) echo $stock_group['stock_group_day'];?>" maxlength="2">
                        <p class="help-block">Example : 25.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>รายละเอียดคลังสินค้า / Description </label>
                        <input id="stock_group_description" name="stock_group_description" type="text" class="form-control" value="<?PHP echo $stock_group['stock_group_description'];?>">
                        <p class="help-block">Example : Description...</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <input id="notification" name="notification" type="checkbox" value="1" <?PHP if($stock_group['notification']){ ?> checked <?PHP }?>>
                        <label>แจ้งเตือนเมื่อคลังสินค้าต่ำกว่าเกณฑ์ / Notification </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=stock" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function selectStockType(){
        var stock_type_code = document.getElementById("stock_type_code").value;

        if (stock_type_code == 'SGU001'){
            $.post("modules/stock/controllers/getUser.php", { }, function( data ) {
                $("#admin_code").html(data);
                $("#admin_code").selectpicker('refresh');
            });
        }else if(stock_type_code == 'SGU002'){
            $.post("modules/stock/controllers/getContractor.php", { }, function( data ) {
                $("#admin_code").html(data);
                $("#admin_code").selectpicker('refresh');
            });
        }else{
            $("#admin_code").html('<span class="filter-option pull-left">Select</span>');
            $("#admin_code").selectpicker('refresh');
        }
    }
</script>