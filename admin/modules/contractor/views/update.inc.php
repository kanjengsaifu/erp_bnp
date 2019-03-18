<script>
    function check(){
        var contractor_prefix = document.getElementById("contractor_prefix").value;
        var contractor_name = document.getElementById("contractor_name").value;
        var contractor_lastname = document.getElementById("contractor_lastname").value;
        var contractor_mobile = document.getElementById("contractor_mobile").value;
        var contractor_address = document.getElementById("contractor_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var village_id = document.getElementById("village_id").value;
        var status_code = document.getElementById("status_code").value;  

        contractor_prefix = $.trim(contractor_prefix);
        contractor_name = $.trim(contractor_name);
        contractor_lastname = $.trim(contractor_lastname);
        contractor_mobile = $.trim(contractor_mobile);
        contractor_address = $.trim(contractor_address);

        if(contractor_prefix.length == 0){
            alert("Please input prefix");
            document.getElementById("contractor_prefix").focus();
            return false;
        }else if(contractor_name.length == 0){
            alert("Please input name");
            document.getElementById("contractor_name").focus();
            return false;
        }else if(contractor_lastname.length == 0){
            alert("Please input lastname");
            document.getElementById("contractor_lastname").focus();
            return false;
        }else if(contractor_address.length == 0){
            alert("Please input address");
            document.getElementById("contractor_address").focus();
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
        }else{ 
            return true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_contractor').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_contractor').attr('src', '../upload/default.png');
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

    function readURL_house_regis(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_house_regis').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_house_regis').attr('src', '../upload/default.png');
        }
    }

    function readURL_account(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_account').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_account').attr('src', '../upload/default.png');
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการผู้รับเหมา / Contractor Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขผู้รับเหมา / Edit contractor 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=contractor&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-8 col-lg-3">
                    <div class="form-group">
                        <label>รหัสประจำตัว / code </label>
                        <input id="contractor_code" name="contractor_code" class="form-control" value="<?php echo $contractor['contractor_code']?>" autocomplete="off" readonly>
                        <p id="alert_code" class="help-block">Example : CT0001.</p>
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
                            <option <?php if($contractor['status_code'] == $status[$i]['status_code'] ){?> selected <?php } ?> value="<?php echo $status[$i]['status_code'] ?>"><?php echo $status[$i]['status_name'] ?></option>
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
                        <select id="contractor_prefix" name="contractor_prefix" class="form-control select">
                            <option value="">Select</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_name" name="contractor_name" class="form-control" value="<?php echo $contractor['contractor_name']?>" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_lastname" name="contractor_lastname" class="form-control" value="<?php echo $contractor['contractor_lastname']?>" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="contractor_mobile" name="contractor_mobile" type="text" class="form-control" value="<?php echo $contractor['contractor_mobile']?>" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ไลน์ไอดี / LINE ID </label>
                        <input id="contractor_line" name="contractor_line" type="text" class="form-control" value="<?php echo $contractor['contractor_line']?>" autocomplete="off">
                        <p class="help-block">Example : Line_ID.</p>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="contractor_address" name="contractor_address" class="form-control" value="<?php echo $contractor['contractor_address']?>" autocomplete="off">
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
                            <option <?php if($contractor['PROVINCE_ID'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <option <?php if($contractor['AMPHUR_ID'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
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
                            <option <?php if($contractor['DISTRICT_ID'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
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
                            <option <?php if($contractor['village_id'] == $village[$i]['VILLAGE_ID'] ){?> selected <?php } ?> value="<?php echo $village[$i]['VILLAGE_ID'] ?>"><?php echo $village[$i]['VILLAGE_NAME'] ?></option>
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
                        <input id="contractor_zipcode" name="contractor_zipcode" type="text" readonly class="form-control" value="<?php echo $contractor['POSTCODE']?>" autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <label>รูปผู้รับเหมา / Contractor image </label>
                    <div class="form-group" align="center">
                        <img id="img_contractor" src="../upload/<?php if($contractor['profile_image'] != "") echo 'contractor/'.$contractor['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="profile_image" name="profile_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-lg-4">
                    <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                    <div class="form-group" align="center">
                        <img id="img_id_card" src="../upload/<?php if($contractor['id_card_image'] != "") echo 'contractor/'.$contractor['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="id_card_image" name="id_card_image" class="form-control" style="margin-top: 14px" onChange="readURL_id_card(this);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>สำเนาทะเบียนบ้าน / Copy of House registration </label>
                    <div class="form-group" align="center">
                        <img id="img_house_regis" src="../upload/<?php if($contractor['house_regis_image'] != "") echo 'contractor/'.$contractor['house_regis_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="house_regis_image" name="house_regis_image" class="form-control" style="margin-top: 14px" onChange="readURL_house_regis(this);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>สำเนาหน้าสมุดบัญชี / Copy of account book page </label>
                    <div class="form-group" align="center">
                        <img id="img_account" src="../upload/<?php if($contractor['account_image'] != "") echo 'contractor/'.$contractor['account_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="account_image" name="account_image" class="form-control" style="margin-top: 14px" onChange="readURL_account(this);">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=contractor" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" id="profile_image_o" name="profile_image_o" value="<?php echo $contractor['profile_image']; ?>">
            <input type="hidden" id="id_card_image_o" name="id_card_image_o" value="<?php echo $contractor['id_card_image']; ?>">
            <input type="hidden" id="house_regis_image_o" name="house_regis_image_o" value="<?php echo $contractor['house_regis_image']; ?>">
            <input type="hidden" id="account_image_o" name="account_image_o" value="<?php echo $contractor['account_image']; ?>">

            <input type="hidden" id="contractor_code" name="contractor_code" value="<?php echo $contractor_code ?>">
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
            $("#contractor_zipcode").val(data);
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