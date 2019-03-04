
<script>

function check_code(id){
    var code = $(id).val();
    $.post( "controllers/getUserByCode.php", { 'user_code': code }, function( data ) {  
        if(data != null){ 
            alert("This "+code+" is already in the system.");
            document.getElementById("user_code").focus();
            $("#code_check").val(data.user_code);
            
        } else{
            $("#code_check").val("");
        }
    });
}

    function check(){

 
        var user_prefix = document.getElementById("user_prefix").value;
        var user_name = document.getElementById("user_name").value;
        var user_lastname = document.getElementById("user_lastname").value;
        var user_mobile = document.getElementById("user_mobile").value;
        var user_email = document.getElementById("user_email").value;
        var user_username = document.getElementById("user_username").value;
        var user_password = document.getElementById("user_password").value;
        var user_address = document.getElementById("user_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var user_zipcode = document.getElementById("user_zipcode").value;
        var user_position_code = document.getElementById("user_position_code").value;
        var license_code = document.getElementById("license_code").value;
        var user_status_code = document.getElementById("user_status_code").value;  

        user_prefix = $.trim(user_prefix);
        user_name = $.trim(user_name);
        user_lastname = $.trim(user_lastname);
        user_mobile = $.trim(user_mobile);
        user_email = $.trim(user_email);
        user_username = $.trim(user_username);
        user_password = $.trim(user_password);
        user_address = $.trim(user_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        user_zipcode = $.trim(user_zipcode);
        user_position_code = $.trim(user_position_code);
        license_code = $.trim(license_code);
        user_status_code = $.trim(user_status_code); 

        

        if(user_prefix.length == 0){
            alert("Please input employee prefix");
            document.getElementById("user_prefix").focus();
            return false;
        }else if(user_name.length == 0){
            alert("Please input employee name");
            document.getElementById("user_name").focus();
            return false;
        }else if(user_lastname.length == 0){
            alert("Please input employee lastname");
            document.getElementById("user_lastname").focus();
            return false;
        }else if(user_username.length == 0){
            alert("Please input employee username");
            document.getElementById("user_username").focus();
            return false;
        }else if(user_password.length == 0){
            alert("Please input employee password");
            document.getElementById("user_password").focus();
            return false;
        }else if(user_address.length == 0){
            alert("Please input employee address");
            document.getElementById("user_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input employee provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input employee amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input employee district");
            document.getElementById("district_id").focus();
            return false;
        }else if(user_position_code.length == 0){
            alert("Please input employee position");
            document.getElementById("user_position_code").focus();
            return false;
        }else if(license_code.length == 0){
            alert("Please input employee license");
            document.getElementById("license_code").focus();
            return false;
        }else if(user_status_code.length == 0){
            alert("Please input employee status");
            document.getElementById("user_status_code").focus();
            return false; 
        }else{ 
            return true;
        }



    }

function getAmphur(){
    
    var province_id = document.getElementById("province_id").value;
    $.post( "controllers/getAmphur.php", { 'province': province_id }, function( data ) {
        document.getElementById("amphur_id").innerHTML = data;
        $("#amphur_id").selectpicker('refresh');
    });

    
    
}

function getDistrict(){
    var amphur_id = document.getElementById("amphur_id").value;
    $.post( "controllers/getDistrict.php", { 'amphur': amphur_id }, function( data ) {
        document.getElementById("district_id").innerHTML = data;
        $("#district_id").selectpicker('refresh');
    });

    $.post( "controllers/getZipcode.php", { 'amphur': amphur_id }, function( data ) {
        document.getElementById("user_zipcode").value = data;
    });
}





</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">จัดการพนักงาน / Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
    
        <?php if($menu['user']['view']==1){?> 
        <a href="?app=user" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <?PHP } ?>
        <?php if($menu['license']['view']==1){?> 
        <a href="?app=license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a>
        <?PHP } ?>
    
    </div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            แก้ไขพนักงาน / Edit employee 
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=user&action=edit" >
                <input type="hidden"  id="user_code" name="user_code" value="<?php echo $user_code ?>" />
                <div class="row"> 
                    <div class="col-lg-3">
                        
                            <div class="form-group">
                                <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                                <select id="user_prefix" name="user_prefix" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if($user['user_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                                    <option <?php if($user['user_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                                    <option <?php if($user['user_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                                </select>
                                <p class="help-block">Example : นาย.</p>
                            </div>
                        
                    </div>
                    <div class="col-lg-3">
                        
                            <div class="form-group">
                                <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                                <input id="user_name" name="user_name" class="form-control" value="<?php echo $user['user_name']?>">
                                <p class="help-block">Example : วินัย.</p>
                            </div>
                        
                    </div>
                    <div class="col-lg-3">
                        
                            <div class="form-group">
                                <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                                <input id="user_lastname" name="user_lastname" class="form-control" value="<?php echo $user['user_lastname']?>">
                                <p class="help-block">Example : ชาญชัย.</p>
                            </div>
                    </div>
                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>อีเมล์ / Email </label>
                            <input id="user_email" name="user_email" type="email" class="form-control" value="<?php echo $user['user_email']?>">
                            <p class="help-block">Example : admin@arno.co.th.</p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        
                            <div class="form-group">
                                <label>โทรศัพท์ / Mobile </label>
                                <input id="user_mobile" name="user_mobile" type="text" class="form-control" value="<?php echo $user['user_mobile']?>">
                                <p class="help-block">Example : 0610243003.</p>
                            </div>
                        
                    </div>
                    <div class="col-lg-3">
                        
                            <div class="form-group">
                                <label>ยูสเซอร์ / Username <font color="#F00"><b>*</b></font></label>
                                <input id="user_username" name="user_username" class="form-control" value="<?php echo $user['user_username']?>">
                                <p class="help-block">Example : thana.</p>
                            </div>
                        
                    </div>
                    <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสผ่าน / Password <font color="#F00"><b>*</b></font></label>
                                <input id="user_password" name="user_password" type="password" class="form-control" value="<?php echo $user['user_password']?>">
                                <p class="help-block">Example : thanaadmin.</p>
                            </div>
                    </div>
                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                            <input type="text" id="user_address" name="user_address" class="form-control" value="<?php echo $user['user_address']?>">
                            <p class="help-block">Example : 271/55.</p>
                        </div>
                    </div>
                    
                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>จังหวัด / Province <font color="#F00"><b>*</b></font> </label>
                            <select id="province_id" name="province_id" class="form-control" onchange="getAmphur()">
                                <option value="">Select</option>
                                <?php 
                                for($i =  0 ; $i < count($add_province) ; $i++){
                                ?>
                                <option <?php if($user['province_id'] == $add_province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $add_province[$i]['PROVINCE_ID'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : นครราชสีมา.</p>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>อำเภอ / Amphur <font color="#F00"><b>*</b></font> </label>
                            <select id="amphur_id" name="amphur_id"  class="form-control" onchange="getDistrict()">
                            <option value="">Select</option>
                            <?php 
                                for($i =  0 ; $i < count($add_amphur) ; $i++){
                                ?>
                                <option <?php if($user['amphur_id'] == $add_amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $add_amphur[$i]['AMPHUR_ID'] ?>"><?php echo $add_amphur[$i]['AMPHUR_NAME'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : เมือง.</p>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                            
                            <select id="district_id" name="district_id" class="form-control">
                            <option value="">Select</option>
                            <?php 
                                for($i =  0 ; $i < count($add_district) ; $i++){
                                ?>
                                <option <?php if($user['district_id'] == $add_district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $add_district[$i]['DISTRICT_ID'] ?>"><?php echo $add_district[$i]['DISTRICT_NAME'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : ในเมือง.</p>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                            <input id="user_zipcode" name="user_zipcode" type="text" readonly class="form-control" value="<?php echo $user['user_zipcode']?>">
                            <p class="help-block">Example : 30000.</p>
                        </div>
                    </div>
                    
                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->


                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>ตำแหน่ง / Position <font color="#F00"><b>*</b></font> </label>
                            <select class="form-control" id="user_position_code" name="user_position_code">
                            <option value="">Select</option>
                                <?php 
                                for($i =  0 ; $i < count($user_position) ; $i++){
                                ?>
                                <option <?php if($user['user_position_code'] == $user_position[$i]['user_position_code'] ){?> selected <?php } ?> value="<?php echo $user_position[$i]['user_position_code'] ?>"><?php echo $user_position[$i]['user_position_name'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : ผู้ดูแลระบบ.</p>
                        </div>
                    </div> 

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>สิทธิ์การใช้งาน / License <font color="#F00"><b>*</b></font> </label>
                            <select class="form-control" id="license_code" name="license_code">
                            <option value="">Select</option>
                                <?php 
                                for($i =  0 ; $i < count($license) ; $i++){
                                ?>
                                <option <?php if($user['license_code'] == $license[$i]['license_code'] ){?> selected <?php } ?> value="<?php echo $license[$i]['license_code'] ?>"><?php echo $license[$i]['license_name'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : สิทธิ์การใช้งานที่ 1 .</p>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                            <select class="form-control" id="user_status_code" name="user_status_code">
                            <option value="">Select</option>
                                <?php 
                                for($i =  0 ; $i < count($user_status) ; $i++){
                                ?>
                                <option <?php if($user['user_status_code'] == $user_status[$i]['user_status_code'] ){?> selected <?php } ?> value="<?php echo $user_status[$i]['user_status_code'] ?>"><?php echo $user_status[$i]['user_status_name'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : ทำงาน.</p>
                        </div>
                    </div> 
                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
                <div class="row">
                    <div class="col-lg-offset-9 col-lg-3" align="right">
                        <a href="?app=user" class="btn btn-default">Back</a>
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