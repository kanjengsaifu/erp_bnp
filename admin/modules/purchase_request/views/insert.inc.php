<script>
	var options = {
        url: function(keyword) {
            return "modules/purchase_request/controllers/getProductBy.php?keyword="+keyword;
        },
        getValue: function(element) {
            return element.product_code ;
        },
        template: {
            type: "description",
            fields: {
                description: "product_name"
            }
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function(data) {
            data.keyword = $(".example-ajax-post:focus").val();
            return data;
        },
        requestDelay: 400
    };

    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_code:'<?php echo $stock_groups[$i]['stock_group_code'];?>',
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>' 
        },
    <?php }?>
    ];


    var supplier_data = [
    <?php for($i = 0 ; $i < count($suppliers) ; $i++ ){?>
        {
            supplier_code:'<?php echo $suppliers[$i]['supplier_code'];?>',
            supplier_name_th:'<?php echo $suppliers[$i]['supplier_name_th'];?>',
            supplier_name_en:'<?php echo $suppliers[$i]['supplier_name_en'];?>' 
        },
    <?php }?>
    ];

    function check_code(){
        var code = document.getElementById("purchase_request_code").value;

        code = $.trim(code);

        console.log(code);

        if(code.length == 0){
            $('#alert_code').html('Example : PR1801010001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/purchase_request/controllers/getPurchaseRequestByCode.php", { code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("purchase_request_code").focus();
                        $('#alert_code').html('This code : '+code+' is already in the system.');
                        $('#alert_code').addClass('alert-danger');
                        $('#alert_code').removeClass('alert-success');
                    }else{
                        $('#alert_code').html('Code : '+code+' can be used.');
                        $('#alert_code').removeClass('alert-danger');
                        $('#alert_code').addClass('alert-success');
                    }
            });
        }
    }

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#purchase_request_date").val(data.date_now);
                $(".calendar").datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("purchase_request_date").focus();
            } else{
                $("#date_check").val("0");
                //generate_credit_date();
            }
        });
    }
	
    function check(){
        var purchase_request_code = document.getElementById("purchase_request_code").value;
        var purchase_request_date = document.getElementById("purchase_request_date").value;
        var purchase_request_type = document.getElementById("purchase_request_type").value;
        var employee_code = document.getElementById("employee_code").value; 
        var purchase_check = document.getElementById("purchase_check").value;
        var date_check = document.getElementById("date_check").value;

        purchase_request_code = $.trim(purchase_request_code);
        purchase_request_type = $.trim(purchase_request_type);
        employee_code = $.trim(employee_code); 

        check_code();
        
        if($('#alert_code').hasClass('alert-danger')){
            document.getElementById("agent_code").focus();
            return false;
        }else if(date_check == "1"){
            alert("This "+purchase_request_date+" is locked in the system.");
            document.getElementById("purchase_request_date").focus();
            return false;
        }else if(purchase_request_code.length == 0){
            alert("Please input purchase request code");
            document.getElementById("purchase_request_code").focus();
            return false;
        }else if(purchase_request_type.length == 0){
            alert("Please input purchase request type");
            document.getElementById("purchase_request_type").focus();
            return false;
        }else if(employee_code.length == 0){
            alert("Please input employee");
            document.getElementById("employee_code").focus();
            return false;
        }else{
            return true;
        }
    }

    function show_data(id){
        var product_code = $(id).val();
        $.post("controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('span[name="product_description[]"]').html(data.product_description)
                $(id).closest('tr').children('td').children('span[name="product_name[]"]').html(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_code[]"]').val(data.product_code)
            }
        });
    }

    function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
    }

    function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

    function add_row(id){ 
        var index = 0;
        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
        }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
        }

        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td class="sorter" style="text-align:center;">'+index+'</td>'+
                '<td>'+
                    '<input type="hidden" class="form-control" name="purchase_request_list_code[]" value="">'+
                    '<input type="hidden" class="form-control" name="product_code[]" value="">'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code">'+
                    'Name : <span name="product_name[]"></span><br>'+
                    'Description : <span name="product_description[]"></span>'+
                '</td>'+ 
                '<td>'+
                    '<select name="stock_group_code[]" class="form-control select" data-live-search="true">'+  
                    '</select>'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" style="text-align:center;" autocomplete="off" name="request_list_qty[]" value="1">'+ 
                '</td>'+
                '<td><input type="text" class="form-control" name="request_list_remark[]"></td>'+
                '<td style="text-align:center;">'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="supplier_code[]"]').empty();
        var str = "<option value='0'>ไม่ระบุ</option>";
        var supplier_code = $("#supplier_code").val();
        $.each(supplier_data, function (index, value) { 
            if(value['supplier_code'] == supplier_code){
                str += "<option value='" + value['supplier_code'] + "' selected>" +  value['supplier_name_en'] + "</option>";  
                
            }else {
                str += "<option value='" + value['supplier_code'] + "'>" +  value['supplier_name_en'] + "</option>";  
                
            }
        });

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_code[]"]').html(str);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="supplier_code[]"]').selectpicker();
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_code[]"]').empty();
        var str = "";
        $.each(stock_group_data, function (index, value) { 
            str += "<option value='" + value['stock_group_code'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').html(str);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').selectpicker();
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Request Management</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มใบร้องขอสั่งซื้อสินค้า / Add Purchase Request  
    </div>

    <div class="panel-body">
        <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_request&action=add" >
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสใบร้องขอสั่งซื้อสินค้า <font color="#F00"><b>*</b></font></label>
                        <input id="purchase_request_code" name="purchase_request_code" class="form-control" onchange="check_code()" value="<?php echo $last_code;?>">
                        <p id="alert_code" class="help-block">Example : PR1801010001.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>วันที่ร้องขอ <font color="#F00"><b>*</b></font></label>
                        <input type="text" id="request_date" name="request_date" value="<?PHP echo date("d")."-".date("m")."-".date("Y"); ?>" class="form-control calendar" onchange="check_date(this);" readonly/>
                        <input id="date_check" type="hidden" value="">
                        <p class="help-block">01-03-2018</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ผู้ร้องขอ <font color="#F00"><b>*</b></font></label>
                        <select id="employee_code" name="employee_code" class="form-control select" data-live-search="true">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($users); $i++){
                            ?>
                            <option <?php if($users[$i]['user_code'] == $login_user['user_code']){?> selected <?php }?> value="<?php echo $users[$i]['user_code'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                    </div>
                </div> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ผู้ขาย </label>
                        <select id="supplier_code" name="supplier_code" data-live-search="true" class="form-control select" >
                            <option value="0">Select</option>
                            <?php 
                            for($i=0; $i<count($suppliers); $i++){
                            ?>
                            <option  value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด (Revel Soft co,ltd).</p>
                    </div>
                </div> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>วันที่แจ้งเตือน </label>
                        <input type="text" id="request_alert" name="request_alert" class="form-control calendar" readonly/>
                        <p class="help-block">01-03-2018</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="form-group">
                        <label>หมายเหตุ</label>
                        <input id="request_remark" name="request_remark" class="form-control">
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>

            <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th style="text-align:center;" width="60">ลำดับ </th>
                        <th style="text-align:center;">รายละเอียดสินค้า  </th> 
                        <th style="text-align:center;">คลังสินค้า </th>
                        <th style="text-align:center;max-width:80px;">จำนวน </th>
                        <th style="text-align:center;">หมายเหตุ </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="sorted_table"></tbody>
                <tfoot>
                    <tr class="odd gradeX">
                        <td colspan="8" align="center">
                            <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <span>เพิ่มสินค้า / Add product</span>
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="index.php?app=purchase_request" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>