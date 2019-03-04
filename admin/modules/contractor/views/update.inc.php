<script>
    function check(){
        var contractor_prefix = document.getElementById("contractor_prefix").value;
        var contractor_name = document.getElementById("contractor_name").value;
        var contractor_lastname = document.getElementById("contractor_lastname").value;
        var contractor_mobile = document.getElementById("contractor_mobile").value;
        var contractor_email = document.getElementById("contractor_email").value;
        var contractor_username = document.getElementById("contractor_username").value;
        var contractor_password = document.getElementById("contractor_password").value;
        var contractor_address = document.getElementById("contractor_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var contractor_zipcode = document.getElementById("contractor_zipcode").value;
        var contractor_status_code = document.getElementById("contractor_status_code").value;  

        contractor_prefix = $.trim(contractor_prefix);
        contractor_name = $.trim(contractor_name);
        contractor_lastname = $.trim(contractor_lastname);
        contractor_mobile = $.trim(contractor_mobile);
        contractor_email = $.trim(contractor_email);
        contractor_username = $.trim(contractor_username);
        contractor_password = $.trim(contractor_password);
        contractor_address = $.trim(contractor_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        contractor_zipcode = $.trim(contractor_zipcode);
        contractor_status_code = $.trim(contractor_status_code); 

        if(contractor_prefix.length == 0){
            alert("Please input contractor prefix");
            document.getElementById("contractor_prefix").focus();
            return false;
        }else if(contractor_name.length == 0){
            alert("Please input contractor name");
            document.getElementById("contractor_name").focus();
            return false;
        }else if(contractor_lastname.length == 0){
            alert("Please input contractor lastname");
            document.getElementById("contractor_lastname").focus();
            return false;
        }else if(contractor_username.length == 0){
            alert("Please input contractor contractorname");
            document.getElementById("contractor_username").focus();
            return false;
        }else if(contractor_password.length == 0){
            alert("Please input contractor password");
            document.getElementById("contractor_password").focus();
            return false;
        }else if(contractor_address.length == 0){
            alert("Please input contractor address");
            document.getElementById("contractor_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input contractor provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input contractor amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input contractor district");
            document.getElementById("district_id").focus();
            return false;
        }else if(contractor_status_code.length == 0){
            alert("Please input contractor status");
            document.getElementById("contractor_status_code").focus();
            return false; 
        }else{ 
            return true;
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
    <!-- /.panel-heading -->
    <div class="panel-body">
        <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=contractor&action=edit" >
            <div class="row"> 
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="contractor_prefix" name="contractor_prefix" class="form-control">
                            <option value="">Select</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นาย'){?> selected <?php } ?> >นาย</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นาง'){?> selected <?php } ?> >นาง</option>
                            <option <?php if($contractor['contractor_prefix'] == 'นางสาว'){?> selected <?php } ?> >นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_name" name="contractor_name" class="form-control" value="<?php echo $contractor['contractor_name']?>">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_lastname" name="contractor_lastname" class="form-control" value="<?php echo $contractor['contractor_lastname']?>">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>อีเมล์ / Email </label>
                        <input id="contractor_email" name="contractor_email" type="email" class="form-control" value="<?php echo $contractor['contractor_email']?>">
                        <p class="help-block">Example : admin@arno.co.th.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>โทรศัพท์ / Mobile </label>
                        <input id="contractor_mobile" name="contractor_mobile" type="text" class="form-control" value="<?php echo $contractor['contractor_mobile']?>">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>ยูสเซอร์ / Username <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_username" name="contractor_username" class="form-control" value="<?php echo $contractor['contractor_username']?>">
                        <p class="help-block">Example : thana.</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>รหัสผ่าน / Password <font color="#F00"><b>*</b></font></label>
                        <input id="contractor_password" name="contractor_password" type="password" class="form-control" value="<?php echo $contractor['contractor_password']?>">
                        <p class="help-block">Example : thanaadmin.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="contractor_address" name="contractor_address" class="form-control select" value="<?php echo $contractor['contractor_address']?>">
                        <p class="help-block">Example : 271/55.</p>
                    </div>
                </div>
            </div>
            <!-- /.row (nested) -->

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>จังหวัด / Province <font color="#F00"><b>*</b></font> </label>
                        <select id="province_id" name="province_id" class="form-control select" onchange="getAmphur()">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($add_province) ; $i++){
                            ?>
                            <option <?php if($contractor['province_id'] == $add_province[$i]['PROVINCE_NAME'] ){?> selected <?php } ?> value="<?php echo $add_province[$i]['PROVINCE_NAME'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
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
                        <select id="amphur_id" name="amphur_id"  class="form-control select" onchange="getDistrict()">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($add_amphur) ; $i++){
                            ?>
                            <option <?php if($contractor['amphur_id'] == $add_amphur[$i]['AMPHUR_NAME'] ){?> selected <?php } ?> value="<?php echo $add_amphur[$i]['AMPHUR_NAME'] ?>"><?php echo $add_amphur[$i]['AMPHUR_NAME'] ?></option>
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
                            <option <?php if($contractor['district_id'] == $add_district[$i]['DISTRICT_NAME'] ){?> selected <?php } ?> value="<?php echo $add_district[$i]['DISTRICT_NAME'] ?>"><?php echo $add_district[$i]['DISTRICT_NAME'] ?></option>
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
                        <input id="contractor_zipcode" name="contractor_zipcode" type="text" readonly class="form-control" value="<?php echo $contractor['contractor_zipcode']?>">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                        <select class="form-control" id="contractor_status_code" name="contractor_status_code">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($contractor_status) ; $i++){
                            ?>
                            <option <?php if($contractor['contractor_status_code'] == $contractor_status[$i]['contractor_status_code'] ){?> selected <?php } ?> value="<?php echo $contractor_status[$i]['contractor_status_code'] ?>"><?php echo $contractor_status[$i]['contractor_status_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : ทำงาน.</p>
                    </div>
                </div> 
            </div>
            <!-- /.row (nested) -->
            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=contractor" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

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
</script>