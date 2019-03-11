<script>

     
    function check_product_code(){ 
        
        var product_code = document.getElementById("product_code").value; 
        
        product_code = $.trim(product_code);

        if(product_code.length == 0){
            $('#alert_product_code').html('Example : STE0001.');
            $('#alert_product_code').removeClass('alert-danger');
            $('#alert_product_code').removeClass('alert-success');
        }else{
            $.post("modules/product/controllers/checkProductBy.php", { product_code: product_code })
                .done(function(data) {
                    // console.log(data);
                    if(data != null){ 
                        document.getElementById("product_code").focus();
                        $('#alert_product_code').html('This code : '+product_code+' is already in the system.');
                        $('#alert_product_code').addClass('alert-danger');
                        $('#alert_product_code').removeClass('alert-success');
                    }else{
                        $('#alert_product_code').html('Code : '+product_code+' can be used.');
                        $('#alert_product_code').removeClass('alert-danger');
                        $('#alert_product_code').addClass('alert-success');
                    }
            });
        } 
    } 

    function check(){

 
        var product_name = document.getElementById("product_name").value; 
        var product_price = document.getElementById("product_price").value; 
        var product_description = document.getElementById("product_description").value; 
        var unit_code = document.getElementById("unit_code").value;   
        var product_type_code = document.getElementById("product_type_code").value;   
        var product_brand_code = document.getElementById("product_brand_code").value;   
         
        product_name = $.trim(product_name); 
        product_price = $.trim(product_price); 
        product_description = $.trim(product_description); 
        unit_code = $.trim(unit_code);  
        product_type_code = $.trim(product_type_code);  
        product_brand_code = $.trim(product_brand_code);  

        if(product_name.length == 0){
            alert("Please input product name");
            document.getElementById("product_name").focus();
            return false;
        }else if(product_price.length == 0 ){
            alert("Please input product price ");
            document.getElementById("product_price").focus();
            return false;   
        }else if(unit_code.length == 0 ){
            alert("Please input product unit code");
            document.getElementById("unit_code").focus();
            return false;   
        }else if(product_type_code.length == 0 ){
            alert("Please input product type ");
            document.getElementById("product_type_code").focus();
            return false;   
        }else if(product_brand_code.length == 0 ){
            alert("Please input product brand");
            document.getElementById("product_brand_code").focus();
            return false;    
        }else{
            return true;
        } 
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#product_drawing_url').attr('value', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#product_drawing_url').attr('src', '');
        }
    }

    function readURL_logo(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/customer/default.png');
        }
    }
 

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Product Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['product_type']['view']==1){ ?> 
        <a href="?app=product_type" class="btn btn-primary btn-menu ">ประเภทสินค้า / Product type</a> 
        <?PHP }?> 
        <?php if($menu['product_brand']['view']==1){ ?> 
        <a href="?app=product_brand" class="btn btn-primary btn-menu ">ยี่ห้อสินค้า / Product brand</a> 
        <?PHP }?> 
        <?php if($menu['product']['view']==1){ ?> 
        <a href="?app=product" class="btn btn-primary btn-menu active">สินค้า / Product</a> 
        <?PHP }?>  
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มสินค้า / Add Product 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=product&action=add" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>รหัสผู้จำหน่าย / Supplier Code</label>
                            <input id="product_code" name="product_code" class="form-control" onchange="check_product_code();" /> 
                            <p id="alert_product_code" class="help-block">Example : STE0001.</p>
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-lg-8">

                        <!-- /.row (nested) -->
                        <div class="row">  
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>ประเภทสินค้า / Product type <font color="#F00"><b>*</b></font> </label>
                                        <select id="product_type_code" name="product_type_code" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($product_type) ; $i++){
                                            ?>
                                            <option value="<?php echo $product_type[$i]['product_type_code'] ?>"><?php echo $product_type[$i]['product_type_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : เคมี.</p>
                                </div>
                            </div>  
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>ยี่ห้อสินค้า / Product brand <font color="#F00"><b>*</b></font> </label>
                                        <select id="product_brand_code" name="product_brand_code" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($product_brand) ; $i++){
                                            ?>
                                            <option value="<?php echo $product_brand[$i]['product_brand_code'] ?>"><?php echo $product_brand[$i]['product_brand_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : ตราหมี.</p>
                                </div>
                            </div>  
                        </div>  
                        <div class="row"> 
                            <div class="col-lg-6">
                                
                                    <div class="form-group">
                                        <label>ชื่อสินค้า / Name. <font color="#F00"><b>*</b></font></label>
                                        <input id="product_name" name="product_name" class="form-control">
                                        <p class="help-block">Example : VNMG060404EN...</p>
                                    </div>
                                
                            </div>    
                            <div class="col-lg-6">
                                
                                    <div class="form-group">
                                        <label>ราคา / Price. <font color="#F00"><b>*</b></font></label>
                                        <input id="product_price" name="product_price" class="form-control">
                                        <p class="help-block">Example : 450</p>
                                    </div>
                                
                            </div>  
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>หน่วยสินค้า / Product Unit <font color="#F00"><b>*</b></font> </label>
                                        <select id="unit_code" name="unit_code" class="form-control">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($unit) ; $i++){
                                            ?>
                                            <option value="<?php echo $unit[$i]['unit_code'] ?>"><?php echo $unit[$i]['unit_name'] ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : กระสอบ.</p>
                                </div>
                            </div>     
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดสินค้า / Description </label>
                                    <input id="product_description" name="product_description" type="text" class="form-control">
                                    <p class="help-block">Example : Description...</p>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปสินค้า / Product Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/default.png" />
                                    <input accept=".jpg , .png"   type="file" id="product_logo" name="product_logo" onChange="readURL_logo(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="submit"  class="btn btn-success">Save</button>
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