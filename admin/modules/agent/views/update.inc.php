<script>
    function check_username(){
        var code = document.getElementById("agent_code").value;
        var username = document.getElementById("agent_username").value;

        code = $.trim(code);
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
            $.post("modules/agent/controllers/getAgentByUsername.php", { code: code, username: username })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("agent_username").focus();
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
        var password = document.getElementById("agent_password").value;

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
        var agent_prefix = document.getElementById("agent_prefix").value;
        var agent_name = document.getElementById("agent_name").value;
        var agent_lastname = document.getElementById("agent_lastname").value;
        var agent_address = document.getElementById("agent_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var village_id = document.getElementById("village_id").value;
        var agent_mobile = document.getElementById("agent_mobile").value;

        status_code = $.trim(status_code); 
        agent_prefix = $.trim(agent_prefix);
        agent_name = $.trim(agent_name);
        agent_lastname = $.trim(agent_lastname);
        agent_address = $.trim(agent_address);
        agent_mobile = $.trim(agent_mobile);

        if(agent_prefix.length == 0){
            alert("Please input prefix");
            document.getElementById("agent_prefix").focus();
            return false;
        }else if(agent_name.length == 0){
            alert("Please input name");
            document.getElementById("agent_name").focus();
            return false;
        }else if(agent_lastname.length == 0){
            alert("Please input lastname");
            document.getElementById("agent_lastname").focus();
            return false;
        }else if(agent_address.length == 0){
            alert("Please input address");
            document.getElementById("agent_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input district");
            document.getElementById("district_id").focus();
            return false;
        }else if(village_id.length == 0){
            alert("Please input village");
            document.getElementById("village_id").focus();
            return false;
        }else if(status_code.length == 0){
            alert("Please input status");
            document.getElementById("status_code").focus();
            return false; 
        }else if($('#alert_code').hasClass('alert-danger')){
            document.getElementById("agent_code").focus();
            return false;
        }else if($('#alert_username').hasClass('alert-danger')){
            document.getElementById("agent_username").focus();
            return false;
        }else if($('#alert_password').hasClass('alert-danger')){
            document.getElementById("agent_password").focus();
            return false;
        }else{ 
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_agent').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_agent').attr('src', '../upload/default.png');
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
        <h1 class="page-header">จัดการนายหน้า / Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขนายหน้า / Edit agent 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=agent&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="agent_code" name="agent_code" class="form-control" value="<?php echo $agent['agent_code']?>" autocomplete="off" readonly>
                        <p id="alert_code" class="help-block">Example : AG0001.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อบัญชีผู้ใช้ / user name <font color="#F00"><b>*</b></font></label>
                        <input required id="agent_username" name="agent_username" class="form-control" value="<?php echo $agent['agent_username']?>" autocomplete="off" onchange="check_username();">
                        <p id="alert_username" class="help-block">Example : AG0001.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสผ่าน / password <font color="#F00"><b>* (6-15)</b></font></label>
                        <input required id="agent_password" name="agent_password" class="form-control" value="<?php echo $agent['agent_password']?>" autocomplete="off" onchange="check_password();">
                        <p id="alert_password" class="help-block">Example : AG0001.</p>
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
                            <option <?php if($agent['status_code'] == $status[$i]['status_code'] ){?> selected <?php } ?> value="<?php echo $status[$i]['status_code'] ?>"><?php echo $status[$i]['status_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ทำงาน.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="agent_prefix" name="agent_prefix" class="form-control select">
                            <option value="">Select</option>
                            <option <?php if($agent['agent_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                            <option <?php if($agent['agent_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                            <option <?php if($agent['agent_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="agent_name" name="agent_name" class="form-control" value="<?php echo $agent['agent_name']?>" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="agent_lastname" name="agent_lastname" class="form-control" value="<?php echo $agent['agent_lastname']?>" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="agent_mobile" name="agent_mobile" type="text" class="form-control" value="<?php echo $agent['agent_mobile']?>" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ไลน์ไอดี / LINE ID </label>
                        <input id="agent_line" name="agent_line" type="text" class="form-control" value="<?php echo $agent['agent_line']?>" autocomplete="off">
                        <p class="help-block">Example : Line_ID</p>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="agent_address" name="agent_address" class="form-control" value="<?php echo $agent['agent_address']?>" autocomplete="off">
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
                            for($i =  0 ; $i < count($province) ; $i++){
                            ?>
                            <option <?php if($agent['PROVINCE_ID'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <?php 
                            for($i =  0 ; $i < count($amphur) ; $i++){
                            ?>
                            <option <?php if($agent['AMPHUR_ID'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : เมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select" onchange="getVillage()">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($district) ; $i++){
                            ?>
                            <option <?php if($agent['DISTRICT_ID'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>หมู่บ้าน / Village <font color="#F00"><b>*</b></font> </label>
                        <select id="village_id" name="village_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($village) ; $i++){
                            ?>
                            <option <?php if($agent['village_id'] == $village[$i]['VILLAGE_ID'] ){?> selected <?php } ?> value="<?php echo $village[$i]['VILLAGE_ID'] ?>"><?php echo $village[$i]['VILLAGE_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : บ้าน.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="agent_zipcode" name="agent_zipcode" type="text" readonly class="form-control" value="<?php echo $agent['POSTCODE']?>" autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label>รูปนายหน้า / Agent image </label>
                    <div class="form-group" align="center">
                        <img id="img_agent" src="../upload/<?php if($agent['profile_image'] != "") echo 'agent/'.$agent['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="profile_image" name="profile_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                    <div class="form-group" align="center">
                        <img id="img_id_card" src="../upload/<?php if($agent['id_card_image'] != "") echo 'agent/'.$agent['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="id_card_image" name="id_card_image" class="form-control" style="margin-top: 14px" onChange="readURL_id_card(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=agent" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" id="profile_image_o" name="profile_image_o" value="<?php echo $agent['profile_image']; ?>">
            <input type="hidden" id="id_card_image_o" name="id_card_image_o" value="<?php echo $agent['id_card_image']; ?>">

            <input type="hidden" id="agent_code" name="agent_code" value="<?php echo $agent_code ?>">
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
            $("#agent_zipcode").val(data);
        });
    }
</script>