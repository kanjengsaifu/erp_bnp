<script>
    function checkAll(id,name,class_name){
        var checkbox = document.getElementsByName(name);

        if (checkbox[0].checked == true ){
            $('input[name="'+name+'"]').prop('checked', true);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="'+class_name+'"]').prop('checked', true);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="view"]').prop('checked', true); 
            if(class_name=="delete"){
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="cancel"]').prop('checked', true);
            }
        }else{
            $('input[name="'+name+'"]').prop('checked', false);
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="'+class_name+'"]').prop('checked', false);
            if(class_name=="view"){
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="add"]').prop('checked', false);
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="edit"]').prop('checked', false);
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="cancel"]').prop('approve', false);
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="cancel"]').prop('checked', false);
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="delete"]').prop('checked', false);
            } 
            if(class_name=="cancel"){
                $(id).closest('table').children('tbody').children('tr').children('td').children('input[class="delete"]').prop('checked', false);
            }
        }
    }

    function checkList(id){
        document.getElementById(id).checked = !(document.getElementById(id).checked); 
    }

    function check(){
        var license_name = document.getElementById("license_name").value; 
        
        license_name = $.trim(license_name); 
        
        if(license_name.length == 0){
            alert("กรุณากรอกสิทธิ์การใช้งาน");
            document.getElementById("license_name").focus();
            return false;
        }else{
            return true;
        }
    }
</script> 

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">จัดการสิทธิ์การใช้งาน / License Management</h1>
    </div>
    <div class="col-lg-6" align="right"> 
        <?php if($menu['user']['view']){ ?> 
        <a href="?app=user" class="btn btn-primary  btn-menu">พนักงาน / Employee</a>
        <?PHP } ?>
        <?php if($menu['license']['view']){ ?> 
        <a href="?app=license" class="btn btn-primary active btn-menu">สิทธิ์การใช้งาน / License</a>
        <?PHP } ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มสิทธิ์การใช้งาน / Add license 
    </div>

    <div class="panel-body">
        <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=license&action=add" >
            <div class="row">  
                <div class="col-lg-3"> 
                    <div class="form-group">
                        <label>ชื่อสิทธิ์การใช้งาน / License Name <font color="#F00"><b>*</b></font></label>
                        <input id="license_name" name="license_name" class="form-control" value="<?PHP echo $license['license_name'];?>">
                        <p class="help-block">Example : ผู้ดูแล.</p>
                    </div> 
                </div> 
            </div> 

            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <h1>สิทธิ์การใช้งาน</h1> 
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" id=" ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>เมนู</th>   
                                <th style="width:90px;" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="view" name="view" onclick="checkAll(this,'view','view')" /> ดู
                                        </label>
                                    </div>
                                </th>   
                                <th style="width:90px" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="add" name="add" onclick="checkAll(this,'add','add')" /> เพิ่ม
                                        </label>
                                    </div> 
                                </th>   
                                <th style="width:90px" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="edit" name="edit" onclick="checkAll(this,'edit','edit')" /> แก้ไข
                                        </label>
                                    </div> 
                                </th>   
                                <th style="width:90px" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="approve" name="approve" onclick="checkAll(this,'approve','approve')"> อนุมัติ
                                        </label>
                                    </div> 
                                </th>
                                <th style="width:120px" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="cancel" name="cancel" onclick="checkAll(this,'cancel','cancel')" /> ลบ(ชั่วคราว)
                                        </label>
                                    </div> 
                                </th>       
                                <th style="width:90px" class="text-center">
                                    <div class="checkbox" style="margin-top:0px;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="all" id="delete" name="delete" onclick="checkAll(this,'delete','delete')" /> ลบ(ถาวร)
                                        </label>
                                    </div> 
                                </th>       
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        for($i=0; $i<count($menus); $i++){
                        ?>
                            <tr> 
                                <td><?php echo $i+1; ?></td> 
                                <td><?php echo $menus[$i]['menu_name']; ?></td>  
                                <td class="text-center"> 
                                    <input class="view" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_view_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_view_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['license_permission_view']==1){ echo 'checked';}?> onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','view');" > 
                                </td> 
                                <td class="text-center"> 
                                    <input class="add" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_add_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_add_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['license_permission_add']==1){ echo 'checked';}?>  onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','add');">  
                                </td> 
                                <td class="text-center"> 
                                    <input class="edit" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_edit_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_edit_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['license_permission_edit']==1){ echo 'checked';}?> onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','edit');">  
                                </td> 
                                <td class="text-center"> 
                                    <input class="approve" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_approve_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_approve_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['permission_approve']){ echo 'checked';}?> onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','approve');">  
                                </td>  
                                <td class="text-center"> 
                                    <input class="cancel" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_cancel_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_cancel_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['license_permission_cancel']==1){ echo 'checked';}?> onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','cancel');">  
                                </td>  
                                <td class="text-center"> 
                                    <input class="delete" style="align:center;width: 20px;height: 20px;" type="checkbox" value="1" id="permission_delete_<?PHP echo $menus[$i]['menu_code'];?>" name="permission_delete_<?PHP echo $menus[$i]['menu_code'];?>" <?PHP if($license_permission['license_permission_delete']==1){ echo 'checked';}?> onclick="oncheck('<?PHP echo $menus[$i]['menu_code'];?>','delete');">  
                                </td>  
                            </tr>
                        <?php 
                        } 
                        ?>
                        </tbody> 
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=license" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function oncheck(id,action) { 
        if(document.getElementById('license_permission_'+action+'_'+id).checked==true){
            document.getElementById('permission_view_'+id).checked = true;
        }
        if(action=='cancel'){ 
            if(document.getElementById('permission_delete_'+id).checked==true){
                document.getElementById('permission_cancel_'+id).checked = false;
                document.getElementById('permission_delete_'+id).checked = false;
            }else{
                if(document.getElementById('permission_cancel_'+id).checked){
                    document.getElementById('permission_cancel_'+id).checked = true; 
                }else{ 
                    document.getElementById('permission_cancel_'+id).checked = false; 
                }
                // document.getElementById('permission_delete_'+id).checked = true; 
            }
        }else if(action=='view'){
            if(document.getElementById('permission_view_'+id).checked==false){
                document.getElementById('permission_add_'+id).checked = false;
                document.getElementById('permission_edit_'+id).checked = false;
                document.getElementById('permission_approve_'+id).checked = false;
                document.getElementById('permission_cancel_'+id).checked = false;
                document.getElementById('permission_delete_'+id).checked = false;
                document.getElementById('permission_view_'+id).checked = false;
            }
        }else if(action=='delete'){ 
            if(document.getElementById('permission_delete_'+id).checked==true){
                document.getElementById('permission_cancel_'+id).checked = true;
                document.getElementById('permission_view_'+id).checked = true;
            }
        }
        // alert(document.getElementById('permission_view_'+id).checked);
    }
</script>