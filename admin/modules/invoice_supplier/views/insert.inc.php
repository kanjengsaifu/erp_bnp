<script>
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

    var options = {
        url: function(keyword) {
            return "controllers/getProductByKeyword.php?keyword="+keyword;
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

    var options_purchase = {
        url: function(keyword) {
            return "controllers/getPurchaseOrderByKeyword.php?&keyword="+keyword;
        },
        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true
            }
        },
        getValue: function(element) {
            return element.purchase_order_code ;
        },
        template: {
            type: "description",
            fields: {
                description: "supplier_name_en"
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

    var data_buffer = [];
    var index_buffer = [];

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#recieve_date").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("recieve_date").focus();
            } else{
                $("#date_check").val("0");
                get_supplier_detail();
            }
        });
    }

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getInvoiceSupplierByCodeGen.php", { 'invoice_supplier_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("invoice_supplier_code").focus();
                $("#invoice_check").val(data.invoice_supplier_code);
                
            } else{
                $("#invoice_check").val("");
            }
        });
    }

    function check(){
        var supplier_code = document.getElementById("supplier_code").value;
        var invoice_code_receive = document.getElementById("invoice_code_receive").value;
        var craete_date = document.getElementById("craete_date").value;
        var recieve_date = document.getElementById("recieve_date").value;
        var term = document.getElementById("term").value;
        var due_day = document.getElementById("due_day").value;
        var employee_code = document.getElementById("employee_code").value;
        var invoice_check = document.getElementById("invoice_check").value;
        var date_check = document.getElementById("date_check").value;
        
        supplier_code = $.trim(supplier_code);
        invoice_code_receive = $.trim(invoice_code_receive);
        craete_date = $.trim(craete_date);
        recieve_date = $.trim(recieve_date);
        term = $.trim(term);
        due_day = $.trim(due_day);
        employee_code = $.trim(employee_code);
        var val_qty = document.getElementsByName('list_qty[]');
        var net_price = 0.0;
        var total = 0.0;

        for(var i = 0 ; i < val_qty.length ; i++){
            if(val_qty[i].value ==0){
                alert("!!!กรุณากรอกจำนวนมากกว่า 0 ");
                return false;
            }
        }
         if(date_check == "1"){
            alert("This "+recieve_date+" is locked in the system.");
            document.getElementById("recieve_date").focus();
            return false;
        }else if(invoice_check != ""){
            alert("This "+invoice_supplier_code+" is already in the system.");
            document.getElementById("invoice_supplier_code").focus();
            return false;
        }else if(supplier_code.length == 0){
            alert("Please input supplier.");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(invoice_code_receive.length == 0){
            alert("Please input invoice code.");
            document.getElementById("invoice_code_receive").focus();
            return false;
        }else if(craete_date.length == 0){
            alert("Please input invoice date.");
            document.getElementById("craete_date").focus();
            return false;
        }else if(recieve_date.length == 0){
            alert("Please input invoice date recieve.");
            document.getElementById("recieve_date").focus();
            return false;
        }

        else{
            update_sum(null);
            $('select[name="stock_group_code[]"]').prop('disabled', false);
            return true;
        }
    }

    function get_supplier_detail(){
        var supplier_code = document.getElementById('supplier_code').value;
        var employee_code = document.getElementById("employee_code").value;
        var recieve_date = document.getElementById("recieve_date").value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_code': supplier_code }, function( data ) {
            if(data != null){
                document.getElementById('supplier_code').value = data.supplier_code;
                document.getElementById('supplier_name').value = data.supplier_name_en;
                document.getElementById('supplier_branch').value = data.supplier_branch;
                document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3 + ' ' +data.supplier_zipcode ;
                document.getElementById('supplier_tax').value = data.supplier_tax ;
                document.getElementById('due_date').value = data.credit_day ;
                document.getElementById('term').value = data.condition_pay ;
                document.getElementById('vat').value = data.vat ;
            }
        });

        $.post( "controllers/getInvoiceSupplierCodeByID.php", { 'supplier_code': supplier_code, 'employee_code':employee_code  }, function( data ) {
            document.getElementById('invoice_supplier_code').value = data;
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
       calculateAll();
        update_line();
     }

     function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }

     function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_code[]"]').val(data.product_code)
            }
        });
     }

     function update_sum(id){
        var val_qty = document.getElementsByName('list_qty[]');
        for(var i = 0 ; i < val_qty.length ; i++){ 
            id = val_qty[i];
            
            var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="list_qty[]"]').val(  ).replace(',',''));
            var price =  parseFloat($(id).closest('tr').children('td').children('input[name="list_price[]"]').val( ).replace(',',''));
            var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="list_total[]"]').val( ).replace(',',''));

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

            $(id).closest('tr').children('td').children('input[name="list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_cost[]"]').val( roundNumber(price,2).toFixed(2) );
            $(id).closest('tr').children('td').children('input[name="list_price[]"]').val( roundNumber(price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $(id).closest('tr').children('td').children('input[name="list_total[]"]').val( roundNumber(sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        }
       calculateAll();

        $( "#click_check" ).val(0);
        $( "#add_product_tag" ).click();
    }

    function add_row_by_click(id,i){
        var p_code = $(id).closest('tr').children('td').children('input[name="p_code"]');
        if($(p_code).prop('checked')==true){
            index_buffer.push(i);     
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");   
        }
        else{
            index_buffer.splice(index_buffer.findIndex(e => e === i),1);      
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");
        }
    }

    function show_purchase_order(id){
        $('#data_show_list').html("ทั้งหมด : 0 รายการ");
        var supplier_code = document.getElementById('supplier_code').value;
        var val = document.getElementsByName('purchase_order_list_code[]');
        var val_qty = document.getElementsByName('list_qty[]');

        var purchase_order_list_code = [];
        var list_qty = [];
        
        for(var i=0; i<val.length; i++){
            purchase_order_list_code.push(val[i].value);
            if(val_qty[i].value ==0){
                alert("!!!กรุณากรอกจำนวนมากกว่า 0 ");
                return;
            }else{
                list_qty.push(val_qty[i].value);
            }
        }

        if(supplier_code != ""){
            if($("#click_check").val()!=0){
                $(".table-pop").hide();
                $(".lds-spinner").show();
                $('#modalAdd').modal('show');
            }
            $('#data_show_list_choose').html("เลือก : 0 รายการ");
            $.post("modules/invoice_supplier/controllers/getInvoiceSupplierListBySupplierCode.php", {
                'supplier_code': supplier_code, 
                'purchase_order_list_code': JSON.stringify(purchase_order_list_code) ,
                'list_qty' : JSON.stringify(list_qty)
            }, function( data ) {
                $('#add_product_tag').show();
                $('#data_show_list').html("ทั้งหมด : "+data.length+" รายการ");
                if(data.length > 0){
                    data_buffer = data;
                    index_buffer=[];
                    var content = "";
                    for(var i = 0; i < data.length ; i++){
                        var list_qty = parseFloat( data[i].list_qty );
                        var list_price = parseFloat( data[i].purchase_order_list_price );
                        var list_total = list_price * list_qty;

                       content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input onclick="add_row_by_click(this,'+i+')" type="checkbox" name="p_code" value="'+data[i].product_code+'" onchange="show_recieve(this);">'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                            '<br>Remark : '+
                                            data[i].invoice_supplier_list_remark+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="qty">' + list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="price">' + roundNumber(list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                        '</td>'+
                                        '<td align="right">'+
                                            '<span name="total">' + roundNumber(list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                            '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly>'+
                                        '</td>'+
                                    '</tr>';

                    }
                    if($( "#click_check" ).val()!=0){
                        $( ".table-pop" ).show();
                        $('#bodyAdd').html(content);
                        $( ".lds-spinner" ).hide();
                    }
                    $( "#click_check" ).val(1);
                }else{
                    $('#add_product_tag').hide();
                }
            });
        }else{
            alert("Please select supplier.");
            document.getElementById("supplier_code").focus();
        }
    } 
    
    function search_pop_like(id){
        var supplier_code = document.getElementById('supplier_code').value;
        var val = document.getElementsByName('purchase_order_list_code[]');
        var val_qty = document.getElementsByName('list_qty[]');

        var purchase_order_list_code = [];
        var list_qty = [];
        $('#data_show_list').html("ทั้งหมด : 0 รายการ");
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_code.push(val[i].value);
            if(val_qty[i].value ==0){
                alert("!!!กรุณากรอกจำนวนมากกว่า 0 ");
                return;
            }else{
                list_qty.push(val_qty[i].value);
            }       
        }

        $.post( "controllers/getInvoiceSupplierListBySupplierID.php", {
            'supplier_code': supplier_code,
             'purchase_order_list_code': JSON.stringify(purchase_order_list_code) ,
             'list_qty' : JSON.stringify(list_qty),
              search : $(id).val() 
              }, function( data ) {
            var content = "";
            $('#data_show_list').html("ทั้งหมด : "+data.length+" รายการ");
            if(data.length > 0){
                data_buffer = data;
                index_buffer=[];
                for(var i = 0; i < data.length ; i++){
                    var list_qty = parseFloat( data[i].list_qty );
                    var list_price = parseFloat( data[i].purchase_order_list_price );
                    var list_total = list_price * list_qty;

                   content += '<tr class="odd gradeX">'+
                                    '<td>'+
                                        '<input onclick="add_row_by_click(this,'+i+')" type="checkbox" name="p_code" value="'+data[i].product_code+'" onchange="show_recieve(this);">'+     
                                    '</td>'+
                                    '<td>'+
                                        data[i].product_code+
                                    '</td>'+
                                    '<td>'+
                                        data[i].product_name+
                                        '<br>Remark : '+
                                        data[i].invoice_supplier_list_remark+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="qty">' + list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="qty" style="display:none;text-align:right;" type="text" class="form-control" value="' + list_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="price">' + roundNumber(list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="price" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);">'+
                                    '</td>'+
                                    '<td align="right">'+
                                        '<span name="total">' + roundNumber(list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + '</span>' +
                                        '<input name="total" style="display:none;text-align:right;" type="text" class="form-control" value="' + roundNumber(list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")  + '" onchange="calculate_list(this);" readonly>'+
                                    '</td>'+
                                '</tr>';

                }
            }
            $('#bodyAdd').html(content);
        });
    }

    function show_recieve(checkbox){ 
        if (checkbox.checked == true){
            $(checkbox).closest('tr').children('td').children('input[name="qty"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="qty"]').hide();

            $(checkbox).closest('tr').children('td').children('input[name="price"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="price"]').hide();

            $(checkbox).closest('tr').children('td').children('input[name="total"]').show();
            $(checkbox).closest('tr').children('td').children('span[name="total"]').hide();
        }else{
            $(checkbox).closest('tr').children('td').children('input[name="qty"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="qty"]').show();

            $(checkbox).closest('tr').children('td').children('input[name="price"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="price"]').show();

            $(checkbox).closest('tr').children('td').children('input[name="total"]').hide();
            $(checkbox).closest('tr').children('td').children('span[name="total"]').show();
        }
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

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_code');
        for(var j = 0 ; j < (index_buffer.length); j++){
            var i = index_buffer[j];
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
                
                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td class="sorter">'+
                        '</td>'+ 
                        '<td>'+
                            '<input type="hidden" name="invoice_supplier_list_code[]" value="0">'+ 
                            '<input type="hidden" name="purchase_order_list_code[]" value="'+ data_buffer[i].purchase_order_list_code +'">'+   
                            '<input type="hidden" name="invoice_supplier_list_fix_type[]" value="no-fix">'+     
                            '<input type="hidden" name="invoice_supplier_list_duty[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_freight_in[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_freight_in_total[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_import_duty[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_import_duty_total[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_cost[]" value="0">'+     
                            '<input type="hidden" name="invoice_supplier_list_cost_total[]" value="0">'+     
                            '<input type="hidden" name="product_code[]"  value="'+ data_buffer[i].product_code +'">'+
					        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+ data_buffer[i].product_code +'" />'+ 
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly>'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)">'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)">'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark" value="'+ data_buffer[i].invoice_supplier_list_remark +'"/>'+
                        '</td>'+
                        '<td>'+
                            '<select  name="stock_group_code[]" class="form-control select" data-live-search="true">'+  
                            '</select>'+ 
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_qty[]" onchange="update_sum(this);" value="'+ qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_price[]" onchange="update_sum(this);" value="'+ roundNumber(price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_total[]" onchange="update_sum(this);"  value="'+ roundNumber(sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") +'" readonly></td>'+
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

                $(".example-ajax-post").easyAutocomplete(options);

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_code[]"]').empty();
                var str = "";
                $.each(stock_group_data, function (index, value) {
                    if(value['stock_group_code'] == data_buffer[i].stock_group_code ){
                        str += "<option value='" + value['stock_group_code'] + "' SELECTED >" +  value['stock_group_name'] + "</option>";
                    }else{
                        str += "<option value='" + value['stock_group_code'] + "'>" +  value['stock_group_name'] + "</option>";
                    }
                    
                });
                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').html(str);

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').selectpicker();

            }
            
        }

       calculateAll();
        update_line();
        $( "#click_check" ).val(0);
        $( "#add_product_tag" ).click();
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
                '</td>'+ 
                '<td>'+
                    '<input type="hidden" name="invoice_supplier_list_code[]" value="0">'+ 
                    '<input type="hidden" name="purchase_order_list_code[]" value="0">'+ 
                    '<input type="hidden" name="invoice_supplier_list_fix_type[]" value="no-fix" />'+     
                    '<input type="hidden" name="invoice_supplier_list_duty[]" value="0">'+           
                    '<input type="hidden" name="invoice_supplier_list_freight_in[]" value="0">'+     
                    '<input type="hidden" name="invoice_supplier_list_freight_in_total[]" value="0">'+     
                    '<input type="hidden" name="invoice_supplier_list_import_duty[]" value="0">'+    
                    '<input type="hidden" name="invoice_supplier_list_import_duty_total[]" value="0">'+    
                    '<input type="hidden" name="invoice_supplier_list_cost[]" value="0">'+
                    '<input type="hidden" name="invoice_supplier_list_cost_total[]" value="0">'+
                    '<input type="hidden" name="old_cost[]" value="0">'+
                    '<input type="hidden" name="old_qty[]" value="0">'+
                    '<input type="hidden" name="product_code[]" value="0">'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="">'+ 
                    '<input type="text" class="form-control" name="product_name[]" readonly>'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]" placeholder="Product Name (Supplier)">'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]" placeholder="Product Detail (Supplier)">'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark">'+
                '</td>'+
                '<td>'+
                    '<select  name="stock_group_code[]" class="form-control select" data-live-search="true">'+  
                    '</select>'+ 
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_qty[]" value="0" onchange="update_sum(this);"></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_price[]" value="0" onchange="update_sum(this);"></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="list_total[]" value="0" onchange="update_sum(this);" readonly></td>'+
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

        $(".example-ajax-post").easyAutocomplete(options);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('div').children('div').children('select[name="stock_group_code[]"]').empty();
        var str = "";
        $.each(stock_group_data, function (index, value) { 
            str += "<option value='" + value['stock_group_code'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').html(str);
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_code[]"]').selectpicker();
        update_line();
    }

    function checkAll(id){
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="qty"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="price"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="total"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="qty"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="price"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="total"]').hide();
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
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="qty"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="price"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="total"]').hide();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="qty"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="price"]').show();
            $(id).closest('table').children('tbody').children('tr').children('td').children('span[name="total"]').show();
            index_buffer = [];
            $('#data_show_list_choose').html("เลือก : "+index_buffer.length+" รายการ");
        }
    }

    function calculateAll(){
        var val = document.getElementsByName('list_total[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){ 
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#total_price').val(roundNumber(total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#vat_price').val(roundNumber((total * ($('#vat').val()/100.0)),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#net_price').val(roundNumber((total * ($('#vat').val()/100.0) + total),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }

    function get_purchase(){
        var code = $('#purchase_order_code').val();
        $.post( "controllers/getPurchaseOrderByCode.php", { 'purchase_order_code': code }, function( data ) {  
            if(data !== null){  
                window.location = "?app=invoice_supplier&action=insert&supplier_code="+data.supplier_code+"&purchase_order_code="+data.purchase_order_code; 
            }else{  
                alert("Can not find purchase order : "+ code );
            } 
        });
    } 

    function update_due_day(id){
        var day = parseInt($('#due_date').val());
        var date = $('#craete_date').val();
        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#term').val(0);
            day = 0;
        }else if (date == ""){
            $('#due_day').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        } else{
            var date_arr = date.split('-'); 

            current_date = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
            tomorrow = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
        }

        tomorrow.setDate(current_date.getDate()+day);
        $('#due_day').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());
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
        <h1 class="page-header">Invoice Supplier Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                เพิ่มใบกำกับภาษีรับเข้า / Add Invoice Supplier
            </div>
            <div class="col-md-6">
                <table width="100%">
                    <tr>
                        <td style="padding-left:4px;">
                            <input class="purchase-ajax-post form-control" name="purchase_order_code" id="purchase_order_code" onchange=""/> 
                        </td>
                        <td style="padding-left:4px;width:100px;">
                            <button class="btn btn-success " onclick="get_purchase();" ><i class="fa fa-plus" aria-hidden="true"></i> get purchase.</button>
                        </td> 
                        <?php if($purchase_order_code != '') {?>
                        <td style="padding-left:4px;width:100px;">
                        <a class="btn btn-primary " href="index.php?app=purchase_order&action=detail&code=<?PHP echo $purchase_order_code; ?>" target="_blank"> 
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            รายละเอียดการสั่งซื้อ
                        </a>
                        </td> 
                        <?php }else{ ?>
                        <td></td> 
                        <?php } ?>
                    </tr>
                </table> 
            </div>
        </div> 
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=add" >
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                <p class="help-block">Example : A0001.</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                <select id="supplier_code" name="supplier_code" class="form-control select" onchange="get_supplier_detail()" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i = 0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option <?php if($suppliers[$i]['supplier_code'] == $supplier['supplier_code']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                <input  id="supplier_name" name="supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> " >
                                <p class="help-block">Example : Revel soft.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                <input  id="supplier_branch" name="supplier_branch" class="form-control" value="<?php echo $supplier['supplier_branch'];?>" >
                                <p class="help-block">Example : 0000 </p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" ><?php echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                <p class="help-block">Example : IN.</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                <input  id="supplier_tax" name="supplier_tax" class="form-control" value="<?php echo $supplier['supplier_tax'];?>" >
                                <p class="help-block">Example : 0305559003597.</p>
                            </div>
                        </div>
                        
                    
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row">

                            <div class="col-lg-6">
                            <div class="form-group">
                                <label>วันที่รับสินค้า / Date recieve</label>
                                <input type="text" id="recieve_date" name="recieve_date" value="<?PHP echo date('d').'-'.date('m').'-'.date('Y');?>" class="form-control calendar" onchange="check_date(this);" readonly/>
                                <input id="date_check" type="hidden" value="" />
                                <p class="help-block">31-01-2018</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                <input id="invoice_supplier_code" name="invoice_supplier_code" class="form-control" onchange="check_code(this)" value="<?php echo $last_code;?>" >
                                <input id="invoice_check" type="hidden" value="" />
                                <p class="help-block">Example : RR1801001 OR RF1801001.</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>วันที่ออกใบกำกับภาษี / Date</label>
                                <input type="text" id="craete_date" name="craete_date" class="form-control calendar" onchange="update_due_day(this)" readonly/>
                                <p class="help-block">31-01-2018</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                <input id="invoice_code_receive" name="invoice_code_receive" class="form-control" >
                                <p class="help-block">Example : INV1801001.</p>
                            </div>
                        </div>

                        <div class="col-lg-6" style="display:none">
                            <div class="form-group">
                                <label>เครดิต / Credit Day </label>
                                <input type="text" id="due_date" name="due_date" class="form-control" value="<?PHP echo $supplier['credit_day']; ?>"> 
                                <p class="help-block">30</p>
                            </div>
                        </div> 

                        <div class="col-lg-6" style="display:none">
                            <div class="form-group">
                                <label>กำหนดชำระ / Due </label>
                                <input type="text" id="due_day" name="due_day" class="form-control calendar" readonly> 
                                <p class="help-block">01-03-2018 </p>
                            </div>
                        </div>

                        <div class="col-lg-12" style="display:none">
                            <div class="form-group">
                                <label>เงื่อนไขการชำระ / term </label>
                                <input type="text" id="term" name="term" class="form-control" value="<?PHP echo $supplier['condition_pay']; ?>">
                                <p class="help-block">Bank </p>
                            </div>
                        </div>

                        <div class="col-lg-12" style="display:none">
                            <div class="form-group">
                                <label>ผู้รับใบกำกับภาษี / Employee <font color="#F00"><b>*</b></font> </label>
                                <select id="employee_code" name="employee_code" class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i = 0 ; $i < count($users) ; $i++){
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
                </div>
            </div> 

            <div>Our reference :</div>

            <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th style="text-align:center;" width="60">ลำดับ </th>
                        <th style="text-align:center;">รหัสสินค้า / รายละเอียดสินค้า </th>
                        <th style="text-align:center;" width="150">คลังสินค้า </th>
                        <th style="text-align:center;" width="150">จำนวน </th>
                        <th style="text-align:center;" width="150">ราคาต่อหน่วยบาท </th>
                        <th style="text-align:center;" width="150">จำนวนเงินบาท </th>
                        <th width="24"></th>
                    </tr>
                </thead>

                <tbody class="sorted_table">
                    <?php 
                    $total = 0;
                    $cost_duty = 0;
                    $cost_price_total_s = 0;
                    $cost_price_ex_total_s = 0;
                    $purchase_order_total_price = 0;
                    for($i=0; $i < count($invoice_supplier_lists); $i++){
                        $cost_qty = $invoice_supplier_lists[$i]['list_qty'];
                        $cost_price = $invoice_supplier_lists[$i]['list_price'];
                        $cost_total = $invoice_supplier_lists[$i]['list_price'];
                        $cost_duty += $cost_qty * $cost_price;
                    ?>
                    <tr class="odd gradeX">
                        <td class="sorter">
                            <?PHP echo ($i + 1); ?>.
                        </td>
                        <td><input type="hidden" name="invoice_supplier_list_code[]" value="0">   
                            <input type="hidden" name="purchase_order_list_code[]" value="<?PHP echo  $invoice_supplier_lists[$i]['purchase_order_list_code'];?>" />  
                            <input type="hidden" name="invoice_supplier_list_fix_type[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_fix_type'];?>" />
                            <input type="hidden" name="invoice_supplier_list_duty[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_duty'];?>" />
                            <input type="hidden" name="invoice_supplier_list_freight_in[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_freight_in'];?>" />
                            <input type="hidden" name="invoice_supplier_list_freight_in_total[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_freight_in_total'];?>" />
                            <input type="hidden" name="invoice_supplier_list_import_duty[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_import_duty'];?>" />
                            <input type="hidden" name="invoice_supplier_list_import_duty_total[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_import_duty_total'];?>" />
                            <input type="hidden" name="invoice_supplier_list_cost[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_cost'];?>" />
                            <input type="hidden" name="invoice_supplier_list_cost_total[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_cost_total'];?>" />
                            <input type="hidden" name="old_cost[]" value="<?PHP echo  $invoice_supplier_lists[$i]['invoice_supplier_list_cost'];?>" />
                            <input type="hidden" name="old_qty[]" value="<?PHP echo  $invoice_supplier_lists[$i]['list_qty'];?>" />
                            <input type="hidden" name="product_code[]" value="<?php echo $invoice_supplier_lists[$i]['product_code']; ?>" />
                            <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $invoice_supplier_lists[$i]['product_code']; ?>"  readonly/>
                            <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $invoice_supplier_lists[$i]['product_name']; ?>" />
                            <input type="text" class="form-control" name="invoice_supplier_list_product_name[]"  placeholder="Product Name (Supplier)"/>
                            <input type="text" class="form-control" name="invoice_supplier_list_product_detail[]"  placeholder="Product Detail (Supplier)" />
                            <input type="text" class="form-control" name="invoice_supplier_list_remark[]"  placeholder="Remark" value="<?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?>" />
                        </td>
                        <td>
                            <select name="stock_group_code[]" class="form-control select" data-live-search="true" >
                                <?php 
                                for($ii = 0 ; $ii < count($stock_groups) ; $ii++){
                                ?>
                                <option value="<?php echo $stock_groups[$ii]['stock_group_code'] ?>" <?PHP if($stock_groups[$ii]['stock_group_code'] == $invoice_supplier_lists[$i]['stock_group_code']){  ?> SELECTED <?PHP } ?> ><?php echo $stock_groups[$ii]['stock_group_name'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                        </td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="list_qty[]" autocomplete="off" value="<?php echo $invoice_supplier_lists[$i]['list_qty']; ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="list_price[]" autocomplete="off" value="<?php echo  number_format($invoice_supplier_lists[$i]['list_price'],4); ?>" /></td>
                        <td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="list_total[]" autocomplete="off" value="<?php echo  number_format($invoice_supplier_lists[$i]['list_qty'] * $invoice_supplier_lists[$i]['list_price'],2); ?>" /></td>
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
                        $total += $invoice_supplier_lists[$i]['list_qty'] * $invoice_supplier_lists[$i]['list_price'];
                    }
                    ?>
                </tbody>

                <tfoot>
                    <tr class="odd gradeX">
                        <td colspan="7" align="center">
                            <a id="add_product_tag" name="add_product_tag" href="javascript:;" onclick="show_purchase_order(this);" style="color:red;">
                                <i class="fa fa-plus" aria-hidden="true"></i> 
                                <input type="hidden" id="click_check" value="1">
                                <span>เพิ่มสินค้า / Add product</span>
                            </a>
                            <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">เลือกรายการสินค้า / Choose product</h4>
                                            <div class="col-lg-8">
                                                <div id="data_show_list" class="form-control alert-box alert-info" style="text-align: left;" role="alert"></div>
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
                                                        <th width="24"><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" /></th>
                                                        <th style="text-align:center;">รหัสสินค้า </th>
                                                        <th style="text-align:center;">ชื่อสินค้า </th>
                                                        <th style="text-align:center;" width="150">จำนวน </th>
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย </th>
                                                        <th style="text-align:center;" width="150">จำนวนเงิน </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyAdd"></tbody>
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
                        <td colspan="3" align="left" style="vertical-align: middle;">
                            <span>ราคารวมทั้งสิ้น / Sub total</span>
                        </td>
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
                            <input type="text" class="form-control" style="text-align: right;" id="total_price" name="total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="3" align="left" style="vertical-align: middle;">
                            <table>
                                <tr>
                                    <td>
                                        <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                    </td>
                                    <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                        <input type="text" class="form-control" style="text-align: right;" id="vat" name="vat" value="<?php echo $supplier['vat'];?>" onchange="calculateAll();"/>
                                    </td>
                                    <td width="16">
                                    %
                                    </td>
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
                            <input type="text" class="form-control" style="text-align: right;" id="vat_price"  name="vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr class="odd gradeX">
                        <td colspan="3" align="left" style="vertical-align: middle;">
                            <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                        </td>
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
                            <input type="text" class="form-control" style="text-align: right;" id="net_price" name="net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
                        </td>
                        <td>
                        </td>
                    </tr>
                </tfoot>
            </table>   

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ค่าขนส่งสินค้า / Freight in<font color="#F00"><b>*</b></font></label>
                        <div>
                            <table name="tb_freight_in" class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th style="width:64px;text-align:center;">ลำดับ</th>
                                        <th style="text-align:center;" >รายการ</th>
                                        <th style="text-align:center;" >จำนวนเงิน</th>
                                        <th style="width:24px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php for($i=0; $i < count($invoice_supplier_freight_in_lists); $i++){ ?>
                                    <tr class="odd gradeX">
                                        <td class="sorter" style="vertical-align: middle;text-align:center;"><?PHP echo $i + 1; ?></td>
                                        <td>
                                            <input type="hidden" name="invoice_supplier_freight_in_list_code[]" value="<?php echo $invoice_supplier_freight_in_lists[$i]['invoice_supplier_freight_in_list_code']; ?>" />
                                            <input type="text" class="form-control" name="invoice_supplier_freight_in_list_name[]" value="<?php echo $invoice_supplier_freight_in_lists[$i]['invoice_supplier_freight_in_list_name']; ?>" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" style="text-align:right;" name="invoice_supplier_freight_in_list_total[]" value="<?php echo number_format($invoice_supplier_freight_in_lists[$i]['invoice_supplier_freight_in_list_total'],2);?>" onchange="calculate_freight_in();calculateCost();" />
                                        </td>
                                        <td>
                                            <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?PHP }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" align="center" >
                                            <a href="javascript:;" onclick="add_freight_in_list(this);" style="color:red;">
                                                <i class="fa fa-plus" aria-hidden="true"></i> 
                                                <span>เพิ่มรายการ / Add list</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" style="vertical-align: middle;">จำนวนเงินรวม </td>
                                        <td style="width:150px;">
                                            <input  id="freight_in" name="freight_in" onchange="calculateCost();" class="form-control" style="text-align:right" value="<?php echo number_format($invoice_supplier['freight_in'],2);?>" onchange="calculateCost()" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <p class="help-block">Example : 0.</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(".example-ajax-post").easyAutocomplete(options);
    $(".purchase-ajax-post").easyAutocomplete(options_purchase);
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });

    $(function() {
        $("#click_check").val(0);
    });
</script>