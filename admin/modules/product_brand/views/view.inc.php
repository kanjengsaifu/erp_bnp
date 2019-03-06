<script>
    function check(){   
        var product_brand_name = document.getElementById("product_brand_name").value;
        var product_brand_detail = document.getElementById("product_brand_detail").value;
       
        product_brand_name = $.trim(product_brand_name);
        product_brand_detail = $.trim(product_brand_detail);
        
       if(product_brand_name.length == 0){
            alert("Please input name");
            document.getElementById("product_brand_name").focus();
            return false;
        }else  if(product_brand_detail.length == 0){
            alert("Please input detail");
            document.getElementById("product_brand_detail").focus();
            return false;
        }else{
            return true;
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
        <a href="?app=product_brand" class="btn btn-primary btn-menu active">ยี่ห้อสินค้า / Product brand</a> 
        <?PHP }?> 
        <?php if($menu['product']['view']==1){ ?> 
        <a href="?app=product" class="btn btn-primary btn-menu ">สินค้า / Product</a> 
        <?PHP }?>  
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        ยี่ห้อสินค้า / Product brand
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($menu['product_brand']['add']==1){ ?> 
                            <form id="form_target" role="form" method="post" onsubmit="return check();" <?php if($product_brand_code == ''){ ?>action="index.php?app=product_brand&action=add"<?php }else{?> action="index.php?app=product_brand&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="product_brand_code" name="product_brand_code" value="<?php echo $product_brand_code?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อยี่ห้อสินค้า / Product brand name<font color="#F00"><b>*</b></font></label>
                                            <input id="product_brand_name" name="product_brand_name"  class="form-control" value="<? echo $product_brand['product_brand_name'];?>">
                                            <p class="help-block">Example : ชิ้น.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>รายละเอียดยี่ห้อสินค้า / Product brand detail <font color="#F00"><b>*</b></font></label>
                                            <input id="product_brand_detail" name="product_brand_detail" class="form-control" value="<? echo $product_brand['product_brand_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=product_brand&action=view" class="btn btn-primary">Reset</a>
                                        <button  type="submit"  class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        <?PHP }?>


                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ<br>No.</th>
                                        <th>รหัส<br>Code</th>
                                        <th>ชื่อยี่ห้อ<br>Brand name</th>
                                        <th>รายละเอียดยี่ห้อ<br>Brand detail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($product_brands); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $product_brands[$i]['product_brand_code']; ?></td>
                                        <td><?php echo $product_brands[$i]['product_brand_name']; ?></td>
                                        <td><?php echo $product_brands[$i]['product_brand_detail']; ?></td>
                                        <td>
                                        <?php if($menu['product_brand']['edit']==1){ ?> 
                                            <a title="Update data" href="?app=product_brand&action=update&code=<?php echo $product_brands[$i]['product_brand_code'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($menu['product_brand']['delete']==1){ ?> 
                                            <a title="Delete data" href="?app=product_brand&action=delete&code=<?php echo $product_brands[$i]['product_brand_code'];?>" onclick="return confirm('You want to delete brand : <?php echo $product_brands[$i]['product_brand_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP } ?>
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
            
            
