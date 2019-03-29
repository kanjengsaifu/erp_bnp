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

console.log(roundNumber(56.755,2));
var exchange_rate_baht_value = <?php if($exchange_rate_baht['exchange_rate_baht_value'] != ''){echo $exchange_rate_baht['exchange_rate_baht_value'];}else {echo 0;}?>;
var vat = parseFloat('<?PHP echo $invoice_supplier['vat'] ;?>');
var vat_type='<?PHP echo $invoice_supplier['vat_type'] ;?>';
var freight_in=parseFloat('<?PHP echo $invoice_supplier['freight_in'] ;?>');

function calculate(all_duty){

    
    var invoice_supplier_list_code = document.getElementsByName("invoice_supplier_list_code[]");
    var invoice_supplier_list_qty = document.getElementsByName("invoice_supplier_list_qty[]");
    var invoice_supplier_list_duty_percent = document.getElementsByName("invoice_supplier_list_duty_percent[]");
    var invoice_supplier_list_import_duty_total = document.getElementsByName("invoice_supplier_list_import_duty_total[]");
    var invoice_supplier_list_freight_in_total = document.getElementsByName("invoice_supplier_list_freight_in_total[]");
    var invoice_supplier_list_freight_in = document.getElementsByName("invoice_supplier_list_freight_in[]");
    
    var invoice_supplier_list_cost_total = document.getElementsByName("invoice_supplier_list_cost_total[]");
    var invoice_supplier_list_cost = document.getElementsByName("invoice_supplier_list_cost[]");

    var invoice_supplier_list_currency_total = document.getElementsByName("invoice_supplier_list_currency_total[]");
    var invoice_supplier_list_currency_price = document.getElementsByName("invoice_supplier_list_currency_price[]");
    var invoice_supplier_list_price = document.getElementsByName("invoice_supplier_list_price[]");
    var invoice_supplier_list_total = document.getElementsByName("invoice_supplier_list_total[]");
    var invoice_supplier_list_import_duty = document.getElementsByName("invoice_supplier_list_import_duty[]");


    /************************ Calculate currency and  exchange rate  *************************/
    var sum = 0.0;
    var total = 0.0; 
    var freight_in_amount =0;

    var invoice_supplier_list_total_sum = 0;
    var invoice_supplier_list_currency_total_sum = 0;

    for(var i = 0 ; i < (invoice_supplier_list_code.length); i++){
        var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));
        var currency_price = parseFloat(invoice_supplier_list_currency_price[i].value.toString().replace(new RegExp(',', 'g'),''));

        var currency_total = roundNumber((qty * currency_price),2);
        var list_total = roundNumber((currency_total * exchange_rate_baht_value),2);
        var list_price = roundNumber((list_total / qty),2);

        invoice_supplier_list_currency_total[i].value = roundNumber(currency_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        invoice_supplier_list_total[i].value = roundNumber(list_total,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        invoice_supplier_list_price[i].value = roundNumber(list_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

        var val_currency_total = parseFloat(invoice_supplier_list_currency_total[i].value.toString().replace(new RegExp(',', 'g'),'')) ;
        if(isNaN(val_currency_total)){
            val_currency_total = 0;
        }
        invoice_supplier_list_currency_total_sum += val_currency_total;

        var val_total = parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) ;
        if(isNaN(val_total)){
            val_total = 0;
        }
        invoice_supplier_list_total_sum += val_total; 
    }
    document.getElementById("invoice_supplier_currency_total").value = roundNumber(invoice_supplier_list_currency_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById("total_price").value = roundNumber(invoice_supplier_list_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    
    console.log("invoice_supplier_currency_total : ",roundNumber(invoice_supplier_list_currency_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    
    console.log("total_price : ",roundNumber(invoice_supplier_list_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    

    /************************ End Calculate currency and  exchange rate  *************************/
  



    /************************ Freight in   *************************/

    var total_price = parseFloat(document.getElementById("total_price").value.toString().replace(new RegExp(',', 'g'),''))
    for(var i = 0 ; i < (invoice_supplier_list_code.length); i++){
        var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));
        var ex_total = parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')); 
        
        var cost_price_f = roundNumber((ex_total / total_price * freight_in),2);
        console.log("ex_total : ",ex_total);
        console.log("total_price : ",total_price);
        console.log("freight_in : ",freight_in);
        console.log("cost_price_f : ",cost_price_f);
        console.log("freight_in_amount : ",freight_in_amount);

        if(freight_in -  freight_in_amount > 0){

            if (i + 1 == invoice_supplier_list_code.length) {
                cost_price_f = freight_in -  freight_in_amount;
                
            }else if (cost_price_f > freight_in -  freight_in_amount) {
                cost_price_f = freight_in -  freight_in_amount;
            } 

            freight_in_amount = freight_in_amount + cost_price_f;
        }else {
            cost_price_f = 0;
        }
        invoice_supplier_list_freight_in[i].value = roundNumber((cost_price_f / qty),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        invoice_supplier_list_freight_in_total[i].value = roundNumber(cost_price_f,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    /************************ End Freight in   *************************/



    /************************ Import duty   *************************/

    for(var i = 0 ; i < (invoice_supplier_list_code.length); i++){
        var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));
        //console.log('Qty : ',qty);
        var duty = 0.0;
        var invoice_supplier_list_fix_type =  $("input[name='invoice_supplier_list_fix_type["+invoice_supplier_list_code[i].value+"]']:checked");
        var ex_total = parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')); 
        //console.log("invoice_supplier_list_fix_type :" , invoice_supplier_list_fix_type);
        
        if(invoice_supplier_list_fix_type[0].value == 'percent-fix'){
            duty = roundNumber(parseFloat(((parseFloat(invoice_supplier_list_duty_percent[i].value.toString().replace(new RegExp(',', 'g'),'')) / 100 ) * ex_total)),2); 
            invoice_supplier_list_import_duty_total[i].value = roundNumber(duty,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            invoice_supplier_list_cost_total[i].value = roundNumber(parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_freight_in_total[i].value.toString().replace(new RegExp(',', 'g'),'')),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

            invoice_supplier_list_import_duty[i].value = roundNumber((duty / qty),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        }else if(invoice_supplier_list_fix_type[0].value == 'price-fix'){
            duty = roundNumber(parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')),2);  
            invoice_supplier_list_cost_total[i].value = roundNumber(parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_freight_in_total[i].value.toString().replace(new RegExp(',', 'g'),'')),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

            invoice_supplier_list_import_duty[i].value = roundNumber((duty /  qty),2);
            var duty_percent = duty / ex_total * 100;
            invoice_supplier_list_duty_percent[i].value = roundNumber(duty_percent,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        }else{ 
            total += ex_total;
        }
        sum += duty;
        //console.log('Sumation : ',sum);
    }

    all_duty = all_duty - sum;
    var use_duty = 0;
    for(var i = 0 ; i < (invoice_supplier_list_code.length); i++){
        var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));
        //console.log('Qty : ',qty);
        var duty = 0.0;
        var ex_total = parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),''));
        var invoice_supplier_list_fix_type =  $("input[name='invoice_supplier_list_fix_type["+invoice_supplier_list_code[i].value+"]']:checked");
        if(invoice_supplier_list_fix_type[0].value == 'no-fix'){
            if(all_duty - use_duty > 0){
                duty = roundNumber((all_duty * ex_total / total),2);

                if (all_duty - use_duty < duty || i+1 == (invoice_supplier_list_code.length)){
                    duty = all_duty - use_duty;
                }
                invoice_supplier_list_import_duty_total[i].value = roundNumber(duty,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                invoice_supplier_list_cost_total[i].value = roundNumber(parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_freight_in_total[i].value.toString().replace(new RegExp(',', 'g'),'')),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");


                invoice_supplier_list_import_duty[i].value = roundNumber((duty /  qty),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                invoice_supplier_list_duty_percent[i].value = roundNumber((duty/ex_total*100),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"); 

                use_duty += duty;
            }else{
                invoice_supplier_list_import_duty_total[i].value = 0;
                invoice_supplier_list_cost_total[i].value = roundNumber(parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_total[i].value.toString().replace(new RegExp(',', 'g'),'')) + parseFloat(invoice_supplier_list_freight_in_total[i].value.toString().replace(new RegExp(',', 'g'),'')),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");


                invoice_supplier_list_import_duty[i].value = 0;
                invoice_supplier_list_duty_percent[i].value = 0;
            }
        }  
    } 
    /************************ End Import duty   *************************/



    /************************ Cost total calculate   *************************/
    var invoice_supplier_list_cost_total_sum = 0;
    var invoice_supplier_list_duty_sum = 0;
    var invoice_supplier_list_freight_in_total_sum = 0;
    for(var i = 0 ; i < (invoice_supplier_list_cost_total.length); i++){
        var qty = parseFloat(invoice_supplier_list_qty[i].value.toString().replace(new RegExp(',', 'g'),''));

        var val_duty_total = parseFloat(invoice_supplier_list_import_duty_total[i].value.toString().replace(new RegExp(',', 'g'),'')) ;
        if(isNaN(val_duty_total)){
            val_duty_total = 0;
        }
        invoice_supplier_list_duty_sum += val_duty_total;



        var val_freight_total = parseFloat(invoice_supplier_list_freight_in_total[i].value.toString().replace(new RegExp(',', 'g'),'')) ;
        if(isNaN(val_freight_total)){
            val_freight_total = 0;
        }
        invoice_supplier_list_freight_in_total_sum += val_freight_total;



        var val_cost_total = parseFloat(invoice_supplier_list_cost_total[i].value.toString().replace(new RegExp(',', 'g'),'')) ;
        if(isNaN(val_cost_total)){
            val_cost_total = 0;
        }
        invoice_supplier_list_cost[i].value = roundNumber((val_cost_total/qty),2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        invoice_supplier_list_cost_total_sum += val_cost_total;



    }
    document.getElementById("import_duty").value = roundNumber(invoice_supplier_list_duty_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById("freight_in").value = roundNumber(invoice_supplier_list_freight_in_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById("cost_total").value = roundNumber(invoice_supplier_list_cost_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

    document.getElementById("vat").value =  roundNumber(vat,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

    var vat_price = 0 ;
    var net_price = invoice_supplier_list_total_sum ;

    document.getElementById("vat_price").value =  roundNumber(vat_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById("net_price").value =  roundNumber(net_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    
    
    console.log("vat_price : ",roundNumber(vat_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
   
    console.log("net_price : ",roundNumber(net_price,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
   
    console.log("import_duty : ",roundNumber(invoice_supplier_list_duty_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    
    console.log("freight_in : ",roundNumber(invoice_supplier_list_freight_in_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    
    console.log("cost_total : ",roundNumber(invoice_supplier_list_cost_total_sum,2).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));

    /************************ End Cost total calculate   *************************/

    return true;
   
}

</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Invoice Supplier Management</h1>
    </div> 
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            
                <div class="col-md-4">
                รายละเอียดใบกำกับภาษีรับเข้า / Invoice Supplier Detail 
                </div>
                <div class="col-md-8" align="right">
                    <?PHP if($previous_code != ""){?>
                    <a class="btn btn-primary" href="?app=invoice_supplier&action=cost&code=<?php echo $previous_code;?>" > <i class="fa fa-angle-double-left" aria-hidden="true"></i> <?php echo $previous_code;?> </a>
                    <?PHP } ?>

                    <a class="btn btn-success "  href="?app=invoice_supplier&action=insert&sort=<?php echo $sort;?>" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    
                    <?PHP if ($sort == "ภายนอกประเทศ") { ?>

                        <a class="btn btn-danger" href="print.php?app=invoice_supplier_abroad&action=pdf&type=credit&sort=<?php echo $sort;?>&code=<?php echo $invoice_supplier_code;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบตั้งเจ้าหนี้ต่างประเทศ </a>
                    
                        <a class="btn btn-danger" href="print.php?app=invoice_supplier_abroad&action=pdf&type=receive&sort=<?php echo $sort;?>&code=<?php echo $invoice_supplier_code;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบรับสินค้า </a>
                    
                    <?PHP } else { ?>

                    <a class="btn btn-danger" href="print.php?app=invoice_supplier&action=pdf&lan=en&sort=<?php echo $sort;?>&code=<?php echo $invoice_supplier_code;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบรับสินค้า </a>
                    
                    <?PHP } ?>

                    <?PHP if($next_code != ""){?>
                    <a class="btn btn-primary" href="?app=invoice_supplier&action=cost&sort=<?php echo $sort;?>&code=<?php echo $next_code;?>" >  <?php echo $next_code;?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a>
                    <?PHP } ?>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return calculate('<?php echo $invoice_supplier['import_duty'];?>');" action="index.php?app=invoice_supplier&action=edit_cost&code=<?php echo $invoice_supplier_code;?>" >
                    <input type="hidden" id="invoice_supplier_code" name="invoice_supplier_code" value="<?php echo $invoice_supplier_code; ?>" />
                    <input type="hidden" id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $invoice_supplier['supplier_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_name_en'] ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_address_1'] ."\n". $invoice_supplier['supplier_address_2'] ."\n". $invoice_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_tax'];?></p>
                                    </div>
                                </div>
                            <?PHP if($invoice_supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Exchange rate Baht<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $exchange_rate_baht['exchange_rate_baht_value'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Import duty<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['import_duty'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Freight in<font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['freight_in'];?></p>
                                    </div>
                                </div>
                            <?PHP } ?>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['due_day'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['term'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับใบกำกับภาษี / Date recieve</label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['recieve_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code_gen'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $invoice_supplier['user_name'];?> <?PHP echo $invoice_supplier['user_lastname'];?> (<?PHP echo $invoice_supplier['user_position_name'];?>)</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="48">ลำดับ <br> (์No.)</th>
                                <th style="text-align:center;" >รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price <?PHP echo $exchange_rate_baht['currency_sign'];?>) </th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย (บาท) <br> (Unit price baht) </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount <?PHP echo $exchange_rate_baht['currency_sign'];?>)</th>
                                <th style="text-align:center;" width="150">จำนวนเงิน (บาท) <br> (Amount baht)</th>
                                <th style="text-align:center;" width="150">ภาษีนำเข้า (บาท) <br> (Import duty)</th>
                                <th style="text-align:center;" width="150">ค่าจัดส่ง (บาท) <br> (Freight in)</th>
                                <th style="text-align:center;" width="150">ราคารวมสุทธิ (บาท) <br> (Total)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php  
                            $cost_duty = 0; 
                            for($i=0; $i < count($invoice_supplier_lists); $i++){ 
                                $cost_qty = $invoice_supplier_lists[$i]['invoice_supplier_list_qty'];
                                $cost_price = $invoice_supplier_lists[$i]['invoice_supplier_list_currency_price'] ;
                                $cost_duty += $cost_qty * $cost_price;
                            }
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>.
                                </td>
                                
                                <td>
                                    <b><?php echo $invoice_supplier_lists[$i]['product_code']; ?></b><br>
                                    <?php echo $invoice_supplier_lists[$i]['product_name']; ?><br>
                                    <span>Sub name : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?><br>
                                    <span>Detail : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?><br>
                                    <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_qty[]" class="form-control" type="text" style="text-align:right;" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'],0) ; ?>"  readonly/>
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_currency_price[]" class="form-control" type="text" style="text-align:right;" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_currency_price'],2) ; ?>"  readonly/>
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_price[]" class="form-control" type="text" style="text-align:right;" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2) ; ?>"  readonly/>
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_currency_total[]" class="form-control" type="text" style="text-align:right;" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_currency_total'],2); ?>"  readonly/>
                                
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_total[]" class="form-control" type="text" style="text-align:right;" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_total'],2); ?>"  readonly/>
                                </td>
                                <td align="right">    
                                <input name="invoice_supplier_list_code[]" type="hidden" class="form-control" value="<?php echo  $invoice_supplier_lists[$i]['invoice_supplier_list_code']; ?>" />    
                                                 
                                    <table>
                                        <tr>
                                            <td>
                                                <input name="invoice_supplier_list_fix_type[<?php echo  $invoice_supplier_lists[$i]['invoice_supplier_list_code']; ?>]" type="radio" value="no-fix"  <?PHP if($invoice_supplier_lists[$i]['invoice_supplier_list_fix_type'] == "no-fix" || $invoice_supplier_lists[$i]['invoice_supplier_list_fix_type'] == ""){ ?> checked <?PHP } ?>/>
                                                <span><b>No fix.</b></span>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input name="invoice_supplier_list_fix_type[<?php echo  $invoice_supplier_lists[$i]['invoice_supplier_list_code']; ?>]" type="radio" value="percent-fix"  <?PHP if($invoice_supplier_lists[$i]['invoice_supplier_list_fix_type'] == "percent-fix"){ ?> checked <?PHP } ?>/>
                                                <span><b>Percent.</b></span> 
                                                <input name="invoice_supplier_list_duty_percent[]" autocomplete="off" type="text" style="text-align:right;" onchange="calculate('<?php echo $invoice_supplier['import_duty'];?>');" class="form-control" value="<?php number_format($invoice_supplier_lists[$i]['invoice_supplier_list_duty'] ,2); ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input name="invoice_supplier_list_fix_type[<?php echo  $invoice_supplier_lists[$i]['invoice_supplier_list_code']; ?>]" type="radio" value="price-fix"  <?PHP if($invoice_supplier_lists[$i]['invoice_supplier_list_fix_type'] == "price-fix"){ ?> checked <?PHP } ?>/>
                                                <span><b>Price.</b></span> 
                                                <input name="invoice_supplier_list_import_duty[]" type="hidden" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_import_duty']) ; ?>"  />
                                                <input name="invoice_supplier_list_import_duty_total[]" autocomplete="off" type="text" style="text-align:right;" onchange="calculate('<?php echo $invoice_supplier['import_duty'];?>');" class="form-control" value="<?php echo number_format($invoice_supplier_lists[$i]['invoice_supplier_list_import_duty_total'],2); ?>"  />
                                            
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td align="right">
                                    <input name="invoice_supplier_list_freight_in_total[]" class="form-control" type="text" style="text-align:right;" value="<?php echo  number_format($cost_price_f,2); ?>" readonly/>
                                    <input name="invoice_supplier_list_freight_in[]" type="hidden" value="<?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_freight_in'],2) ?>"  />
                                    
                                </td>
                                <td align="right">
                                    
                                    <input name="invoice_supplier_list_cost[]" type="hidden" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_cost'],2) ; ?>"  />
                                    <input name="invoice_supplier_list_cost_total[]" type="text" value="<?php echo   number_format($invoice_supplier_lists[$i]['invoice_supplier_list_cost_total'],2); ?>" class="form-control"  style="text-align:right;" readonly />
                                </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="3" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="text-align: right;">
                                    <input id="invoice_supplier_currency_total" name="invoice_supplier_currency_total" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['invoice_supplier_currency_total'],2) ;?>" readonly/>
                                </td>
                                <td style="text-align: right;"> 
                                    <input id="total_price" name="total_price" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['total_price'],2) ;?>" readonly/>
                                
                                </td>
                                <td style="text-align: right;">
                                    <input id="import_duty" name="import_duty" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['import_duty'],2) ;?>" readonly/>
                                </td>
                                <td style="text-align: right;">
                                    <input id="freight_in" name="freight_in" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['freight_in'],2) ;?>" readonly/>
                                </td>
                                <td style="text-align: right;">
                                    <input id="cost_total" name="cost_total" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['cost_total'],2) ;?>" readonly/>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;text-align: right;"> 
                                                <input id="vat" name="vat" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['vat'],2) ;?>" readonly/>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                    <input id="vat_price"  name="vat_price" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['vat_price'],2) ;?>" readonly/>

                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                  
                                </td>

                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                <input id="net_price" name="net_price" class="form-control" type="text" style="text-align:right;" value="<?PHP echo number_format($invoice_supplier['net_price'],2) ;?>" readonly/>

                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;">
                                </td>
                                <td style="text-align: right;"> 
                                    
                                </td>

                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
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
$( document ).ready(function() {
calculate('<?php echo $invoice_supplier['import_duty'];?>');
});
</script>
 