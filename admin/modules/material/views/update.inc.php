<script>

    function check_code(){
        var code = $('#material_code_first').val() + $('#material_code').val();
        $.post( "controllers/getMaterialByCode.php", { 'material_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("material_code").focus();
                $("#code_check").val(data.material_code);
                
            } else{
                $("#code_check").val("");
            }
        });
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

    function check(){

 
        var material_name = document.getElementById("material_name").value; 
        var material_description = document.getElementById("material_description").value; 
        var unit_code = document.getElementById("unit_code").value;
        // var material_quantity_per_unit = document.getElementById("material_quantity_per_unit").value;  
        var material_minimum_stock = document.getElementById("material_minimum_stock").value;  
        var material_maximum_stock = document.getElementById("material_maximum_stock").value;  
        
        material_name = $.trim(material_name); 
        material_description = $.trim(material_description); 
        unit_code = $.trim(unit_code);
        // material_quantity_per_unit = $.trim(material_quantity_per_unit); 
        material_minimum_stock = $.trim(material_minimum_stock); 
        material_maximum_stock = $.trim(material_maximum_stock); 

        if(material_name.length == 0){
            alert("Please input material name");
            document.getElementById("material_name").focus();
            return false;
        }else if(unit_code.length == 0 ){
            alert("Please input material unit code");
            document.getElementById("unit_code").focus();
            return false;
        // }else if(material_quantity_per_unit.length == 0){
        //     alert("Please input material quantity per unit");
        //     document.getElementById("material_quantity_per_unit").focus();
        //     return false;
        }else if(material_minimum_stock.length == 0){
            alert("Please input minimum stock");
            document.getElementById("material_minimum_stock").focus();
            return false;
        }else if(material_maximum_stock.length == 0){
            alert("Please input maximum stock");
            document.getElementById("material_maximum_stock").focus();
            return false;
        }else if(material_description.length == 0){
            alert("Please input material description");
            document.getElementById("material_description").focus();
            return false;
        }else{
            return true;
        } 
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#material_drawing_url').attr('value', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#material_drawing_url').attr('src', '');
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

    function update_code(){
        var material_type = document.getElementById("material_type").value;
        material_type = $.trim(material_type);
        if(material_type.length > 0){
            $.post( "controllers/getFirstChar.php", { 'material_type_name': material_type }, function( data ) {
                document.getElementById("material_code_first").value =  data;
                check_code();
            });
            
        }else{
            document.getElementById("material_code_first").value =  "";
            check_code();
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Material Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['material']['view']==1){ ?> 
        <a href="?app=material" class="btn btn-primary btn-menu active">วัตถุดิบ / Material</a> 
        <?PHP }?>
        <?php if($menu['material_type']['view']==1){ ?> 
        <a href="?app=material_type" class="btn btn-primary btn-menu">ประเภท / Type</a> 
        <?PHP }?>
        <?php if($menu['unit']['view']==1){ ?> 
        <a href="?app=unit" class="btn btn-primary btn-menu ">หน่วย / Unit</a>
        <?PHP }?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขวัตถุดิบ / Update Material 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=material&action=edit" enctype="multipart/form-data">
                <input type="hidden"  id="material_code" name="material_code" value="<?php echo $material_code ?>" />
                <input type="hidden"  id="material_logo_o" name="material_logo_o" value="<?php echo $material['material_logo']; ?>" /> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row"> 
                            <div class="col-lg-6">
                                
                                <div class="form-group">
                                    <label>ชื่อวัตถุดิบ / Name. <font color="#F00"><b>*</b></font></label>
                                    <input id="material_name" name="material_name" class="form-control" value="<?PHP echo $material['material_name'];?>">
                                    <p class="help-block">Example : VNMG060404EN...</p>
                                </div>
                                
                            </div>    
                            
                            <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ประเภทวัตถุดิบ / Material Type <font color="#F00"><b>*</b></font> </label>
                                    <select id="material_type" name="material_type" class="form-control" onChange="update_code()">
                                            <option value="">Select</option>
                                            <?php 
                                                for($i =  0 ; $i < count($material_type) ; $i++){
                                            ?>
                                            <option  value="<?php echo $material_type[$i]['material_type_code'] ?>"><?php echo $material_type[$i]['material_type_name']; ?></option>
                                            <?
                                                }
                                            ?>
                                        </select>
                                    <p class="help-block">Example : Special Tool.</p>
                                </div>
                            </div> -->
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row"> 
                            <!-- <div class="col-lg-6">
                                
                                <div class="form-group">
                                    <label>ปริมาณต่อหน่วย / Quantity Per Unit <font color="#F00"><b>*</b></font></label>
                                    <input type="number" id="material_quantity_per_unit" name="material_quantity_per_unit" class="form-control"  value="<?php echo number_format($material['material_quantity_per_unit'],0, '.', ','); ?>"  onkeyup="comma(this)">
                                    <p class="help-block">Example : 500</p>
                                </div>
                                
                            </div>  -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>หน่วยวัตถุดิบ / Material Unit <font color="#F00"><b>*</b></font> </label>
                                    <select id="unit_code" name="unit_code" class="form-control">
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($unit) ; $i++){
                                        ?>
                                        <option <?php if($unit[$i]['unit_code'] == $material['unit_code'] ){?> selected <?php } ?> value="<?php echo $unit[$i]['unit_code'] ?>"><?php echo $unit[$i]['unit_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block">Example : ลิตร.</p>
                                </div>
                            </div> 
                        </div> 
                        <div class="row">  
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Minimum Stock <font color="#F00"><b>*</b></font></label>
                                    <input id="material_minimum_stock" name="material_minimum_stock" type="number" class="form-control" value="<?PHP echo $material['material_minimum_stock'];?>">
                                    <p class="help-block">Example : 50.</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Maximum Stock <font color="#F00"><b>*</b></font></label>
                                    <input id="material_maximum_stock" name="material_maximum_stock" type="number" class="form-control" value="<?PHP echo $material['material_maximum_stock'];?>">
                                    <p class="help-block">Example : 100.</p>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดวัตถุดิบ / Description </label>
                                    <input id="material_description" name="material_description" type="text" class="form-control" value="<?PHP echo $material['material_description'];?>">
                                    <p class="help-block">Example : Description...</p>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปวัตถุดิบ / Material Picture </label>
                                    <img class="img-responsive" id="img_logo" src="<?php if($material['material_logo'] != "" ){echo '../upload/material/'.$material['material_logo'];}else{ echo "../upload/default.png";} ?>" />
                                    <input accept=".jpg , .png"   type="file" id="material_logo" name="material_logo" onChange="readURL_logo(this);">
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
                รายการผู้ขาย / Supplier List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <?php if($menu['material']['edit']==1){ ?> 
                <form id="form_target_1" role="form" method="post" onsubmit="return check_supplier();" 
                <?php if($material_supplier_code == ""){ ?>
                    action="index.php?app=material&action=add_supplier&code=<?php echo $material_code?>" 
                <?php }else{ ?>
                    action="index.php?app=material&action=edit_supplier&code=<?php echo $material_code?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="material_supplier_code" name="material_supplier_code" value="<?php echo $material_supplier_code ?>" />
                   
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>ชื่อผู้ขาย / Supplier name <font color="#F00"><b>*</b></font></label>
                                <select id="supplier_code" name="supplier_code"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($supplier) ; $i++){
                                    ?>
                                    <option <?if($supplier[$i]['supplier_code'] == $material_supplier['supplier_code'] ){?> selected <?php } ?> value="<?php echo $supplier[$i]['supplier_code'] ?>"><?php echo $supplier[$i]['supplier_name_en'] ?> </option>
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
                                <input id="material_supplier_buyprice" name="material_supplier_buyprice" type="text" class="form-control" value="<?php echo $material_supplier['material_supplier_buyprice']?>">
                                <p class="help-block">Example : 120.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ระยะเวลาขนส่ง ( วัน ) / Lead Time <font color="#F00"><b>*</b></font></label>
                                <input id="material_supplier_lead_time" name="material_supplier_lead_time" type="text" class="form-control" value="<?php echo $material_supplier['material_supplier_lead_time']?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <!-- <div class="col-lg-4">
                            <div class="form-group">
                                <label>Supplier Status  <font color="#F00"><b>*</b></font></label>
                                <select id="material_supplier_status" name="material_supplier_status" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if($material_supplier['material_supplier_status'] == 'Active'){?> selected <?php } ?> >Active</option>
                                    <option <?php if($material_supplier['material_supplier_status'] == 'Inactive'){?> selected <?php } ?> >Inactive</option>
                                </select>
                                <p class="help-block">Example : Active.</p>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=material&action=update&code=<? echo $material_code;?>" class="btn btn-primary" >Reset</a>
                            <button  type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            <?PHP } ?>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="tb-material-customer">
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
                        for($i=0; $i < count($material_suppliers); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $material_suppliers[$i]['supplier_name_en']; ?></td>
                            <td class="center"><?php echo $material_suppliers[$i]['material_supplier_buyprice']; ?></td>
                            <td class="center"><?php echo $material_suppliers[$i]['material_supplier_lead_time']; ?></td>
                            <!-- <td class="center"><?php echo $material_suppliers[$i]['material_supplier_status']; ?></td> -->
                            <td> 
                                <a href="?app=material&action=update&code=<?php echo $material_code;?>&material_supplier_code=<?php echo $material_suppliers[$i]['material_supplier_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>   
                                <a href="?app=material&action=delete&code=<?php echo $material_code;?>&material_supplier_code=<?php echo $material_suppliers[$i]['material_supplier_code'];?>" onclick="return confirm('You want to delete supplier : <?php echo $material_suppliers[$i]['supplier_name_en']; ?> (<?php echo $material_suppliers[$i]['supplier_name_th']; ?>)');" style="color:red;">
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
 