<script>
    function check_code(){
        var code = document.getElementById("stock_group_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : STG001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/stock/controllers/getStockGroupByCode.php", { code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("stock_group_code").focus();
                        $('#alert_code').html('This code : '+code+' is already in the system.');
                        $('#alert_code').addClass('alert-danger');
                        $('#alert_code').removeClass('alert-success');
                    }else{
                        $('#alert_code').html('This code : '+code+' can be used.');
                        $('#alert_code').removeClass('alert-danger');
                        $('#alert_code').addClass('alert-success');
                    }
            });
        }
    }

    function check(){
        var code = document.getElementById("stock_group_code").value;
        var stock_type_code = document.getElementById("stock_type_code").value;
        var stock_group_name = document.getElementById("stock_group_name").value;
        var admin_code = document.getElementById("admin_code").value;

        code = $.trim(code);
        stock_type_code = $.trim(stock_type_code);
        stock_group_name = $.trim(stock_group_name);
        
        if($('#alert_code').hasClass('alert-danger')){
            alert('This code : '+code+' is already in the system.');
            return false;
        }else if(stock_group_name.length == 0){
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
        เพิ่มคลังสินค้า / Add Stock Group
    </div>

    <div class="panel-body">
        <form id="form_target" role="form" method="post" onsubmit="return check();" action="?app=stock&action=add" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>รหัสคลังสินค้า / Stock Group Code. </label>
                        <input type="text" id="stock_group_code" name="stock_group_code" class="form-control integer" onchange="check_code()" autocomplete="off" maxlength="50">
                        <p id="alert_code" class="help-block">Example : 0000001</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>ชื่อคลังสินค้า / Stock Name. <font color="#F00"><b>*</b></font></label>
                        <input id="stock_group_name" name="stock_group_name" class="form-control" autocomplete="off" maxlength="150">
                        <p class="help-block">Example : Main Stock.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>ประเภทคลังสินค้า / Stock Type <font color="#F00"><b>*</b></font> </label>
                        <select id="stock_type_code" name="stock_type_code" class="form-control select" data-live-search="true" onchange="selectStockType()">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($stock_type); $i++){
                            ?>
                            <option value="<?php echo $stock_type[$i]['stock_type_code'] ?>" <?PHP if($stock_type[$i]['stock_type_code'] == $stock_type_code){ ?> SELECTED <?PHP }?> ><?php echo $stock_type[$i]['stock_type_name'] ?></option>
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
                        </select>
                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-4">
                    <div class="form-group">
                        <label>แจ้งเตือนทุกวันที่ / Daily alerts </label>
                        <input id="stock_group_day" name="stock_group_day" type="text" class="form-control integer" maxlength="2">
                        <p class="help-block">Example : 25.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>รายละเอียดคลังสินค้า / Description </label>
                        <input id="stock_group_description" name="stock_group_description" type="text" class="form-control">
                        <p class="help-block">Example : Description.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <input id="notification" name="notification" type="checkbox" value="1">
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
        }
    }
</script>