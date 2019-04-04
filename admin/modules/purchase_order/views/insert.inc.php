<script>
    var data_buffer = [];
    var index_buffer = [];

    function check_code(){
        var code = document.getElementById("purchase_order_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : PO1801010001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/purchase_order/controllers/getPurchaseOrderByCode.php", { code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("purchase_order_code").focus();
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
                //$("#purchase_order_date").val(data.date_now);
                $('.calendar').datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("purchase_order_date").focus();
            } else{
                $("#date_check").val("0");
                //generate_credit_date();
            }
        });
    }

    function check(){
        var supplier_code = document.getElementById("supplier_code").value;
        var purchase_order_code = document.getElementById("purchase_order_code").value;
        var purchase_order_date = document.getElementById("purchase_order_date").value;
        var purchase_order_credit_term = document.getElementById("purchase_order_credit_term").value;
        var employee_code = document.getElementById("employee_code").value;
        var date_check = document.getElementById("date_check").value;

        supplier_code = $.trim(supplier_code);
        purchase_order_code = $.trim(purchase_order_code);
        purchase_order_date = $.trim(purchase_order_date);
        purchase_order_credit_term = $.trim(purchase_order_credit_term);
        employee_code = $.trim(employee_code);

        if(date_check == "1"){
            alert("This "+purchase_order_date+" is locked in the system.");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(purchase_order_code == ""){
            alert("Please input purchase order code .");
            document.getElementById("purchase_order_code").focus();
            return false;
        }else if(supplier_code.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(purchase_order_date.length == 0){
            alert("Please input purchase order Date");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(employee_code.length == 0){
            alert("Please input employee");
            document.getElementById("employee_code").focus();
            return false;
        }else{
            return true;
        }
    }

    function get_supplier_detail(){
        var supplier_code = document.getElementById('supplier_select').value;
        document.getElementById('supplier_code').value = supplier_code;

        $.post("controllers/getSupplierByCode.php", { 'supplier_code': supplier_code}, function( data ) {
            document.getElementById('purchase_order_credit_term').value = data.credit_day;
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });

        $.post("modules/purchase_order/controllers/getProductBySupplierCode.php", { 'supplier_code': supplier_code }, function( data ) {
            product_data = data;
        });
    }

    function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
        calculateAll();
    }

    function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

    function show_data(id){
        var product_name = "";
        var data = product_data.filter(val => val['product_code'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_code[]"]').val( data[0]['product_code'] );
            $(id).closest('tr').children('td').children('span[name="product_name[]"]').html( data[0]['product_name'] );
            $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( data[0]['product_buyprice'] );
            update_sum(id);
        }
    }

    function update_sum(id){
        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( ).replace(',',''));

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0.0;
        }

        if(isNaN(sum)){
            sum = 0.0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();
    }
    
    function roundNumber(num, scale) {
        if(!("" + num).includes("e")) {
            return +(Math.round(num + "e+" + scale)  + "e-" + scale);
        } else {
            var arr = ("" + num).split("e");
            var sig = ""
            if(+arr[1] + scale > 0) {
            sig = "+";
            }
            return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
        }
    }

    function show_purchase_order(id){
        var supplier_code = document.getElementById('supplier_code').value;
        var val_pr = document.getElementsByName('purchase_request_list_code[]');
        var val_qty = document.getElementsByName('purchase_order_list_qty[]');

        var purchase_order_list_qty = [];
        for(var i = 0 ; i < val_qty.length ; i++){
            if(val_qty[i].value ==0){
                alert("!!!กรุณากรอกจำนวนมากกว่า 0 ");
                return;
            }else{
                purchase_order_list_qty.push(val_qty[i].value);
            } 
        }

        var purchase_request_list_code = [];
        for(var i = 0 ; i < val_pr.length ; i++){
            purchase_request_list_code.push(val_pr[i].value);
        }
        
        if(supplier_code != ""){
            $.post("modules/purchase_order/controllers/getPurchaseOrderListBySupplierCode.php", { 
                'supplier_code': supplier_code,
                'purchase_request_list_code': JSON.stringify(purchase_request_list_code) ,
                'search':'' 
            }).done(function( data ) {
                index_buffer = [];

                if(data.length){
                    data_buffer = data;
                    $('#data_show_list').html("ทั้งหมด : "+data.length+" รายการ");

                    var content = "";
                    for(var i = 0; i < data.length; i++){
                        var purchase_order_list_qty = parseFloat( data[i].purchase_order_list_qty );
                        var purchase_order_list_price = parseFloat( data[i].purchase_order_list_price );
                        var purchase_order_list_total = (data[i].purchase_order_list_qty * data[i].purchase_order_list_price);
                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input onclick="add_row_by_click(this,'+i+')" type="checkbox" name="p_code" value="'+data[i].product_code+'">'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                            '<br>Remark : '+
                                            data[i].purchase_order_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="qty">' + purchase_order_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + purchase_order_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                   
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="price">' + roundNumber(purchase_order_list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(purchase_order_list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                    
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="total">' + roundNumber(purchase_order_list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(purchase_order_list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly>'+
                                   
                                        '</td>'+
                                    '</tr>';

                    }

                    $('#bodyAdd').html(content);
                    $('.table-pop').show();
                    $('.lds-spinner').hide();
                    $('#modalAdd').modal('show');
                }else{
                    alert("ไม่มีรายการสินค้าที่สามารถเปิดใบสั่งซื้อได้");
                }
            });
        }else{
            alert("Please select supplier.");
            document.getElementById("supplier_code").focus();
        }
    } 

    function search_pop_like(id){
        $('.table-pop').hide();
        $('.lds-spinner').show();

        var supplier_code = document.getElementById('supplier_code').value;
        var val_pr = document.getElementsByName('purchase_request_list_code[]');
        var val_qty = document.getElementsByName('purchase_order_list_qty[]');
        
        var purchase_order_list_qty = [];
        for(var i = 0 ; i < val_qty.length ; i++){
            if(val_qty[i].value ==0){
                alert("!!!กรุณากรอกจำนวนมากกว่า 0 ");
                return;
            }else{
                purchase_order_list_qty.push(val_qty[i].value);
            } 
        }

        var purchase_request_list_code = [];
        for(var i = 0 ; i < val_pr.length ; i++){
            purchase_request_list_code.push(val_pr[i].value);
        }

        $.post("modules/purchase_order/controllers/getPurchaseOrderListBySupplierCode.php", { 
            'supplier_code': supplier_code,
            'purchase_request_list_code': JSON.stringify(purchase_request_list_code) ,
            'search':$(id).val() 
         }).done(function( data ) {
            data_buffer = data;
            index_buffer = [];

            if(data.length > 0){
                $('.table-pop').show();
                $('.lds-spinner').hide();
                $('#data_show_list').html("ทั้งหมด : "+data.length+" รายการ");
                var content = "";
                for(var i = 0; i < data.length ; i++){
                    var purchase_order_list_qty = parseFloat( data[i].purchase_order_list_qty );
                    var purchase_order_list_price = parseFloat( data[i].purchase_order_list_price );
                    var purchase_order_list_total = (data[i].purchase_order_list_qty * data[i].purchase_order_list_price);
                    content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input onclick="add_row_by_click(this,'+i+')" type="checkbox" name="p_code" value="'+data[i].product_code+'">'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].product_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].product_name+
                                        '<br>Remark : '+
                                        data[i].purchase_order_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + purchase_order_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + purchase_order_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + roundNumber(purchase_order_list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(purchase_order_list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                    
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + roundNumber(purchase_order_list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(purchase_order_list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly>'+
                                   
                                    '</td>'+
                                '</tr>';

                }
            }
            $('#bodyAdd').html(content);
        });
    }

    function calculate_list(id){
        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0.0;
        }

        if(isNaN(sum)){
            sum = 0.0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="qty"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="price"]').val( roundNumber(price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="total"]').val( roundNumber(sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }

    function add_row_by_click(id,i){
        var p_code = $(id).closest('tr').children('td').children('input[name="p_code"]');
        if($(p_code).prop('checked')){
            index_buffer.push(i);
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");   
        }else{
            index_buffer.splice(index_buffer.findIndex(e => e === i),1);
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");   
        }
    }

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_code');
        for(var j=0; j<(index_buffer.length); j++){
            var i = index_buffer[j];
            if(checkbox[i].checked){
                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }

                var purchase_request_list_code = 0;
                if(data_buffer[i].purchase_request_list_code !== undefined){
                    purchase_request_list_code = data_buffer[i].purchase_request_list_code;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td class="sorter">'+index+'.</td>'+
                        '<td>'+
                            '<input type="hidden" name="purchase_order_list_code[]" value="">'+ 
                            '<input type="hidden" name="product_code[]" value="'+data_buffer[i].product_code+'">'+
                            '<input type="hidden" name="stock_group_code[]" value="'+data_buffer[i].stock_group_code+'">'+
                            '<input type="hidden" name="purchase_request_list_code[]" value="'+purchase_request_list_code+'">'+     
                            '<span>'+data_buffer[i].product_code+'</span>'+
                        '</td>'+
                        '<td>'+
                            '<span>Product name : </span>'+
                            '<span>'+data_buffer[i].product_name+'</span><br>'+
                            '<span>Remark : </span>'+
                            '<input type="text" class="form-control" name="purchase_order_list_remark[]" value="'+data_buffer[i].purchase_order_list_remark+'">'+
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_qty[]" onchange="update_sum(this);" value="'+data_buffer[i].purchase_order_list_qty+'"></td>'+
                        '<td>'+
                            '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price[]" onchange="update_sum(this);" value="'+data_buffer[i].purchase_order_list_price+'">'+
                            '<input type="checkbox" name="save_product_price[]" value="'+ data_buffer[i].product_code +'"> บันทึกราคาซื้อ'+ 
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price_sum[]" onchange="update_sum(this);" value="'+(data_buffer[i].purchase_order_list_qty * data_buffer[i].purchase_order_list_price)+'"></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="product_detail_blank(this);">'+
                                '<i class="fa fa-file-text-o" aria-hidden="true"></i>'+
                            '</a> '+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );
            }
        }
        update_line();
        calculateAll();
    }

    function checkAll(id){
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
            var checkbox = document.getElementsByName('p_code');
            for(var i = 0 ; i < (checkbox.length); i++){
                if(checkbox[i].checked){ 
                    var checkVal = index_buffer.filter(function(ele){
                        return ele == i;
                    });
                    if(checkVal.length <=0 ){
                        index_buffer.push(i);
                    }
                }
            }
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");
        }else{
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
            index_buffer = [];
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");
        }
    }

    function calculateAll(){
        var val = document.getElementsByName('purchase_order_list_price_sum[]');
        var vat_type = parseInt(document.getElementById("purchase_order_vat_type").value);
        var vat = parseInt(document.getElementById("purchase_order_vat").value);
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        if(vat_type == 1){
            $('#purchase_order_net_price').val(roundNumber(total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_total_price').val(roundNumber(total - ((vat/(100.00 + vat) * total)),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_vat_price').val(roundNumber((vat/(100.00 + vat)) * total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
        }else if(vat_type == 2){
            $('#purchase_order_total_price').val(roundNumber(total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_vat_price').val(roundNumber((total * (vat/100.0)),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_net_price').val(roundNumber((total * (vat/100.0) + total),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
        }else{
            $('#purchase_order_total_price').val(roundNumber(total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_net_price').val(roundNumber(total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
            $('#purchase_order_vat_price').val(0);
        }
    }

    function product_detail_blank(id){
        var product_code = $(id).closest('tr').children('td').children('input[name="product_code[]"]').val();
        if(product_code == ''){
            alert('ไม่มีข้อมูลสินค้านี้');
            $(id).closest('tr').children('td').children('input[name="product_code[]"]').focus();
        }else{
            window.open("?app=product_detail&product_code="+product_code);
        }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มใบสั่งซื้อสินค้า / Add Purchase Order  
    </div>

    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=add" >
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                <p class="help-block">Example : A0001.</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier <font color="#F00"><b>*</b></font></label>
                                    <select id="supplier_select" name="supplier_select" class="form-control select" onchange="get_supplier_detail()" data-live-search="true" >
                                    <option value="">Select</option>
                                    <?php 
                                    for($i=0; $i<count($suppliers); $i++){
                                    ?>
                                    <option <?php if($suppliers[$i]['supplier_code'] == $supplier['supplier_code']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                <textarea id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                <p class="help-block">Example : IN.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสใบสั่งซื้อสินค้า / Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                <input id="purchase_order_code" name="purchase_order_code" class="form-control" onchange="check_code()" value="<?php echo $purchase_order_code; ?>" >
                                <p id="alert_code" class="help-block">Example : PO1801010001.</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้ออกใบสั่งซื้อ / Employee <font color="#F00"><b>*</b></font> </label>
                                <select id="employee_code" name="employee_code" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i=0; $i<count($users); $i++){
                                    ?>
                                    <option <?PHP if($login_user['user_code'] == $users[$i]['user_code']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_code'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>วันที่ออกใบสั่งซื้อสินค้า / Purchase Order Date</label>
                                <input type="text" id="purchase_order_date" name="purchase_order_date" value="<?PHP echo date("d")."-".date("m")."-".date("Y"); ?>" class="form-control calendar" onchange="check_date(this);" readonly>
                                <input id="date_check" type="hidden" value="">
                                <p class="help-block">Example : 31-01-2018</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>เครดิต (วัน) / Credit term (Day)</label>
                                <input type="text" id="purchase_order_credit_term" name="purchase_order_credit_term" class="form-control" value="<?PHP echo $supplier['credit_day'];?>">
                                <p class="help-block">Example : 10 </p>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>จัดส่งโดย / Delivery by</label>
                                <input type="text" id="purchase_order_delivery_by" name="purchase_order_delivery_by" value="<?PHP echo $supplier['purchase_order_delivery_by'] ?>" class="form-control">
                                <p class="help-block">Example : DHL </p>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>วันที่จัดส่ง / Delivery Date</label>
                                <input type="text" id="delivery_term" name="delivery_term" value="<? echo $purchase_order['delivery_term'];?>" class="form-control calendar" onchange="check_date(this);" readonly>
                                <p class="help-block">Example : 31-01-2018</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>หมายเหตุ / Remark</label>
                                <input type="text" id="purchase_order_remark" name="purchase_order_remark" value="<? echo $purchase_order['purchase_order_remark'];?>" class="form-control">
                                <p class="help-block">Example : -</p>
                            </div>
                        </div>            
                    </div>    
                </div>
            </div> 

            <div>Our reference :</div>
            <table width="100%" name="tb_list" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align:center;" width="60">ลำดับ </th>
                        <th style="text-align:center;" width="150">รหัสสินค้า </th>
                        <th style="text-align:center;">ชื่อสินค้า / หมายเหตุ</th>
                        <th style="text-align:center;" width="120">จำนวน</th>
                        <th style="text-align:center;" width="120">ราคาต่หน่วย </th>
                        <th style="text-align:center;" width="120">จำนวนเงิน </th>
                        <th width="48"></th>
                    </tr>
                </thead>
                <tbody class="sorted_table">
                    <?php 
                    $total = 0;
                    for($i=0; $i < count($purchase_order_lists); $i++){
                    ?>
                    <tr class="odd gradeX">
                        <td class="sorter" style="text-align:center;">
                            <?PHP echo ($i + 1); ?>.
                        </td>
                        <td>
                            <input type="hidden" name="purchase_order_list_code[]" value="">
                            <input type="hidden" name="product_code[]" value="<?PHP echo $purchase_order_lists[$i]['product_code'];?>">
                            <input type="hidden" name="stock_group_code[]" value="<?PHP echo $purchase_order_lists[$i]['stock_group_code'];?>">
                            <input type="hidden" name="purchase_request_list_code[]" value="<?PHP echo $purchase_order_lists[$i]['purchase_request_list_code'];?>">
                            <span><?PHP echo $purchase_order_lists[$i]['product_code'];?></span>
                        </td>
                        <td>
                            <span>Product name : </span>
                            <span><?PHP echo  $purchase_order_lists[$i]['product_name'];?></span><br>
                            <span>Remark.</span>
                            <input type="text" class="form-control" name="purchase_order_list_remark[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_remark']; ?>">
                        </td>
                        <td><input type="text" class="form-control" style="text-align:center;" autocomplete="off" onchange="update_sum(this);" name="purchase_order_list_qty[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_qty']; ?>"></td>
                        <td>
                            <input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2); ?>">
                            <input type="checkbox" name="save_product_price[]" value="<?php echo $purchase_order_lists[$i]['product_code']; ?>"/> บันทึกราคาซื้อ
                        </td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" readonly onchange="update_sum(this);" name="purchase_order_list_price_sum[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'],2); ?>"></td>
                        <td>
                            <a href="javascript:;" onclick="product_detail_blank(this);">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            </a> 
                            <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <?
                        $total += $purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'];
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="odd gradeX">
                        <td colspan="8" align="center">
                            <a href="javascript:;" onclick="show_purchase_order(this);" style="color:red;">
                                <i class="fa fa-plus" aria-hidden="true"></i> 
                                <span>เพิ่มสินค้า / Add product</span>
                            </a>

                            <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">เลือกรายการสินค้า / Choose product</h4>
                                            <div class="col-lg-8">
                                                <div id="data_show_list" class="form-control alert-box alert-info" style="text-align: left;" role="alert">
                                                </div>
                                            </div>
                                            <div class="col-md-4 pull-right" >
                                                <input type="text" class="form-control pull-right" name="search_pop" onchange="search_pop_like(this)" placeholder="Search"/>
                                            </div>
                                        </div>

                                        <div class="modal-body modal-body-m">
                                            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                                            <br>
                                            <table width="100%" class="table table-striped table-bordered table-hover table-pop" >
                                                <thead>
                                                    <tr>
                                                        <th width="24"><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)"></th>
                                                        <th style="text-align:center;">รหัสสินค้า  </th>
                                                        <th style="text-align:center;">ชื่อสินค้า  </th>
                                                        <th style="text-align:center;" width="150">จำนวน  </th>
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย  </th>
                                                        <th style="text-align:center;" width="150">จำนวนเงิน  </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyAdd">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="col-lg-8">
                                                <div id="data_show_list_choose" class="form-control alert-box alert-success text-left" role="alert">
                                                    เลือก 0 รายการ
                                                </div>
                                            </div>
                                            <div class="col-lg-4" align="right">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Product</button>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="3" rowspan="3"></td>
                        <td colspan="2" align="left" style="vertical-align: middle;"><span>ราคารวมทั้งสิ้น / Sub total</span></td>
                        <td>
                        <?PHP
                            if($supplier['vat_type'] == 1){
                                $total_val = $total - (($supplier['vat']/( 100 + $supplier['vat'] )) * $total);
                            } else if($supplier['vat_type'] == 2){
                                $total_val = $total;
                            } else {
                                $total_val = $total;
                            }
                        ?>
                            <input type="text" class="form-control" style="text-align: right;" id="purchase_order_total_price" name="purchase_order_total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="2" align="left" style="vertical-align: middle;">
                            <table>
                                <tr>
                                    <td><span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span></td>
                                    <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                        <input type="hidden" id="purchase_order_vat_type" name="purchase_order_vat_type" value="<?php echo $supplier['vat_type'];?>">
                                        <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat" name="purchase_order_vat" value="<?php echo $supplier['vat'];?>" onchange="calculateAll();">
                                    </td>
                                    <td width="16">%</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <?PHP 
                            if($supplier['vat_type'] == 1){
                                $vat_val = ($supplier['vat']/( 100 + $supplier['vat'] )) * $total;
                            } else if($supplier['vat_type'] == 2){
                                $vat_val = ($supplier['vat']/100) * $total;
                            } else {
                                $vat_val = 0.0;
                            }
                            ?>
                            <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat_price"  name="purchase_order_vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="2" align="left" style="vertical-align: middle;"><span>จำนวนเงินรวมทั้งสิ้น / Net Total</span></td>
                        <td>
                            <?PHP 
                            if($supplier['vat_type'] == 1){
                                $net_val =  $total;
                            } else if($supplier['vat_type'] == 2){
                                $net_val = ($supplier['vat']/100) * $total + $total;
                            } else {
                                $net_val = $total;
                            }
                            ?>
                            <input type="text" class="form-control" style="text-align: right;" id="purchase_order_net_price" name="purchase_order_net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>   
        
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script> 
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>