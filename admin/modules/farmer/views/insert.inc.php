<script>
    function check_code(){
        var code = document.getElementById("farmer_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : 1309905557849.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/farmer/controllers/getFarmerByCode.php", { farmer_code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("farmer_code").focus();
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

    function check(){
        var farmer_prefix = document.getElementById("farmer_prefix").value;
        var farmer_name = document.getElementById("farmer_name").value;
        var farmer_lastname = document.getElementById("farmer_lastname").value;
        var farmer_mobile = document.getElementById("farmer_mobile").value;
        var farmer_address = document.getElementById("farmer_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var village_id = document.getElementById("village_id").value;

        farmer_prefix = $.trim(farmer_prefix);
        farmer_name = $.trim(farmer_name);
        farmer_lastname = $.trim(farmer_lastname);
        farmer_mobile = $.trim(farmer_mobile);
        farmer_address = $.trim(farmer_address);

        if(farmer_prefix.length == 0){
            alert("Please input prefix");
            document.getElementById("farmer_prefix").focus();
            return false;
        }else if(farmer_name.length == 0){
            alert("Please input name");
            document.getElementById("farmer_name").focus();
            return false;
        }else if(farmer_lastname.length == 0){
            alert("Please input lastname");
            document.getElementById("farmer_lastname").focus();
            return false;
        }else if(farmer_address.length == 0){
            alert("Please input address");
            document.getElementById("farmer_address").focus();
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
        }else if($('#alert_code').hasClass('alert-danger')){
            document.getElementById("farmer_code").focus();
            return false;
        }else{ 
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_farmer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_farmer').attr('src', '../upload/default.png');
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการเกษตรกร / Farmer Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มเกษตรกร / Add farmer
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=farmer&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="farmer_code" name="farmer_code" class="form-control" autocomplete="off" onchange="check_code();">
                        <p id="alert_code" class="help-block">Example : 1309905557849.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="farmer_prefix" name="farmer_prefix" class="form-control select">
                            <option value="">Select</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="farmer_name" name="farmer_name" class="form-control" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="farmer_lastname" name="farmer_lastname" class="form-control" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="farmer_mobile" name="farmer_mobile" type="text" class="form-control" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ไลน์ไอดี / LINE ID </label>
                        <input id="farmer_line" name="farmer_line" class="form-control" autocomplete="off">
                        <p class="help-block">Example : Line_ID.</p>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="farmer_address" name="farmer_address" class="form-control" autocomplete="off">
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
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select" onchange="getVillage()">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>หมู่บ้าน / Village <font color="#F00"><b>*</b></font> </label>
                        <select id="village_id" name="village_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : บ้าน.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="farmer_zipcode" name="farmer_zipcode" type="text" class="form-control" autocomplete="off" readonly>
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <label>รูปเกษตรกร / Farmer image </label>
                    <div class="form-group" align="center">
                        <img id="img_farmer" src="../upload/default.png" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="profile_image" name="profile_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=farmer" class="btn btn-default">Back</a>
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
            $("#farmer_zipcode").val(data);
        });
    }

    function getVillage(){
        var district = document.getElementById("district_id").value;

        $.post("controllers/getVillage.php", { district: district }, function( data ) {
            $("#village_id").html(data);
            $("#village_id").selectpicker('refresh');
        });
    }
</script>