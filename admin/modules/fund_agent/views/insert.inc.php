<script>
    function check_code(){
        var code = document.getElementById("fund_agent_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : AG0001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/fund_agent/controllers/getFundAgentByCode.php", { code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("fund_agent_code").focus();
                        $('#alert_code').html('This code : '+code+' is already in the system.');
                        $('#alert_code').addClass('alert-danger');
                        $('#alert_code').removeClass('alert-success');
                    }else{
                        $('#alert_code').html('Code : '+code+' can be used.');
                        $('#alert_code').removeClass('alert-danger');
                        $('#alert_code').addClass('alert-success');
                    }
            });
        }
    }

    function check_username(){
        var username = document.getElementById("fund_agent_username").value;

        username = $.trim(username);

        if(username.length == 0){
            $('#alert_username').html('Example : AG0001.');
            $('#alert_username').removeClass('alert-danger');
            $('#alert_username').removeClass('alert-success');
        }else if(username.length < 6 || username.length > 15){
            $('#alert_username').html('length should be 6-15 characters');
            $('#alert_username').addClass('alert-danger');
            $('#alert_username').removeClass('alert-success');
        }else{
            $.post("modules/fund_agent/controllers/getFundAgentByUsername.php", { username: username })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("fund_agent_username").focus();
                        $('#alert_username').html('This username : '+username+' is already in the system.');
                        $('#alert_username').addClass('alert-danger');
                        $('#alert_username').removeClass('alert-success');
                    }else{
                        $('#alert_username').html('Username : '+username+' can be used.');
                        $('#alert_username').removeClass('alert-danger');
                        $('#alert_username').addClass('alert-success');
                    }
            });
        }
    }

    function check_password(){
        var password = document.getElementById("fund_agent_password").value;

        password = $.trim(password);

        if(password.length == 0){
            $('#alert_password').html('Example : AG0001.');
            $('#alert_password').removeClass('alert-danger');
            $('#alert_password').removeClass('alert-success');
        }else if(password.length < 6 || password.length > 15){
            $('#alert_password').html('length should be 6-15 characters');
            $('#alert_password').addClass('alert-danger');
            $('#alert_password').removeClass('alert-success');
        }else{
            $('#alert_password').html('Password can be used.');
            $('#alert_password').removeClass('alert-danger');
            $('#alert_password').addClass('alert-success');
        }
    }

    function check(){
        var status_code = document.getElementById("status_code").value;  
        var fund_agent_prefix = document.getElementById("fund_agent_prefix").value;
        var fund_agent_name = document.getElementById("fund_agent_name").value;
        var fund_agent_lastname = document.getElementById("fund_agent_lastname").value;
        var fund_agent_address = document.getElementById("fund_agent_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var fund_agent_zipcode = document.getElementById("fund_agent_zipcode").value;
        var fund_agent_mobile = document.getElementById("fund_agent_mobile").value;

        status_code = $.trim(status_code); 
        fund_agent_prefix = $.trim(fund_agent_prefix);
        fund_agent_name = $.trim(fund_agent_name);
        fund_agent_lastname = $.trim(fund_agent_lastname);
        fund_agent_address = $.trim(fund_agent_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        fund_agent_zipcode = $.trim(fund_agent_zipcode);
        fund_agent_mobile = $.trim(fund_agent_mobile);

        if(fund_agent_prefix.length == 0){
            alert("Please input fund_agent prefix");
            document.getElementById("fund_agent_prefix").focus();
            return false;
        }else if(fund_agent_name.length == 0){
            alert("Please input fund_agent name");
            document.getElementById("fund_agent_name").focus();
            return false;
        }else if(fund_agent_lastname.length == 0){
            alert("Please input fund_agent lastname");
            document.getElementById("fund_agent_lastname").focus();
            return false;
        }else if(fund_agent_address.length == 0){
            alert("Please input fund_agent address");
            document.getElementById("fund_agent_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input fund_agent provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input fund_agent amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input fund_agent district");
            document.getElementById("district_id").focus();
            return false;
        }else if(status_code.length == 0){
            alert("Please input fund_agent status");
            document.getElementById("status_code").focus();
            return false; 
        }else if($('#alert_code').hasClass('alert-danger')){
            document.getElementById("fund_agent_code").focus();
            return false;
        }else if($('#alert_username').hasClass('alert-danger')){
            document.getElementById("fund_agent_username").focus();
            return false;
        }else if($('#alert_password').hasClass('alert-danger')){
            document.getElementById("fund_agent_password").focus();
            return false;
        }else{ 
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_profile').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_profile').attr('src', '../upload/default.png');
        }
    }

    function readURL_id_card(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_id_card').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_id_card').attr('src', '../upload/default.png');
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการตัวเเทนกองทุน / Fund Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มตัวเเทนกองทุน / Add fund agent
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=fund_agent&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="fund_agent_code" name="fund_agent_code" class="form-control" autocomplete="off" onchange="check_code();">
                        <p id="alert_code" class="help-block">Example : AG0001.</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อบัญชีผู้ใช้ / user name <font color="#F00"><b>*</b></font></label>
                        <input required id="fund_agent_username" name="fund_agent_username" class="form-control" autocomplete="off" onchange="check_username();">
                        <p id="alert_username" class="help-block">Example : AG0001.</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>รหัสผ่าน / password <font color="#F00"><b>* (6-15)</b></font></label>
                        <input required id="fund_agent_password" name="fund_agent_password" class="form-control" autocomplete="off" onchange="check_password();">
                        <p id="alert_password" class="help-block">Example : AG0001.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="fund_agent_prefix" name="fund_agent_prefix" class="form-control select">
                            <option value="">Select</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="fund_agent_name" name="fund_agent_name" class="form-control" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="fund_agent_lastname" name="fund_agent_lastname" class="form-control" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                        <select id="status_code" name="status_code" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($status) ; $i++){
                            ?>
                            <option value="<?php echo $status[$i]['status_code'] ?>"><?php echo $status[$i]['status_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ทำงาน.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="fund_agent_mobile" name="fund_agent_mobile" type="text" class="form-control" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="fund_agent_address" name="fund_agent_address" class="form-control" autocomplete="off">
                        <p class="help-block">Example : 271/55.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
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

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>อำเภอ / Amphur <font color="#F00"><b>*</b></font> </label>
                        <select id="amphur_id" name="amphur_id" data-live-search="true"  class="form-control select" onchange="getDistrict()">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : เมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="fund_agent_zipcode" name="fund_agent_zipcode" type="text" readonly class="form-control" autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label>รูปตัวเเทนกองทุน / FundAgent image </label>
                    <div class="form-group" align="center">
                        <img id="img_profile" src="../upload/default.png" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="profile_image" name="profile_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                    <div class="form-group" align="center">
                        <img id="img_id_card" src="../upload/default.png" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="id_card_image" name="id_card_image" class="form-control" style="margin-top: 14px" onChange="readURL_id_card(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=fund_agent" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
            $("#fund_agent_zipcode").val(data);
        });
    }
</script>