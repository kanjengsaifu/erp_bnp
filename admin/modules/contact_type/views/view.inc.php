<script>
    function check(){   
        var contact_type_name = document.getElementById("contact_type_name").value;
        var contact_type_detail = document.getElementById("contact_type_detail").value;
       
        contact_type_name = $.trim(contact_type_name);
        contact_type_detail = $.trim(contact_type_detail);
        
       if(contact_type_name.length == 0){
            alert("Please input name");
            document.getElementById("contact_type_name").focus();
            return false;
        }else  if(contact_type_detail.length == 0){
            alert("Please input detail");
            document.getElementById("contact_type_detail").focus();
            return false;
        }else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Satisfaction Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['contact_way']['view']==1){ ?> 
        <a href="?app=contact_way" class="btn btn-primary btn-menu ">ช่องทางติดต่อ / Contact way</a>
        <?PHP }?>
        <?php if($menu['contact_type']['view']==1){ ?> 
        <a href="?app=contact_type" class="btn btn-primary btn-menu active">ประเภทการติดต่อ / Contact type</a>
        <?PHP }?>
        <?php if($menu['satisfaction']['view']==1){ ?> 
        <a href="?app=satisfaction" class="btn btn-primary btn-menu ">ความพึงพอใจ / Satisfaction</a> 
        <?PHP }?>
    </div>
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        ประเภทการติดต่อ / Contact type
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if($menu['contact_type']['add']==1){ ?> 
                            <form id="form_target" role="form" method="post" onsubmit="return check();" <?php if($contact_type_code == ''){ ?>action="index.php?app=contact_type&action=add"<?php }else{?> action="index.php?app=contact_type&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="contact_type_code" name="contact_type_code" value="<?php echo $contact_type_code?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>ชื่อประเภทการติดต่อ / Contact type name<font color="#F00"><b>*</b></font></label>
                                            <input id="contact_type_name" name="contact_type_name"  class="form-control" value="<? echo $contact_type['contact_type_name'];?>">
                                            <p class="help-block">Example : ชิ้น.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>รายละเอียดประเภทการติดต่อ / Contact type detail <font color="#F00"><b>*</b></font></label>
                                            <input id="contact_type_detail" name="contact_type_detail" class="form-control" value="<? echo $contact_type['contact_type_detail'];?>">
                                            <p class="help-block">Example : -.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=contact_type&action=view" class="btn btn-primary">Reset</a>
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
                                    for($i=0; $i < count($contact_types); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $contact_types[$i]['contact_type_code']; ?></td>
                                        <td><?php echo $contact_types[$i]['contact_type_name']; ?></td>
                                        <td><?php echo $contact_types[$i]['contact_type_detail']; ?></td>
                                        <td>
                                        <?php if($menu['contact_type']['edit']==1){ ?> 
                                            <a title="Update data" href="?app=contact_type&action=update&code=<?php echo $contact_types[$i]['contact_type_code'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($menu['contact_type']['delete']==1){ ?> 
                                            <a title="Delete data" href="?app=contact_type&action=delete&code=<?php echo $contact_types[$i]['contact_type_code'];?>" onclick="return confirm('You want to delete contact way : <?php echo $contact_types[$i]['contact_type_name']; ?>');" style="color:red;">
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
            
            
