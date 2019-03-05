<script>
    function check_code(id){
        var code = $(id).val();
        $.post("controllers/getSongsermByCode.php", { 'songserm_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("songserm_code").focus();
                $("#code_check").val(data.songserm_code);
            } else{
                $("#code_check").val("");
            }
        });
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
        <h1 class="page-header">จัดการส่งเสริม / Songserm Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขส่งเสริม / Edit songserm 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=songserm&action=edit" enctype="multipart/form-data">>
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
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="songserm_mobile" name="songserm_mobile" type="text" class="form-control" value="<?php echo $songserm['songserm_mobile']?>" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="songserm_address" name="songserm_address" class="form-control" value="<?php echo $songserm['songserm_address']?>" autocomplete="off">
                        <p class="help-block">Example : 271/55.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3">
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

                <div class="col-md-6 col-lg-3">
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

                <div class="col-md-6 col-lg-3">
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

                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="songserm_zipcode" name="songserm_zipcode" type="text" readonly class="form-control" value="<?php echo $songserm['songserm_zipcode']?>"  autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>

                <div class="col-md-12">
                    <label>รูปส่งเสริม / Songserm image </label>
                    <div class="form-group" align="center">
                        <img id="img_songserm" src="../upload/<?php if($songserm['songserm_image'] != "") echo 'songserm/'.$songserm['songserm_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="songserm_image" name="songserm_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                    <div class="form-group" align="center">
                        <img id="img_id_card" src="../upload/<?php if($songserm['id_card_image'] != "") echo 'songserm/'.$songserm['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="id_card_image" name="id_card_image" class="form-control" style="margin-top: 14px" onChange="readURL_id_card(this);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>สำเนาทะเบียนบ้าน / Copy of House registration </label>
                    <div class="form-group" align="center">
                        <img id="img_house_regis" src="../upload/<?php if($songserm['house_regis_image'] != "") echo 'songserm/'.$songserm['house_regis_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="house_regis_image" name="house_regis_image" class="form-control" style="margin-top: 14px" onChange="readURL_house_regis(this);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>สำเนาหน้าสมุดบัญชี / Copy of account book page </label>
                    <div class="form-group" align="center">
                        <img id="img_account" src="../upload/<?php if($songserm['account_image'] != "") echo 'songserm/'.$songserm['account_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                        <input accept=".jpg , .png" type="file" id="account_image" name="account_image" class="form-control" style="margin-top: 14px" onChange="readURL_account(this);">
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

            <input type="hidden" id="songserm_image_o" name="songserm_image_o" value="<?php echo $songserm['songserm_image']; ?>">
            <input type="hidden" id="id_card_image_o" name="id_card_image_o" value="<?php echo $songserm['id_card_image']; ?>">
            <input type="hidden" id="house_regis_image_o" name="house_regis_image_o" value="<?php echo $songserm['house_regis_image']; ?>">
            <input type="hidden" id="account_image_o" name="account_image_o" value="<?php echo $songserm['account_image']; ?>">

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