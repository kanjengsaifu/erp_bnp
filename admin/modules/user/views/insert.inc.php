<script>
    function check_user_code(){ 
        
        var user_code = document.getElementById("user_code").value; 
        
        user_code = $.trim(user_code);

        if(user_code.length == 0){
            $('#alert_user_code').html('Example : STE0001.');
            $('#alert_user_code').removeClass('alert-danger');
            $('#alert_user_code').removeClass('alert-success');
        }else{
            $.post("modules/user/controllers/checkUserBy.php", { user_code: user_code })
                .done(function(data) {
                    // console.log(data);
                    if(data != null){ 
                        document.getElementById("user_code").focus();
                        $('#alert_user_code').html('This code : '+user_code+' is already in the system.');
                        $('#alert_user_code').addClass('alert-danger');
                        $('#alert_user_code').removeClass('alert-success');
                    }else{
                        $('#alert_user_code').html('Code : '+user_code+' can be used.');
                        $('#alert_user_code').removeClass('alert-danger');
                        $('#alert_user_code').addClass('alert-success');
                    }
            });
        } 
    } 
    
    function check_user_username(){ 
        
        var user_username = document.getElementById("user_username").value; 
        
        user_username = $.trim(user_username);

        if(user_username.length == 0){
            $('#alert_user_username').html('Example : thana.');
            $('#alert_user_username').removeClass('alert-danger');
            $('#alert_user_username').removeClass('alert-success');
        }else{
            $.post("modules/user/controllers/checkUserBy.php", { user_username: user_username })
                .done(function(data) {
                    // console.log(data);
                    if(data != null){ 
                        document.getElementById("user_username").focus();
                        $('#alert_user_username').html('This username : '+user_username+' is already in the system.');
                        $('#alert_user_username').addClass('alert-danger');
                        $('#alert_user_username').removeClass('alert-success');
                    }else{
                        $('#alert_user_username').html('Username : '+user_username+' can be used.');
                        $('#alert_user_username').removeClass('alert-danger');
                        $('#alert_user_username').addClass('alert-success');
                    }
            });
        } 
    }  
    
    function check_password(){
        var password = document.getElementById("user_password").value;

        password = $.trim(password);

        if(password.length == 0){
            $('#alert_user_password').html('Example : STE0001.');
            $('#alert_user_password').removeClass('alert-danger');
            $('#alert_user_password').removeClass('alert-success');
        }else if(password.length < 6 || password.length > 15){
            $('#alert_user_password').html('length should be 6-15 characters');
            $('#alert_user_password').addClass('alert-danger');
            $('#alert_user_password').removeClass('alert-success');
        }else{
            $('#alert_user_password').html('Password can be used.');
            $('#alert_user_password').removeClass('alert-danger');
            $('#alert_user_password').addClass('alert-success');
        }
    }

    function check(){
        var user_code = document.getElementById("user_code").value;
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

        user_code = $.trim(user_code);
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
        }else if(user_code.length != 0 && $('#alert_user_code').hasClass('alert-danger')){
            return false;
        }else if($('#alert_user_username').hasClass('alert-danger')){
            return false;
        }else if($('#alert_user_password').hasClass('alert-danger')){
            return false;
        }else{ 
            return true;
        }
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

<div class="panel panel-default">
    <div class="panel-heading">
    เพิ่มพนักงาน / Add employee 
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <form  id="form_target" role="form" method="post" action="index.php?app=user&action=add" onsubmit="return check();">
            <div class="row">
            
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>รหัสพนักงาน / Employee Code</label>
                        <input id="user_code" name="user_code" class="form-control" onchange="check_user_code();" /> 
                        <p id="alert_user_code" class="help-block">Example : STE0001.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="user_prefix" name="user_prefix" class="form-control">
                            <option value="">Select</option>
                            <option>นาย</option>
                            <option>นาง</option>
                            <option>นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="user_name" name="user_name" class="form-control">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="user_lastname" name="user_lastname" class="form-control">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>อีเมล์ / Email </label>
                        <input id="user_email" name="user_email" type="email" class="form-control">
                        <p class="help-block">Example : admin@arno.co.th.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="user_mobile" name="user_mobile" type="text" class="form-control">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ยูสเซอร์ / Username <font color="#F00"><b>*</b></font></label>
                        <input id="user_username" name="user_username" class="form-control" onchange="check_user_username()"> 
                        <p id="alert_user_username" class="help-block">Example : thana.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>รหัสผ่าน / Password <font color="#F00"><b>*</b></font></label>
                        <input id="user_password" name="user_password" type="password" class="form-control" onchange="check_password()">
                        <p id="alert_user_password" class="help-block">Example : thanaadmin.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="user_address" name="user_address" class="form-control">
                        <p class="help-block">Example : 271/55.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>จังหวัด / Province <font color="#F00"><b>*</b></font> </label>
                        <select id="province_id" name="province_id" data-live-search="true" class="form-control select" onchange="getAmphur()">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($add_province) ; $i++){
                            ?>
                            <option value="<?php echo $add_province[$i]['PROVINCE_ID'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
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
                        <select id="amphur_id" name="amphur_id" data-live-search="true" class="form-control select" onchange="getDistrict()">
                        <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : เมือง.</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select">
                        <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="user_zipcode" name="user_zipcode" type="text" readonly class="form-control">
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
                            <option value="<?php echo $user_position[$i]['user_position_code'] ?>"><?php echo $user_position[$i]['user_position_name'] ?></option>
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
                            <option value="<?php echo $license[$i]['license_code'] ?>"><?php echo $license[$i]['license_name'] ?></option>
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
                            <option value="<?php echo $user_status[$i]['user_status_code'] ?>"><?php echo $user_status[$i]['user_status_name'] ?></option>
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
                    <button  type="submit"  class="btn btn-success"  >Save</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->

<script type="text/javascript">
    function getAmphur(){
        var province = document.getElementById("province_id").value;

        $.post("controllers/getAmphur.php", { 'province': province }, function( data ) {
            $("#amphur_id").html(data);
            $("#amphur_id").selectpicker('refresh');
        });

        document.getElementById("amphur_id").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur_id").value;

        $.post("controllers/getDistrict.php", { 'amphur': amphur }, function( data ) {
            $("#district_id").html(data);
            $("#district_id").selectpicker('refresh');
        });

        $.post("controllers/getZipcode.php", { 'amphur': amphur }, function( data ) {
            $("#user_zipcode").val(data);
        });
    }
</script>