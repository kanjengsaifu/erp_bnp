<script>
    function check(){
        var farmer_prefix = document.getElementById("farmer_prefix").value;
        var farmer_name = document.getElementById("farmer_name").value;
        var farmer_lastname = document.getElementById("farmer_lastname").value;
        var farmer_mobile = document.getElementById("farmer_mobile").value;
        var farmer_address = document.getElementById("farmer_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var farmer_zipcode = document.getElementById("farmer_zipcode").value;
        var farmer_line = document.getElementById("farmer_line").value;  

        farmer_prefix = $.trim(farmer_prefix);
        farmer_name = $.trim(farmer_name);
        farmer_lastname = $.trim(farmer_lastname);
        farmer_mobile = $.trim(farmer_mobile);
        farmer_address = $.trim(farmer_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        farmer_zipcode = $.trim(farmer_zipcode);
        farmer_line = $.trim(farmer_line); 

        if(farmer_prefix.length == 0){
            alert("Please input farmer prefix");
            document.getElementById("farmer_prefix").focus();
            return false;
        }else if(farmer_name.length == 0){
            alert("Please input farmer name");
            document.getElementById("farmer_name").focus();
            return false;
        }else if(farmer_lastname.length == 0){
            alert("Please input farmer lastname");
            document.getElementById("farmer_lastname").focus();
            return false;
        }else if(farmer_address.length == 0){
            alert("Please input farmer address");
            document.getElementById("farmer_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input farmer provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input farmer amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input farmer district");
            document.getElementById("district_id").focus();
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
        แก้ไขเกษตรกร / Edit farmer 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=farmer&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="farmer_code" name="farmer_code" class="form-control" value="<?php echo $farmer['farmer_code']?>" autocomplete="off" readonly>
                        <p id="alert_code" class="help-block">Example : 1309905557849.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="farmer_prefix" name="farmer_prefix" class="form-control">
                            <option value="">Select</option>
                            <option <?php if($farmer['farmer_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                            <option <?php if($farmer['farmer_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                            <option <?php if($farmer['farmer_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="farmer_name" name="farmer_name" class="form-control" value="<?php echo $farmer['farmer_name']?>" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="farmer_lastname" name="farmer_lastname" class="form-control" value="<?php echo $farmer['farmer_lastname']?>" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="farmer_mobile" name="farmer_mobile" type="text" class="form-control" value="<?php echo $farmer['farmer_mobile']?>" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ไลน์ไอดี / LINE ID </label>
                        <input id="farmer_line" name="farmer_line" class="form-control" value="<?php echo $farmer['farmer_line']?>" autocomplete="off">
                        <p class="help-block">Example : Line_ID.</p>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="farmer_address" name="farmer_address" class="form-control" value="<?php echo $farmer['farmer_address']?>" autocomplete="off">
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
                            <option <?php if($farmer['province_id'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <option <?php if($farmer['amphur_id'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
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
                            for($i =  0 ; $i < count($district) ; $i++){
                            ?>
                            <option <?php if($farmer['district_id'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
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
                        <input id="farmer_zipcode" name="farmer_zipcode" type="text" readonly class="form-control" value="<?php echo $farmer['farmer_zipcode']?>"  autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <label>รูปเกษตรกร / Farmer image </label>
                    <div class="form-group" align="center">
                        <img id="img_farmer" src="../upload/<?php if($farmer['profile_image'] != "") echo 'farmer/'.$farmer['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
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

            <input type="hidden" id="profile_image_o" name="profile_image_o" value="<?php echo $farmer['profile_image']; ?>">

            <input type="hidden" id="farmer_code" name="farmer_code" value="<?php echo $farmer_code ?>">
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
</script>