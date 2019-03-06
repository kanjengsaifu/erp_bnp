<script>

    function check_code(){
        var code = $('#material_code_first').val() + $('#material_code').val();
        $.post( "controllers/getMaterialByCode.php", { 'material_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("material_code").focus();
                $("#code_check").val(data.material_id);
                
            } else{
                $("#code_check").val("");
            }
        });
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
                เพิ่มวัตถุดิบ / Add Material 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=material&action=add" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row"> 
                            <div class="col-lg-6">
                                
                                    <div class="form-group">
                                        <label>ชื่อวัตถุดิบ / Name. <font color="#F00"><b>*</b></font></label>
                                        <input id="material_name" name="material_name" class="form-control">
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
                                            <option  value="<?php echo $material_type[$i]['material_type_id'] ?>"><?php echo $material_type[$i]['material_type_name'] ?></option>
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
                                    <input type="number" id="material_quantity_per_unit" name="material_quantity_per_unit" class="form-control"   onkeyup="comma(this)">
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
                                            <option value="<?php echo $unit[$i]['unit_code'] ?>"><?php echo $unit[$i]['unit_name'] ?></option>
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
                                    <input id="material_minimum_stock" name="material_minimum_stock" type="number" class="form-control" value="">
                                    <p class="help-block">Example : 50.</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Maximum Stock <font color="#F00"><b>*</b></font></label>
                                    <input id="material_maximum_stock" name="material_maximum_stock" type="number" class="form-control" value="">
                                    <p class="help-block">Example : 100.</p>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รายละเอียดวัตถุดิบ / Description </label>
                                    <input id="material_description" name="material_description" type="text" class="form-control">
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
                                    <img class="img-responsive" id="img_logo" src="../upload/default.png" />
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