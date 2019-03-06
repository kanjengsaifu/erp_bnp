<script> 
    var options = {
        url: function(keyword) {
            return "controllers/getMaterialByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.material_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "material_name"
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
            data.keyword = $(".example-ajax-post").val();
            return data;
        },

        requestDelay: 400
    }; 

    var data_buffer = [];
 
    function check(){ 
  
        var supplier_code = document.getElementById("supplier_code").value; 
        var invoice_supplier_date = document.getElementById("invoice_supplier_date").value; 
        var invoice_supplier_code_receive = document.getElementById("invoice_supplier_code_receive").value; 

        supplier_code = $.trim(supplier_code);
        invoice_supplier_date = $.trim(invoice_supplier_date);
        invoice_supplier_code_receive = $.trim(invoice_supplier_code_receive); 

        if(supplier_code.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(invoice_supplier_date.length == 0){
            alert("Please input invoice date");
            document.getElementById("invoice_supplier_date").focus();
            return false; 
        }else if(invoice_supplier_code_receive.length == 0){
            alert("Please input invoice code");
            document.getElementById("invoice_supplier_code_receive").focus();
            return false; 
        }else{
            return true;
        }

    }

    function get_supplier_detail(){
        var supplier_code = document.getElementById('supplier_select').value;
        // var purchase_order_category = document.getElementById('purchase_order_category').value;
        // var user_code = document.getElementById('user_code').value;
        document.getElementById('supplier_code').value = supplier_code;
        $.post( "controllers/getSupplierByCode.php", { 'supplier_code': supplier_code}, function( data ) {
            // console.log(data);
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('invoice_supplier_name').value = data.supplier_name_en;
            document.getElementById('invoice_supplier_branch').value = data.supplier_branch;
            document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
            document.getElementById('invoice_supplier_tax').value = data.supplier_tax;
            // document.getElementById('purchase_order_credit_term').value = data.credit_day;
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });

        // $.post( "controllers/getPurchaseOrderCodeByCode.php", { 'supplier_code': supplier_code, 'user_code':user_code, 'purchase_order_category':purchase_order_category  }, function( data ) {
        //     document.getElementById('purchase_order_code').value = data;
        //     check_code();

        // });

        // $.post( "controllers/getMaterialBySupplierCode.php", { 'supplier_code': supplier_code }, function( data ) {
        //     material_data = data;
        // });
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
 

     function update_sum(id){

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price_sum[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

         

        
    } 
    
    function show_purchase_order(id){
        var supplier_code = document.getElementById('supplier_code').value;
        var val = document.getElementsByName('purchase_order_list_code[]');
        var purchase_order_list_code = [];
        console.log(supplier_code);
        
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_code.push(val[i].value);
        }
        console.log(purchase_order_list_code);
        if(supplier_code != ""){

            $.post( "controllers/getInvoiceSupplierListBySupplierCode.php", { 
                'supplier_code': supplier_code,
                'purchase_order_code':'<?PHP echo $_GET['purchase_order_code'];?>', 
                'purchase_order_list_code': JSON.stringify(purchase_order_list_code) }, function( data ) {
                console.log(data);
                if(data.length > 0){
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        var invoice_supplier_list_qty = parseFloat( data[i].invoice_supplier_list_qty );
                       
                        var invoice_supplier_list_price = parseFloat( data[i].invoice_supplier_list_price );
                        
                        var invoice_supplier_list_price_sum = invoice_supplier_list_price * invoice_supplier_list_qty;

                        content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input type="checkbox" name="p_id" value="'+data[i].material_code+'"   />'+     
                                    '</td>'+
                                    '<td>'+   
                                        data[i].material_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].material_name+
                                        '<br>Remark : '+
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + invoice_supplier_list_price_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
                                    '</td>'+
                                '</tr>';

                    }
                    
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }else{
                    add_row_new(id);
                }
                
            });
        }else{
            alert("Please select supplier.");
        }
        
    } 

    function search_pop_like(id){
        var supplier_code = document.getElementById('supplier_code').value;
        var val = document.getElementsByName('purchase_order_list_code[]');
        var purchase_order_list_code = [];
        
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_code.push(val[i].value);
        }

        $.post( "controllers/getInvoiceSupplierListBySupplierCode.php", { 
            'supplier_code': supplier_code, 
            'purchase_order_code':'<?PHP echo $_GET['purchase_order_code'];?>', 
            'purchase_order_list_code': JSON.stringify(purchase_order_list_code), search : $(id).val() }, function( data ) {
            var content = "";
            if(data.length > 0){
                data_buffer = data;
                
                for(var i = 0; i < data.length ; i++){

                    var invoice_supplier_list_qty = parseFloat( data[i].invoice_supplier_list_qty );
                    
                    var invoice_supplier_list_price = parseFloat( data[i].invoice_supplier_list_price ); 

                    var invoice_supplier_list_price_sum = invoice_supplier_list_price * invoice_supplier_list_qty;

                   content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input type="checkbox" name="p_id" value="'+data[i].material_code+'"   />'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].material_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].material_name+
                                        '<br>Remark : '+
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" />'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + invoice_supplier_list_price_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + invoice_supplier_list_price_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly />'+
                                    '</td>'+
                                '</tr>';


                }
            }
            $('#bodyAdd').html(content);
        });
    }
    
    function show_data(id){
        
        var material_code = $(id).val();  

        $.post( "controllers/getMaterialByCode.php", { 'material_code': $.trim(material_code)}, function( data ) {
            
            if(data != null){
                $(id).closest('tr').children('td').children('span[name="material_name[]"]').text(data.material_name)
                $(id).closest('tr').children('td').children('input[name="material_code[]"]').val(data.material_code)  
                $(id).closest('tr').children('td').children('input[name="save_material_price[]"]').val(data.material_code)  
                
                var supplier_code = $('#supplier_code').val(); 
                if(supplier_code!=''){
                    $.post( "controllers/getMaterialSupplierPriceByCode.php", { 'material_code': $.trim(data.material_code),'supplier_code': $.trim(supplier_code)}, function( data ) { 
                        // console.log(data);
                        if (data != null){
                            if( data.material_code == null ){
                                $(id).closest('tr').children('td').children('input[name="save_material_price[]"]').attr('checked',true) ; 
                                $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val('');
                            }else{
                                var material_price = parseFloat(data.material_supplier_buyprice);
                                $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( material_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
                            }
                        }else{
                            $(id).closest('tr').children('td').children('input[name="save_material_price[]"]').attr('checked',true) ;
                            $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val('');
                        }
                        update_sum(id);
                    }); 
                } 
            }
        });
    
    }

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_id');
        for(var i = 0 ; i < (checkbox.length); i++){
            if(checkbox[i].checked){
                 
                var qty =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
                var price =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
                var purchase_price = parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
                var sum =  parseFloat($(checkbox[i]).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));
             

                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }
                
                console.log(data_buffer[i]);
                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td class="sorter">'+
                        index+
                        '.</td>'+
                        '<td>'+
                            '<input type="hidden" name="invoice_supplier_list_code[]" value="0" />'+ 
                            '<input type="hidden" name="purchase_order_list_code[]" value="'+data_buffer[i].purchase_order_list_code+'" />'+ 
                            '<input type="hidden" name="material_code[]" value="'+data_buffer[i].material_code+'" />'+ 
                            '<input type="hidden" name="invoice_supplier_list_remark[]" value="'+data_buffer[i].invoice_supplier_list_remark+'" />'+ 
                            '<span>'+data_buffer[i].material_code+'</span>'+
                        '</td>'+
                        '<td>'+
                        '<span>Material name : </span>'+
                        '<span>'+data_buffer[i].material_name+'</span>'+ 
                                        '<br>Remark : '+
                                        data_buffer[i].invoice_supplier_list_remark+
                        '</td>'+ 
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_qty[]" onchange="update_sum(this);"  value="'+data_buffer[i].invoice_supplier_list_qty+'"/></td>'+
                        '<td >'+
                            '<input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_price[]" onchange="update_sum(this);" value="'+(parseFloat(data_buffer[i].invoice_supplier_list_price).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'"/>'+ 
                        '</td>'+
                        '<td align="right"><input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_price_sum[]" onchange="update_sum(this);" value="'+(data_buffer[i].invoice_supplier_list_price*data_buffer[i].invoice_supplier_list_qty).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'"/></td>'+
                        
                        '<td>'+ 
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(".example-ajax-post").easyAutocomplete(options); 

            }
            
        }
        calculateAll();
        update_line();
    }

    


    function add_row_new(id){
        $('#modalAdd').modal('hide');
        var index = 0;
        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
        }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
        }
        $(id).closest('table').children('tbody').append(
                '<tr class="odd gradeX">'+
                    '<td class="sorter">'+
                    index+
                    '.</td>'+
                    '<td>'+
                    '<input type="hidden" name="invoice_supplier_list_code[]" value="0" />'+ 
                    '<input type="hidden" name="purchase_order_list_code[]" value="0" />'+  
                        '<input class="example-ajax-post form-control" name="material_code[]" onchange="show_data(this);" placeholder="Material Code" />'+ 
                    '</td>'+
                    '<td>'+
                    '<span>Material name : </span>'+
                            '<span name="material_name[]" ></span> '+ 
                    '</td>'+ 
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_qty[]"  onchange="update_sum(this);" value="1"/></td>'+
                    '<td >'+
                        '<input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_price[]" onchange="update_sum(this);" value="0"/>'+  
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="invoice_supplier_list_price_sum[]" onchange="update_sum(this);" value="0"/></td>'+
                    
                    '<td>'+ 
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);
 
     
        
        update_line();
    }


    function checkAll(id)
    {
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
        }else{
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
        }
    }


    function calculateAll(){

        

    }

    function material_detail_blank(id){
        var material_code = $(id).closest('tr').children('td').children('input[name="material_code[]"]').val();
        if(material_code == ''){
            alert('ไม่มีข้อมูลวัตถุดิบนี้');
            $(id).closest('tr').children('td').children('input[name="material_code[]"]').focus();
        }else{
            window.open("?app=material_detail&material_code="+material_code);
        }
    }
    function update_invoice_supplier_due(id){
        var day = parseInt($('#invoice_supplier_due_day').val());
        var date = $('#invoice_supplier_date').val();

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#invoice_supplier_term').val(0);
            day = 0;
        }else if (date == ""){
            $('#invoice_supplier_due').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        } else{
            var date_arr = date.split('-'); 

            current_date = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
            tomorrow = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
        }

        tomorrow.setDate(current_date.getDate()+day);
        $('#invoice_supplier_due').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());

        console.log($('#invoice_supplier_due').val());
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
        $(id).closest('tr').children('td').children('input[name="price"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="total"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
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
                รายการใบกำกับภาษีรับเข้า
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>&action=edit&invoice_supplier_code=<? echo $invoice_supplier['invoice_supplier_code'];?>" >

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
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <input type="hidden" id="supplier_code_select" name="supplier_code_select" value="<?PHP echo $supplier_code; ?>"/>
                                            <select id="supplier_select" name="supplier_select" class="form-control " onchange="get_supplier_detail()" data-live-search="true" disabled>
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
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
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8" style="display:none">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_name'];?> " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4" style="display:none">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_branch" name="invoice_supplier_branch" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_branch'];?>" >
                                        <p class="help-block">Example : 0000 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_supplier_address" name="invoice_supplier_address" class="form-control" rows="5" ><?php echo $invoice_supplier['invoice_supplier_address'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_tax" name="invoice_supplier_tax" class="form-control" value="<?php echo $invoice_supplier['invoice_supplier_tax'];?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">  
                            <div class="row"> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับสินค้า / Date recieve</label>
                                        <input type="text" id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve" value="<? echo $invoice_supplier['invoice_supplier_date_recieve'];?>"  class="form-control calendar" onchange="" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date <font color="#F00"><b>*</b></font></label>
                                        <input type="text" id="invoice_supplier_date" name="invoice_supplier_date" value="<? echo $invoice_supplier['invoice_supplier_date'];?>" class="form-control calendar" readonly onchange="update_invoice_supplier_due(this)"/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code_receive" name="invoice_supplier_code_receive" class="form-control" value="<? echo $invoice_supplier['invoice_supplier_code_receive'];?>" >
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <input type="text" id="invoice_supplier_remark" name="invoice_supplier_remark" value="<? echo $invoice_supplier['invoice_supplier_remark'];?>"  class="form-control"  />
                                        <p class="help-block">-</p>
                                    </div>
                                </div>
                                <div class="col-lg-6" style="display:none">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        <input type="text" id="invoice_supplier_due_day" name="invoice_supplier_due_day"  class="form-control" value="<?PHP echo $supplier['credit_day']; ?>" /> 
                                        <p class="help-block">30</p>
                                    </div>
                                </div>  
                                <div class="col-lg-6" style="display:none">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_supplier_due" name="invoice_supplier_due"  class="form-control calendar" value="<? echo $invoice_supplier['invoice_supplier_due'];?>" readonly/> 
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div> 
                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_supplier_term" name="invoice_supplier_term"  class="form-control" value="<?PHP echo $supplier['condition_pay']; ?>" />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div> 
 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" name="tb_list" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th>
                                <th style="text-align:center;" width="150">รหัสวัตถุดิบ </th>
                                <th style="text-align:center;" >ชื่อวัตถุดิบ / หมายเหตุ  </th> 
                                <th style="text-align:center;" width="120">จำนวน  </th>
                                <th style="text-align:center;" width="120">ราคาต่อหน่วย </th>
                                <th style="text-align:center;" width="120">จำนวนเงิน </th>
                                <th width="24"></th>
                            </tr>
                        </thead>
                        <tbody  class="sorted_table">
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" name="invoice_supplier_list_code[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_code'];?>"/>
                                    <input type="hidden" name="purchase_order_list_code[]" value="<?PHP echo  $invoice_supplier_lists[$i]['purchase_order_list_code'];?>"/>
                                    
                                    <input type="hidden" name="material_code[]" value="<?PHP echo  $invoice_supplier_lists[$i]['material_code'];?>" /> 
                                    <input type="hidden" name="invoice_supplier_list_remark[]" value="<?PHP echo  $invoice_supplier_lists[$i]['purchase_order_code'];?>" /> 

                                    <span><?PHP echo  $invoice_supplier_lists[$i]['material_code'];?></span>
                                </td>
                                <td>
                                    <span>Material name : </span>
                                    <span><?PHP echo  $invoice_supplier_lists[$i]['material_name'];?></span>
                                    <br>Remark : 
                                        <?PHP echo  $invoice_supplier_lists[$i]['purchase_order_code'];?>
                                </td> 
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="invoice_supplier_list_qty[]" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?>" /></td>
                                <td>
                                    <input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="invoice_supplier_list_price[]" value="<?php echo number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?>" />
                                     
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" readonly onchange="update_sum(this);" name="invoice_supplier_list_price_sum[]" value="<?php echo number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?>" /></td>
                                
                                <td> 
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <? 
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td>   
                                   
                                </td>
                                <td colspan="6" align="center">
                                    <a href="javascript:;" onclick="show_purchase_order(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>ค้นหาวัตถุดิบ</span>
                                    </a>

                                    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">เลือกรายการวัตถุดิบ / Choose material</h4>
                                            </div>

                                            <div  class="modal-body">
                                            <div class="row">
                                                <div class="col-md-offset-8 col-md-4" align="right">
                                                    <input type="text" class="form-control" name="search_pop" onchange="search_pop_like(this)" placeholder="Search"/>
                                                </div>
                                            </div>
                                            <br>
                                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th width="24"><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" /></th>
                                                        <th style="text-align:center;">รหัสสินค้า </th>
                                                        <th style="text-align:center;">ชื่อสินค้า </th>
                                                        <th style="text-align:center;" width="150">จำนวน </th>
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย </th>
                                                        <th style="text-align:center;" width="150">จำนวนเงิน </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyAdd">

                                                </tbody>
                                            </table>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="add_row(this);">Add Material</button>
                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->


                                </td>
                            </tr> 
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier&purchase_order_code=<?PHP echo $_GET['purchase_order_code'];?>" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script> 

    $(".example-ajax-post").easyAutocomplete(options);
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>