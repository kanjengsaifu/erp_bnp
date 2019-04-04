<script>

    function check_supplier_code(){ 
        
        var supplier_code = document.getElementById("supplier_code").value; 
        
        supplier_code = $.trim(supplier_code);

        if(supplier_code.length == 0){
            $('#alert_supplier_code').html('Example : STE0001.');
            $('#alert_supplier_code').removeClass('alert-danger');
            $('#alert_supplier_code').removeClass('alert-success');
        }else{
            $.post("modules/supplier/controllers/checkSupplierBy.php", { supplier_code: supplier_code })
                .done(function(data) {
                    // console.log(data);
                    if(data != null){ 
                        document.getElementById("supplier_code").focus();
                        $('#alert_supplier_code').html('This code : '+supplier_code+' is already in the system.');
                        $('#alert_supplier_code').addClass('alert-danger');
                        $('#alert_supplier_code').removeClass('alert-success');
                    }else{
                        $('#alert_supplier_code').html('Code : '+supplier_code+' can be used.');
                        $('#alert_supplier_code').removeClass('alert-danger');
                        $('#alert_supplier_code').addClass('alert-success');
                    }
            });
        } 
    } 

    function check(){
        
        var supplier_code = document.getElementById("supplier_code").value;
        var supplier_name_th = document.getElementById("supplier_name_th").value;
        var supplier_name_en = document.getElementById("supplier_name_en").value; 
        var supplier_tax = document.getElementById("supplier_tax").value;
        var supplier_address_1 = document.getElementById("supplier_address_1").value;
        var supplier_address_2 = document.getElementById("supplier_address_2").value;
        var supplier_address_3 = document.getElementById("supplier_address_3").value; 
        var supplier_branch = document.getElementById("supplier_branch").value;
        var code_check = document.getElementById("code_check").value;  
       
       
        supplier_code = $.trim(supplier_code);
        supplier_name_th = $.trim(supplier_name_th);
        supplier_name_en = $.trim(supplier_name_en); 
        supplier_tax = $.trim(supplier_tax);
        supplier_address_1 = $.trim(supplier_address_1);
        supplier_address_2 = $.trim(supplier_address_2);
        supplier_address_3 = $.trim(supplier_address_3); 
        supplier_branch = $.trim(supplier_branch); 

        if(supplier_name_th.length == 0){
            alert("Please input Supplier name thai");
            document.getElementById("supplier_name_th").focus();
            return false;
        }else  if(supplier_name_en.length == 0){
            alert("Please input Supplier name english");
            document.getElementById("supplier_name_en").focus();
            return false; 
        }else if(supplier_tax.length == 0){
            alert("Please input Supplier tax");
            document.getElementById("supplier_tax").focus();
            return false;
        }else if(supplier_address_1.length == 0 &&supplier_address_2.length == 0 && supplier_address_3.length == 0 ){
            alert("Please input Supplier address");
            document.getElementById("supplier_address_1").focus();
            return false;
        }else if(supplier_branch.length == 0){
            alert("Please input Supplier branch");
            document.getElementById("supplier_branch").focus();
            return false;
        }else if(supplier_code.length != 0 && $('#alert_supplier_code').hasClass('alert-danger')){
            return false;
        }else{
            return true;
        }



    }

    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }

    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/default.png');
        }
    }
 

</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการผู้ขาย / Supplier Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มผู้ขาย / Add Supplier 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=supplier&action=add" enctype="multipart/form-data">
                    <div class="row">
                    
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสผู้จำหน่าย / Supplier Code</label>
                                <input id="supplier_code" name="supplier_code" class="form-control" onchange="check_supplier_code();" /> 
                                <p id="alert_supplier_code" class="help-block">Example : STE0001.</p>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row"> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ชื่อผู้จำหน่าย (ไทย) / Supplier name (Thai)<font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_name_th" name="supplier_name_th" class="form-control">
                                        <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ชื่อผู้จำหน่าย (อังกฤษ) / Supplier name (Eng.)<font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_name_en" name="supplier_name_en" class="form-control"  >
                                        <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                    </div>
                                </div>
                            </div>    
                            <div class="row"> 
                                <div class="col-lg-4"> 
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax. <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_tax" name="supplier_tax" class="form-control">
                                        <p class="help-block">Example : 123456789012345.</p>
                                    </div> 
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_branch" name="supplier_branch" class="form-control" />
                                        <p class="help-block">Example : 0000 = สำนักงานใหญ่, 0001 = สาขาย่อยที่ 1 .</p>
                                    </div>
                                </div> 
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>โทรศัพท์ / Telephone </label>
                                        <input id="supplier_tel" name="supplier_tel" type="text" class="form-control">
                                        <p class="help-block">Example : 023456789.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>แฟกซ์ / Fax </label>
                                        <input id="supplier_fax" name="supplier_fax" type="text" class="form-control">
                                        <p class="help-block">Example : 020265389-01.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>อีเมล์ / Email </label>
                                        <input id="supplier_email" name="supplier_email" type="email" class="form-control">
                                        <p class="help-block">Example : admin@arno.co.th.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 1 / Address 1 </label>
                                        <input id="supplier_address_1" name="supplier_address_1" type="text" class="form-control">
                                        <p class="help-block">Example : ตึกบางนาธานี.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 2 / Address 2 </label>
                                        <input id="supplier_address_2" name="supplier_address_2" type="text" class="form-control">
                                        <p class="help-block">Example : เขตบางนา.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 3 / Address 3 </label>
                                        <input id="supplier_address_3" name="supplier_address_3" type="text" class="form-control">
                                        <p class="help-block">Example : กรุงเทพ.</p>
                                    </div>
                                </div> 
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รูปผู้ขาย / Supplier Picture </label>
                                        <img class="img-responsive" id="img_logo" src="../upload/default.png" /><br>
                                        <input accept=".jpg , .png"   type="file" id="supplier_logo" name="supplier_logo" onChange="readURL(this);">
                                        <p class="help-block">Example : .jpg or .png </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (nested) -->

                    <hr>

                    <div class="row"> 
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                <select id="vat_type" name="vat_type" class="form-control">
                                    <option value="" >เลือก / Select</option>
                                    <option value="0" >0 - ไม่มี Vat</option>
                                    <option value="1" >1 - รวม Vat</option>
                                    <option value="2" >2 - แยก Vat</option>
                                </select>
                                <p class="help-block">Example : 0 - ไม่มี vat.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ภาษีมูลค่าเพิ่ม / Vat </label>
                                <input id="vat" name="vat" type="text" class="form-control" value="7" style="text-align:right;">
                                <p class="help-block">Example : 7.</p>
                            </div>
                        </div> 
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>เครดิต / Credit Day </label>
                                <input id="credit_day" name="credit_day" type="text" class="form-control"  value="30" style="text-align:right;">
                                <p class="help-block">Example : 30 (วัน).</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เงื่อนไขการชำระเงิน / Pay Type </label>
                                <select id="condition_pay" name="condition_pay" class="form-control">
                                    <option value="">เลือก / Select</option>
                                    <option>เช็ค</option>
                                    <option>เงินสด</option>
                                    <option>โอนเงิน</option>
                                </select>
                                <p class="help-block">Example : เงินสด.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=supplie" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="submit" class="btn btn-success">Save</button>
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