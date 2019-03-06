<script>

     

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

    
    function check_supplier(){
        var supplier_code = document.getElementById("supplier_code").value; 
        var material_supplier_buyprice = document.getElementById("material_supplier_buyprice").value; 
        var material_supplier_lead_time = document.getElementById("material_supplier_lead_time").value; 
         
        supplier_code = $.trim(supplier_code); 
        material_supplier_buyprice = $.trim(material_supplier_buyprice); 
        material_supplier_lead_time = $.trim(material_supplier_lead_time); 

        if(supplier_code.length == 0){
            alert("Please input supplier");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(material_supplier_buyprice.length == 0 ){
            alert("Please input price");
            document.getElementById("material_supplier_buyprice").focus();
            return false; 
        }else if(material_supplier_lead_time.length == 0){
            alert("Please input lead time");
            document.getElementById("material_supplier_lead_time").focus();
            return false;
        }else{
            return true;
        } 
    }
    
    function check_material(){
        var material_code = document.getElementById("material_code").value; 
        var product_material_amount = document.getElementById("product_material_amount").value;  
         
        material_code = $.trim(material_code); 
        product_material_amount = $.trim(product_material_amount);  

        if(material_code.length == 0){
            alert("Please input material");
            document.getElementById("material_code").focus();
            return false;
        }else if(product_material_amount.length == 0 ){
            alert("Please input amount");
            document.getElementById("product_material_amount").focus();
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
                แก้ไขสินค้า / Update Product 
            </div>
            <!-- /.panel-heading --> 
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=product&action=edit" enctype="multipart/form-data">
                    <input type="hidden"  id="product_code" name="product_code" value="<?php echo $product_code ?>" />
                    <input type="hidden"  id="product_logo_o" name="product_logo_o" value="<?php echo $product['product_logo']; ?>" /> 
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
                                                    <option  <?php if($product_type[$i]['product_type_code'] == $product['product_type_code'] ){?> selected <?php } ?>   value="<?php echo $product_type[$i]['product_type_code'] ?>"><?php echo $product_type[$i]['product_type_name'] ?></option>
                                                    <?
                                                }
                                                ?>
                                            </select>
                                        <p class="help-block">Example : กระสอบ.</p>
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
                                                    <option <?php if($product_brand[$i]['product_brand_code'] == $product['product_brand_code'] ){?> selected <?php } ?> value="<?php echo $product_brand[$i]['product_brand_code'] ?>"><?php echo $product_brand[$i]['product_brand_name'] ?></option>
                                                    <?
                                                }
                                                ?>
                                            </select>
                                        <p class="help-block">Example : กระสอบ.</p>
                                    </div>
                                </div>   
                            </div>
                            <div class="row"> 
                                <div class="col-lg-6">
                                    
                                        <div class="form-group">
                                            <label>ชื่อสินค้า / Name. <font color="#F00"><b>*</b></font></label>
                                            <input id="product_name" name="product_name" class="form-control" value="<?PHP echo $product['product_name'];?>">
                                            <p class="help-block">Example : VNMG060404EN...</p>
                                        </div>
                                    
                                </div>     
                                <div class="col-lg-6">
                                    
                                        <div class="form-group">
                                            <label>ราคา / Price. <font color="#F00"><b>*</b></font></label>
                                            <input id="product_price" name="product_price" class="form-control" value="<?PHP echo $product['product_price'];?>">
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
                                                <option <?php if($unit[$i]['unit_code'] == $product['unit_code'] ){?> selected <?php } ?> value="<?php echo $unit[$i]['unit_code'] ?>"><?php echo $unit[$i]['unit_name'] ?></option>
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
                                        <img class="img-responsive" id="img_logo" src="<?php if($product['product_logo'] != "" ){echo '../upload/product/'.$product['product_logo'];}else{ echo "../upload/default.png";} ?>" />
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

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                รายการวัตถุดิบ (ตัดสต๊อกเมื่อซื้อ) / Material List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <?php if($menu['product']['edit']==1){ ?> 
                <form id="form_target_2" role="form" method="post" onsubmit="return check_material();" 
                <?php if($product_material_code == ""){ ?>
                    action="index.php?app=product&action=add_material&code=<?php echo $product_code?>" 
                <?php }else{ ?>
                    action="index.php?app=product&action=edit_material&code=<?php echo $product_code?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="product_material_code" name="product_material_code" value="<?php echo $product_material_code ?>" />
                
                   <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วัตถุดิบ / Material <font color="#F00"><b>*</b></font></label>
                                <select id="material_code" name="material_code"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($material) ; $i++){
                                    ?>
                                    <option <?if($material[$i]['material_code'] == $product_material['material_code'] ){?> selected <?php } ?> value="<?php echo $material[$i]['material_code'] ?>"><?php echo $material[$i]['material_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : กระสอบ.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>จำนวน ( กระสอบ ) / Amount <font color="#F00"><b>*</b></font></label>
                                <input id="product_material_amount" name="product_material_amount" type="number" class="form-control" value="<?php echo $product_material['product_material_amount']?>">
                                <p class="help-block">Example : 1.</p>
                            </div>
                        </div> 
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div> 
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&code=<? echo $product_code;?>" class="btn btn-primary" >Reset</a>
                            <button  type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            <?PHP } ?>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="tb-product-customer">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>วัตถุดิบ / Material</th>
                            <th>จำนวน ( กระสอบ ) / Amount</th> 
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($product_materials); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $product_materials[$i]['material_name']; ?></td>
                            <td class="center"><?php echo $product_materials[$i]['product_material_amount']; ?></td> 
                            <td> 
                                <a href="?app=product&action=update&code=<?php echo $product_code;?>&product_material_code=<?php echo $product_materials[$i]['product_material_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>   
                                <a href="?app=product&action=delete&code=<?php echo $product_code;?>&product_material_code=<?php echo $product_materials[$i]['product_material_code'];?>" onclick="return confirm('You want to delete material : <?php echo $product_materials[$i]['material_name_en']; ?> (<?php echo $product_materials[$i]['material_name_th']; ?>)');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a> 
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
                    
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
 

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                รายการผู้ขาย / Supplier List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <?php if($menu['product']['edit']==1){ ?> 
                <form id="form_target_1" role="form" method="post" onsubmit="return check_supplier();" 
                <?php if($product_supplier_code == ""){ ?>
                    action="index.php?app=product&action=add_supplier&code=<?php echo $product_code?>" 
                <?php }else{ ?>
                    action="index.php?app=product&action=edit_supplier&code=<?php echo $product_code?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="product_supplier_code" name="product_supplier_code" value="<?php echo $product_supplier_code ?>" />
                   
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ชื่อผู้ขาย / Supplier name <font color="#F00"><b>*</b></font></label>
                                <select id="supplier_code" name="supplier_code"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($supplier) ; $i++){
                                    ?>
                                    <option <?if($supplier[$i]['supplier_code'] == $product_supplier['supplier_code'] ){?> selected <?php } ?> value="<?php echo $supplier[$i]['supplier_code'] ?>"><?php echo $supplier[$i]['supplier_name_en'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                            </div>
                        </div>
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div>

                     <!-- /.row (nested) -->
                     <div class="row">
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ราคา ( บาท ) / Price <font color="#F00"><b>*</b></font></label>
                                <input id="product_supplier_buyprice" name="product_supplier_buyprice" type="text" class="form-control" value="<?php echo $product_supplier['product_supplier_buyprice']?>">
                                <p class="help-block">Example : 120.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ระยะเวลาขนส่ง ( วัน ) / Lead Time <font color="#F00"><b>*</b></font></label>
                                <input id="product_supplier_lead_time" name="product_supplier_lead_time" type="text" class="form-control" value="<?php echo $product_supplier['product_supplier_lead_time']?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <!-- <div class="col-lg-4">
                            <div class="form-group">
                                <label>Supplier Status  <font color="#F00"><b>*</b></font></label>
                                <select id="product_supplier_status" name="product_supplier_status" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if($product_supplier['product_supplier_status'] == 'Active'){?> selected <?php } ?> >Active</option>
                                    <option <?php if($product_supplier['product_supplier_status'] == 'Inactive'){?> selected <?php } ?> >Inactive</option>
                                </select>
                                <p class="help-block">Example : Active.</p>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&code=<? echo $product_code;?>" class="btn btn-primary" >Reset</a>
                            <button  type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            <?PHP } ?>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="tb-product-customer">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Lead Time</th>
                            <!-- <th>Status</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($product_suppliers); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $product_suppliers[$i]['supplier_name_en']; ?></td>
                            <td class="center"><?php echo $product_suppliers[$i]['product_supplier_buyprice']; ?></td>
                            <td class="center"><?php echo $product_suppliers[$i]['product_supplier_lead_time']; ?></td>
                            <!-- <td class="center"><?php echo $product_suppliers[$i]['product_supplier_status']; ?></td> -->
                            <td> 
                                <a href="?app=product&action=update&code=<?php echo $product_code;?>&product_supplier_code=<?php echo $product_suppliers[$i]['product_supplier_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>   
                                <a href="?app=product&action=delete&code=<?php echo $product_code;?>&product_supplier_code=<?php echo $product_suppliers[$i]['product_supplier_code'];?>" onclick="return confirm('You want to delete supplier : <?php echo $product_suppliers[$i]['supplier_name_en']; ?> (<?php echo $product_suppliers[$i]['supplier_name_th']; ?>)');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a> 
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
                    
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>