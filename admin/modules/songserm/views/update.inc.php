<script>
    function check_username(){
        var code = document.getElementById("songserm_code").value;
        var username = document.getElementById("songserm_username").value;

        code = $.trim(code);
        username = $.trim(username);

        if(username.length == 0){
            $('#alert_username').html('Example : STE0001.');
            $('#alert_username').removeClass('alert-danger');
            $('#alert_username').removeClass('alert-success');
        }else if(username.length < 6 || username.length > 15){
            $('#alert_username').html('length should be 6-15 characters');
            $('#alert_username').addClass('alert-danger');
            $('#alert_username').removeClass('alert-success');
        }else{
            $.post("modules/songserm/controllers/getSongsermByUsername.php", { code: code, username: username })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("songserm_username").focus();
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
        var password = document.getElementById("songserm_password").value;

        password = $.trim(password);

        if(password.length == 0){
            $('#alert_password').html('Example : STE0001.');
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
        var songserm_prefix = document.getElementById("songserm_prefix").value;
        var songserm_name = document.getElementById("songserm_name").value;
        var songserm_lastname = document.getElementById("songserm_lastname").value;
        var songserm_mobile = document.getElementById("songserm_mobile").value;
        var songserm_address = document.getElementById("songserm_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var songserm_zipcode = document.getElementById("songserm_zipcode").value;
        var songserm_status_code = document.getElementById("songserm_status_code").value;  

        songserm_prefix = $.trim(songserm_prefix);
        songserm_name = $.trim(songserm_name);
        songserm_lastname = $.trim(songserm_lastname);
        songserm_mobile = $.trim(songserm_mobile);
        songserm_address = $.trim(songserm_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        songserm_zipcode = $.trim(songserm_zipcode);
        songserm_status_code = $.trim(songserm_status_code); 

        if(songserm_prefix.length == 0){
            alert("Please input songserm prefix");
            document.getElementById("songserm_prefix").focus();
            return false;
        }else if(songserm_name.length == 0){
            alert("Please input songserm name");
            document.getElementById("songserm_name").focus();
            return false;
        }else if(songserm_lastname.length == 0){
            alert("Please input songserm lastname");
            document.getElementById("songserm_lastname").focus();
            return false;
        }else if(songserm_address.length == 0){
            alert("Please input songserm address");
            document.getElementById("songserm_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input songserm provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input songserm amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input songserm district");
            document.getElementById("district_id").focus();
            return false;
        }else if(songserm_status_code.length == 0){
            alert("Please input songserm status");
            document.getElementById("songserm_status_code").focus();
            return false; 
        }else if($('#alert_username').hasClass('alert-danger')){
            document.getElementById("songserm_username").focus();
            return false;
        }else if($('#alert_password').hasClass('alert-danger')){
            document.getElementById("songserm_password").focus();
            return false;
        }else{ 
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_songserm').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_songserm').attr('src', '../upload/default.png');
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการทีมส่งเสริม / Songserm Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขทีมส่งเสริม / Edit songserm 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=songserm&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="songserm_code" name="songserm_code" class="form-control" value="<?php echo $songserm['songserm_code']?>" autocomplete="off" readonly>
                        <p id="alert_code" class="help-block">Example : STE0001.</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อบัญชีผู้ใช้ / user name <font color="#F00"><b>*</b></font></label>
                        <input required id="songserm_username" name="songserm_username" class="form-control" value="<?php echo $songserm['songserm_username']?>" autocomplete="off" onchange="check_username();">
                        <p id="alert_username" class="help-block">Example : STE0001.</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>รหัสผ่าน / password <font color="#F00"><b>* (6-15)</b></font></label>
                        <input required id="songserm_password" name="songserm_password" class="form-control" value="<?php echo $songserm['songserm_password']?>" autocomplete="off" onchange="check_password();">
                        <p id="alert_password" class="help-block">Example : STE0001.</p>
                    </div>
                </div>
            </div> 
            <div class="row"> 
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="songserm_prefix" name="songserm_prefix" class="form-control">
                            <option value="">Select</option>
                            <option <?php if($songserm['songserm_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                            <option <?php if($songserm['songserm_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                            <option <?php if($songserm['songserm_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="songserm_name" name="songserm_name" class="form-control" value="<?php echo $songserm['songserm_name']?>" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="songserm_lastname" name="songserm_lastname" class="form-control" value="<?php echo $songserm['songserm_lastname']?>" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ตำเเหน่ง / Position <font color="#F00"><b>*</b></font> </label>
                        <select required id="songserm_position_code" name="songserm_position_code" class="form-control select">
                            <?php 
                            for($i =  0 ; $i < count($songserm_position) ; $i++){
                            ?>
                            <option <?php if($songserm['songserm_position_code'] == $songserm_position[$i]['songserm_position_code'] ){?> selected <?php } ?> value="<?php echo $songserm_position[$i]['songserm_position_code'] ?>"><?php echo $songserm_position[$i]['songserm_position_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ส่งเสริม.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                        <select class="form-control" id="songserm_status_code" name="songserm_status_code">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($songserm_status) ; $i++){
                            ?>
                            <option <?php if($songserm['songserm_status_code'] == $songserm_status[$i]['songserm_status_code'] ){?> selected <?php } ?> value="<?php echo $songserm_status[$i]['songserm_status_code'] ?>"><?php echo $songserm_status[$i]['songserm_status_name'] ?></option>
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
                        <input id="songserm_mobile" name="songserm_mobile" type="text" class="form-control" value="<?php echo $songserm['songserm_mobile']?>" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="songserm_address" name="songserm_address" class="form-control" value="<?php echo $songserm['songserm_address']?>" autocomplete="off">
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
                            <option <?php if($songserm['province_id'] == $add_province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $add_province[$i]['PROVINCE_ID'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
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
                            for($i =  0 ; $i < count($add_amphur) ; $i++){
                            ?>
                            <option <?php if($songserm['amphur_id'] == $add_amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $add_amphur[$i]['AMPHUR_ID'] ?>"><?php echo $add_amphur[$i]['AMPHUR_NAME'] ?></option>
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
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($add_district) ; $i++){
                            ?>
                            <option <?php if($songserm['district_id'] == $add_district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $add_district[$i]['DISTRICT_ID'] ?>"><?php echo $add_district[$i]['DISTRICT_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="songserm_zipcode" name="songserm_zipcode" type="text" readonly class="form-control" value="<?php echo $songserm['songserm_zipcode']?>"  autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label>รูปทีมส่งเสริม / Songserm image </label>
                    <div class="form-group" align="center">
                        <img id="img_songserm" src="../upload/<?php if($songserm['profile_image'] != "") echo 'songserm/'.$songserm['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="profile_image" name="profile_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=songserm" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" id="profile_image_o" name="profile_image_o" value="<?php echo $songserm['profile_image']; ?>">
            <input type="hidden" id="id_card_image_o" name="id_card_image_o" value="<?php echo $songserm['id_card_image']; ?>">

            <input type="hidden" id="songserm_code" name="songserm_code" value="<?php echo $songserm_code ?>">
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
            $("#songserm_zipcode").val(data);
        });
    }
</script>