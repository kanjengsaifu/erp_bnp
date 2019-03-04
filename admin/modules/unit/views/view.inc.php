<script>
    function check(){   
        var unit_name = document.getElementById("unit_name").value;
        var unit_detail = document.getElementById("unit_detail").value;
       
        unit_name = $.trim(unit_name);
        unit_detail = $.trim(unit_detail);
        
       if(unit_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("unit_name").focus();
            return false;
        }else  if(unit_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("unit_detail").focus();
            return false;
        }else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Material Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['material']['view']==1){ ?> 
        <a href="?app=material" class="btn btn-primary btn-menu ">วัตถุดิบ / Material</a> 
        <?PHP }?>
        <?php if($menu['material_type']['view']==1){ ?> 
        <a href="?app=material_type" class="btn btn-primary btn-menu ">ประเภท / Type</a> 
        <?PHP }?>
        <?php if($menu['unit']['view']==1){ ?> 
        <a href="?app=unit" class="btn btn-primary btn-menu active">หน่วย / Unit</a>
        <?PHP }?>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            หน่วยวัตถุดิบ / Material Unit
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($menu['unit']['add']==1){ ?> 
                            <form id="form_target" role="form" method="post" onsubmit="return check();" <?php if($unit_code == ''){ ?>action="index.php?app=unit&action=add"<?php }else{?> action="index.php?app=unit&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="unit_code" name="unit_code" value="<?php echo $unit_code?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อหน่วยวัตถุดิบ / Unit name<font color="#F00"><b>*</b></font></label>
                                            <input id="unit_name" name="unit_name"  class="form-control" value="<? echo $unit['unit_name'];?>">
                                            <p class="help-block">Example : ชิ้น.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>รายละเอียดหน่วยวัตถุดิบ / Unit detail <font color="#F00"><b>*</b></font></label>
                                            <input id="unit_detail" name="unit_detail" class="form-control" value="<? echo $unit['unit_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=unit&action=view" class="btn btn-primary">Reset</a>
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
                                        <th>ชื่อหน่วยวัตถุดิบ<br>Unit name</th>
                                        <th>รายละเอียดหน่วยวัตถุดิบ<br>Unit detail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($units); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $units[$i]['unit_code']; ?></td>
                                        <td><?php echo $units[$i]['unit_name']; ?></td>
                                        <td><?php echo $units[$i]['unit_detail']; ?></td>
                                        <td>
                                        <?php if($menu['unit']['edit']==1){ ?> 
                                            <a title="Update data" href="?app=unit&action=update&code=<?php echo $units[$i]['unit_code'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($menu['unit']['delete']==1){ ?> 
                                            <a title="Delete data" href="?app=unit&action=delete&code=<?php echo $units[$i]['unit_code'];?>" onclick="return confirm('You want to delete unit : <?php echo $units[$i]['unit_name']; ?>');" style="color:red;">
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
            
            
