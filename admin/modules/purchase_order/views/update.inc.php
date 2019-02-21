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

    function check_code(){
        var code = $('#purchase_order_code').val();
        $.post( "controllers/getPurchaseOrderByCodeCheck.php", { 'purchase_order_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("purchase_order_code").focus();
                $("#purchase_check").val(data.purchase_order_code);
                
            } else{
                $("#purchase_check").val("");
            }
        });
    }

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#purchase_order_date").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("purchase_order_date").focus();
            } else{
                $("#date_check").val("0");
                //generate_credit_date();
            }
        });
    }

    function check(){

        var supplier_code = document.getElementById("supplier_code").value; 
        var purchase_order_date = document.getElementById("purchase_order_date").value;
        var purchase_order_credit_term = document.getElementById("purchase_order_credit_term").value;
        var user_code = document.getElementById("user_code").value;
        var purchase_check = document.getElementById("purchase_check").value;
        var purchase_order_code = document.getElementById("purchase_order_code").value;
        var date_check = document.getElementById("date_check").value;
        
        supplier_code = $.trim(supplier_code); 
        purchase_order_date = $.trim(purchase_order_date);
        purchase_order_credit_term = $.trim(purchase_order_credit_term);
        user_code = $.trim(user_code);

        if(supplier_code.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(purchase_order_date.length == 0){
            alert("Please input purchase Order Date");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(user_code.length == 0){
            alert("Please input employee");
            document.getElementById("user_code").focus();
            return false;
        }else{
            return true;
        }
    }

    function get_supplier_detail(){
        var supplier_code = document.getElementById('supplier_select').value;
        // var purchase_order_category = document.getElementById('purchase_order_category').value;
        var user_code = document.getElementById('user_code').value;
        document.getElementById('supplier_code').value = supplier_code;
        $.post( "controllers/getSupplierByID.php", { 'supplier_code': supplier_code}, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('purchase_order_credit_term').value = data.credit_day;
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });

        // $.post( "controllers/getPurchaseOrderCodeByID.php", { 'supplier_code': supplier_code, 'user_code':user_code, 'purchase_order_category':purchase_order_category  }, function( data ) {
        //     document.getElementById('purchase_order_code').value = data;
        //     check_code();

        // });

        // $.post( "controllers/getMaterialBySupplierID.php", { 'supplier_code': supplier_code }, function( data ) {
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

          var qty =  $(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val(  );
          var price =  $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( );
          var sum =  $(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( );

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0;
        }

        if(isNaN(sum)){
            sum = 0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val( qty );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( price );
        $(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( sum );

        
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



    function show_purchase_order(id){
        var supplier_code = document.getElementById('supplier_code').value;   
        
        if(supplier_code != ""){

            $.post( "controllers/getPurchaseOrderListBySupplierID.php", 
            {  
                'supplier_code': supplier_code
             }, function( data ) {
                if(data.length > 0){
                    // console.log(data);
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].material_code+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].material_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].material_name+ 
                                        '</td>'+ 
                                        '<td align="right">'+
                                            data[i].material_supplier_buyprice +
                                        '</td>'+ 
                                    '</tr>';

                    }
                    
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }else{
                    //add_row_new(id);
                    alert("ไม่มีรายการวัตถุดิบที่สามารถเปิดใบสั่งซื้อได้");
                }
                
            });
        }else{
            alert("Please select supplier.");
        }
        
    } 

    function search_pop_like(id){

        var supplier_code = document.getElementById('supplier_code').value; 

        $.post( "controllers/getPurchaseOrderListBySupplierID.php", 
        {  
            'supplier_code': supplier_code, 
            'search':$(id).val() 
        }, function( data ) {
            // console.log(data);
            var content = "";
            if(data.length > 0){
                data_buffer = data;
                
                for(var i = 0; i < data.length ; i++){

                    content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].material_code+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].material_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].material_name+ 
                                        '</td>'+ 
                                        '<td align="right">'+
                                            data[i].material_supplier_buyprice +
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
                    $.post( "controllers/getMaterialSupplierPriceByID.php", { 'material_code': $.trim(data.material_code),'supplier_code': $.trim(supplier_code)}, function( data ) { 
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
                            '<input type="hidden" name="purchase_order_list_code[]" value="0" />'+ 
                            '<input type="hidden" name="material_code[]" value="'+data_buffer[i].material_code+'" />'+ 
                            '<span>'+data_buffer[i].material_code+'</span>'+
                        '</td>'+
                        '<td>'+
                        '<span>Material name : </span>'+
                        '<span>'+data_buffer[i].material_name+'</span><br>'+ 
                        '</td>'+ 
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_qty[]" onchange="update_sum(this);"  value="1"/></td>'+
                        '<td >'+
                            '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price[]" onchange="update_sum(this);" value="'+data_buffer[i].material_supplier_buyprice+'"/>'+
                            '<input type="checkbox" name="save_material_price[]" value="'+ data_buffer[i].material_code +'" /> บันทึกราคาซื้อ'+ 
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price_sum[]" onchange="update_sum(this);" value="'+data_buffer[i].material_supplier_buyprice+'"/></td>'+
                        
                        '<td>'+
                            '<a href="javascript:;" onclick="material_detail_blank(this);">'+
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
        
        $(".example-ajax-post").easyAutocomplete(options);
        update_line();
        calculateAll();
    } 

    function add_row_new(id){
        var supplier_code = document.getElementById('supplier_code').value;   
        
        if(supplier_code != ""){ 

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
                    '<input type="hidden" name="purchase_order_list_code[]" value="0" />'+  
                        '<input class="example-ajax-post form-control" name="material_code[]" onchange="show_data(this);" placeholder="Material Code" />'+ 
                    '</td>'+
                    '<td>'+
                    '<span>Material name : </span>'+
                            '<span name="material_name[]" ></span> '+ 
                    '</td>'+ 
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_qty[]"  onchange="update_sum(this);" value="1"/></td>'+
                    '<td >'+
                        '<input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price[]" onchange="update_sum(this);" />'+ 
                        '<input type="checkbox" name="save_material_price[]" value="" /> บันทึกราคาซื้อ'+
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" name="purchase_order_list_price_sum[]" onchange="update_sum(this);" /></td>'+
                    
                    '<td>'+
                        '<a href="javascript:;" onclick="material_detail_blank(this);">'+
                            '<i class="fa fa-file-text-o" aria-hidden="true"></i>'+
                        '</a> '+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            );
            $(".example-ajax-post").easyAutocomplete(options);
            update_line();
        }else{
            alert("Please select supplier.");
        }
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

        var val = document.getElementsByName('purchase_order_list_price_sum[]');
        var purchase_order_vat = parseFloat(document.getElementById('purchase_order_vat').value);
        var vat_type = document.getElementById('purchase_order_vat_type').value;
    
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        if(vat_type == 1){
            total = total - ((purchase_order_vat/( 100 + purchase_order_vat )) * total); 
        }else if(vat_type == 2){ 
            total = total;
        }else{
            total = total; 
            $('#purchase_order_vat').val(0);
        } 

        $('#purchase_order_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#purchase_order_vat_price').val((total * ($('#purchase_order_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#purchase_order_net_price').val((total * ($('#purchase_order_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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


</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management </h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            แก้ไขใบสั่งซื้อวัตถุดิบ /  Edit Purchase Order 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=edit&purchase_order_code=<?php echo $purchase_order_code;?>&type=<?PHP echo $type; ?>" > 
                    <input type="hidden"  id="purchase_order_date_old" name="purchase_order_date_old" value="<?php echo $purchase_order['purchase_order_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font> <?php if($purchase_order['purchase_order_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $purchase_order['purchase_order_rewrite_no']; ?></font></b> <?PHP } ?></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <input type="hidden" id="supplier_code" name="supplier_code" value="<?PHP echo $supplier['supplier_code']; ?>"/>
                                        <select id="supplier_select" name="supplier_select" class="form-control select" onchange="get_supplier_detail()" data-live-search="true" >
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
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">
                            <div class="row"> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รหัสใบสั่งซื้อวัตถุดิบ / Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <input readonly id="purchase_order_code" name="purchase_order_code" class="form-control" value="<? echo $purchase_order['purchase_order_code'];?>"  onchange="check_code()" > 
                                         
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อวัตถุดิบ / Purchase Order Date</label>
                                        <input type="text" id="purchase_order_date" name="purchase_order_date" value="<? echo $purchase_order['purchase_order_date'];?>"  class="form-control calendar"   onchange="check_date(this);" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เครดิต (วัน) / Credit term (Day)</label>
                                        <input type="text" id="purchase_order_credit_term" name="purchase_order_credit_term" value="<? echo $purchase_order['purchase_order_credit_term'];?>" class="form-control"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบสั่งซื้อ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="user_code" name="user_code" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?php if($users[$i]['user_code'] == $purchase_order['user_code']){?> selected <?php }?> value="<?php echo $users[$i]['user_code'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div> 
                                <!-- <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>จัดส่งโดย / Delivery by</label>
                                        <input type="text" id="purchase_order_delivery_by" name="purchase_order_delivery_by" value="<? echo $purchase_order['purchase_order_delivery_by'];?>"  class="form-control"/>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div> -->
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่จัดส่ง / Delivery Date</label>
                                        <input type="text" id="purchase_order_delivery_date" name="purchase_order_delivery_date" value="<? echo $purchase_order['purchase_order_delivery_date'];?>"  class="form-control calendar"   onchange="check_date(this);" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark</label>
                                        <input type="text" id="purchase_order_remark" name="purchase_order_remark" value="<? echo $purchase_order['purchase_order_remark'];?>"  class="form-control"  />
                                        <p class="help-block">-</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>    
                    <div>
                    Our reference :
                    </div>
                    <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th>
                                <th style="text-align:center;" width="150">รหัสวัตถุดิบ </th>
                                <th style="text-align:center;" >ชื่อวัตถุดิบ / หมายเหตุ </th> 
                                <th style="text-align:center;" width="120">จำนวน </th>
                                <th style="text-align:center;" width="120">ราคาต่อหน่วย </th>
                                <th style="text-align:center;" width="120">จำนวนเงิน  </th>
                                <th width="24"></th>
                            </tr>
                        </thead>
                        <tbody  class="sorted_table">
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" name="purchase_order_list_code[]" value="<?PHP echo  $purchase_order_lists[$i]['purchase_order_list_code'];?>"/>
                                    
                                    <input type="hidden" name="material_code[]" value="<?PHP echo  $purchase_order_lists[$i]['material_code'];?>" /> 

                                    <span><?PHP echo  $purchase_order_lists[$i]['material_code'];?></span>
                                </td>
                                <td>
                                    <span>Material name : </span>
                                    <span><?PHP echo  $purchase_order_lists[$i]['material_name'];?></span>
                                </td> 
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_qty[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_qty']; ?>" /></td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" />
                                    <input type="checkbox" name="save_material_price[]" value="<?php echo $purchase_order_lists[$i]['material_code']; ?>"/> บันทึกราคาซื้อ
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" autocomplete="off" readonly onchange="update_sum(this);" name="purchase_order_list_price_sum[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" /></td>
                                
                                <td>
                                    <a href="javascript:;" onclick="material_detail_blank(this);">
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
                                <td colspan="7" align="center">
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
                                            <!-- <div class="row">
                                                <div class="col-md-offset-8 col-md-4" align="right">
                                                    <input type="text" class="form-control" name="search_pop" onchange="search_pop_like(this)" placeholder="Search"/>
                                                </div>
                                            </div> -->
                                            <br>
                                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th width="24"><input type="checkbox" value="all" id="check_all" onclick="checkAll(this)" /></th>
                                                        <th style="text-align:center;">รหัสวัตถุดิบ  </th>
                                                        <th style="text-align:center;">ชื่อวัตถุดิบ  </th> 
                                                        <th style="text-align:center;" width="150">ราคาต่อหน่วย (บาท) </th> 
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
                            <tr class="odd gradeX">
                                <td colspan="3" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td>
                                <?PHP
                                    if($purchase_order['purchase_order_vat_type'] == 1){
                                        $total_val = $total - (($purchase_order['purchase_order_vat']/( 100 + $purchase_order['purchase_order_vat'] )) * $total);
                                    } else if($purchase_order['purchase_order_vat_type'] == 2){
                                        $total_val = $total;
                                    } else {
                                        $total_val = $total;
                                    }
                                ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_total_price" name="purchase_order_total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;"> 
                                    <table>
                                        <tr>
                                            <td colspan="3" >
                                                <span>ประเภทภาษีมูลค่าเพิ่ม / Vat type </span>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="2" >
                                                
                                                <select id="purchase_order_vat_type" name="purchase_order_vat_type" class="form-control" style="max-width:130px;margin-left:auto;margin-right:8px;margin-bottom:8px;" onchange="calculateAll()"> 
                                                    <option value="0" <?PHP if($purchase_order['purchase_order_vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                                    <option value="1"  <?PHP if($purchase_order['purchase_order_vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                                    <option value="2"  <?PHP if($purchase_order['purchase_order_vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                                                </select>
                                            </td>
                                            <td width="16"> 
                                            </td>
                                        </tr>
                                        <tr >
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat" name="purchase_order_vat" value="<?php echo $purchase_order['purchase_order_vat'];?>" onchange="calculateAll();" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <?PHP 
                                    if($purchase_order['purchase_order_vat_type'] == 1){
                                        $vat_val = ($purchase_order['purchase_order_vat']/( 100 + $purchase_order['purchase_order_vat'] )) * $total;
                                    } else if($purchase_order['purchase_order_vat_type'] == 2){
                                        $vat_val = ($purchase_order['purchase_order_vat']/100) * $total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat_price"  name="purchase_order_vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td>
                                    <?PHP 
                                    if($purchase_order['purchase_order_vat_type'] == 1){
                                        $net_val =  $total;
                                    } else if($purchase_order['purchase_order_vat_type'] == 2){
                                        $net_val = ($purchase_order['purchase_order_vat']/100) * $total + $total;
                                    } else {
                                        $net_val = $total;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_net_price" name="purchase_order_net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-6 col-lg-6" align="right">
                            <a href="index.php?app=purchase_order" class="btn btn-default">Back</a>
                            
                            <?php 
                            if( $purchase_order['purchase_order_status'] == 'New'){
                            ?>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                            <!-- <a href="index.php?app=purchase_order&action=checking&purchase_order_code=<?php echo $purchase_order_code;?>&supplier_code=<?PHP echo $purchase_order['supplier_code']; ?>" class="btn btn-danger" >Check Order</a> -->
                            <!-- <a href="index.php?app=purchase_order&action=request&purchase_order_code=<?php echo $purchase_order_code;?>" class="btn btn-warning" >Request Order</a> -->
                            <?php 
                            }
                            ?>
                            <?php 
                            if( $purchase_order['purchase_order_status'] == 'Approved'){
                            ?>
                            <a href="index.php?app=purchase_order&action=sending&purchase_order_code=<?php echo $purchase_order_code;?>&supplier_code=<?PHP echo $purchase_order['supplier_code']; ?>" class="btn btn-warning" >Send Order</a>
                            
                            <?php 
                            }
                            ?>
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
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>