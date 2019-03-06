<script>

     

    function check(){ 

        var project_name = document.getElementById("project_name").value;  
        var project_price_per_rai = document.getElementById("project_price_per_rai").value;   
        var project_description = document.getElementById("project_description").value;   
        
        project_name = $.trim(project_name); 
        project_price_per_rai = $.trim(project_price_per_rai);    
        project_description = $.trim(project_description);    

        if(project_name.length == 0){
            alert("Please input project name");
            document.getElementById("project_name").focus();
            return false;    
        }else if(project_price_per_rai.length == 0 ){
            alert("Please input price");
            document.getElementById("project_price_per_rai").focus();
            return false;    
        }else if(project_description.length == 0 ){
            alert("Please input project description");
            document.getElementById("project_description").focus();
            return false;    
        }else{
            return true;
        } 
    } 

    function check_product(){
        var product_code = document.getElementById("product_code").value; 
        var product_product_amount = document.getElementById("product_product_amount").value;  
         
        product_code = $.trim(product_code); 
        product_product_amount = $.trim(product_product_amount);  

        if(product_code.length == 0){
            alert("Please input product");
            document.getElementById("product_code").focus();
            return false;
        }else if(product_product_amount.length == 0 ){
            alert("Please input amount");
            document.getElementById("product_product_amount").focus();
            return false;  
        }else{
            return true;
        } 
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#project_drawing_url').attr('value', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#project_drawing_url').attr('src', '');
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
        <h1 class="page-header">Project Management</h1>
    </div>
    <div class="col-lg-6" align="right"> 
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขโครงการ / Update Project 
            </div>
            <!-- /.panel-heading --> 
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=project&action=edit" enctype="multipart/form-data">
                    <input type="hidden"  id="project_code" name="project_code" value="<?php echo $project_code ?>" />
                    <input type="hidden"  id="project_logo_o" name="project_logo_o" value="<?php echo $project['project_logo']; ?>" /> 
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row"> 
                                <div class="col-lg-12">
                                    
                                        <div class="form-group">
                                            <label>ชื่อโครงการ / Name. <font color="#F00"><b>*</b></font></label>
                                            <input id="project_name" name="project_name" class="form-control" value="<?PHP echo $project['project_name']?>">
                                            <p class="help-block">Example : VNMG060404EN...</p>
                                        </div>
                                    
                                </div>     
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label>ราคา ( ต่อไร่ ) / Price. <font color="#F00"><b>*</b></font></label>
                                        <input type="number" id="project_price_per_rai" name="project_price_per_rai" class="form-control" value="<?PHP echo $project['project_price_per_rai']?>">
                                        <p class="help-block">Example : 450</p>
                                    </div> 
                                </div>     
                            </div> 
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รายละเอียดโครงการ / Description </label>
                                        <input id="project_description" name="project_description" type="text" class="form-control" value="<?PHP echo $project['project_description']?>">
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
                                        <img class="img-responsive" id="img_logo" src="<?php if($project['project_logo'] != "" ){echo '../upload/project/'.$project['project_logo'];}else{ echo "../upload/default.png";} ?>" />
                                        <input accept=".jpg , .png"   type="file" id="project_logo" name="project_logo" onChange="readURL_logo(this);">
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
                รายการสินค้า (ตัดสต๊อกเมื่อซื้อ) / Product List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <?php if($menu['project']['edit']==1){ ?> 
                <form id="form_target_2" role="form" method="post" onsubmit="return check_product();" 
                <?php if($project_product_code == ""){ ?>
                    action="index.php?app=project&action=add_product&code=<?php echo $project_code?>" 
                <?php }else{ ?>
                    action="index.php?app=project&action=edit_product&code=<?php echo $project_code?>" 
                <?php }?>
                enctype="multipart/form-data">
                <input type="hidden"  id="project_product_code" name="project_product_code" value="<?php echo $project_product_code ?>" />
                
                   <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>สินค้า / Product <font color="#F00"><b>*</b></font></label>
                                <select id="product_code" name="product_code"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($product) ; $i++){
                                    ?>
                                    <option <?if($product[$i]['product_code'] == $project_product['product_code'] ){?> selected <?php } ?> value="<?php echo $product[$i]['product_code'] ?>"><?php echo $product[$i]['product_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : ปุ๋ยเคมี ตราหมี สูตร 15-15-15.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>จำนวน ( กระสอบ ) / Amount <font color="#F00"><b>*</b></font></label>
                                <input id="project_product_amount" name="project_product_amount" type="number" class="form-control" value="<?php echo $project_product['project_product_amount']?>">
                                <p class="help-block">Example : 1.</p>
                            </div>
                        </div> 
                        
                        <!-- /.col-lg-6 (nested) -->
                    </div> 
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=project&action=update&code=<? echo $project_code;?>" class="btn btn-primary" >Reset</a>
                            <button  type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            <?PHP } ?>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="tb-project-customer">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>สินค้า / Product</th>
                            <th>จำนวน ( กระสอบ ) / Amount</th> 
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($project_products); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $project_products[$i]['product_name']; ?></td>
                            <td class="center"><?php echo $project_products[$i]['project_product_amount']; ?></td> 
                            <td> 
                                <a href="?app=project&action=update&code=<?php echo $project_code;?>&project_supplier_code=<?php echo $project_suppliers[$i]['project_supplier_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>   
                                <a href="?app=project&action=delete&code=<?php echo $project_code;?>&project_supplier_code=<?php echo $project_suppliers[$i]['project_supplier_code'];?>" onclick="return confirm('You want to delete supplier : <?php echo $project_suppliers[$i]['supplier_name_en']; ?> (<?php echo $project_suppliers[$i]['supplier_name_th']; ?>)');" style="color:red;">
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
 
 