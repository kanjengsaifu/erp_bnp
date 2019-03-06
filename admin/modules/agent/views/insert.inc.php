<script>
    function check_code(id){
        var code = $(id).val();
        $.post("controllers/getAgentByCode.php", { 'agent_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("agent_code").focus();
                $("#code_check").val(data.agent_code);
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){
        var agent_prefix = document.getElementById("agent_prefix").value;
        var agent_name = document.getElementById("agent_name").value;
        var agent_lastname = document.getElementById("agent_lastname").value;
        var agent_mobile = document.getElementById("agent_mobile").value;
        var agent_address = document.getElementById("agent_address").value;
        var province_id = document.getElementById("province_id").value;
        var amphur_id = document.getElementById("amphur_id").value;
        var district_id = document.getElementById("district_id").value;
        var agent_zipcode = document.getElementById("agent_zipcode").value;
        var agent_status_code = document.getElementById("agent_status_code").value;  

        agent_prefix = $.trim(agent_prefix);
        agent_name = $.trim(agent_name);
        agent_lastname = $.trim(agent_lastname);
        agent_mobile = $.trim(agent_mobile);
        agent_address = $.trim(agent_address);
        province_id = $.trim(province_id);
        amphur_id = $.trim(amphur_id);
        district_id = $.trim(district_id);
        agent_zipcode = $.trim(agent_zipcode);
        agent_status_code = $.trim(agent_status_code); 

        if(agent_prefix.length == 0){
            alert("Please input agent prefix");
            document.getElementById("agent_prefix").focus();
            return false;
        }else if(agent_name.length == 0){
            alert("Please input agent name");
            document.getElementById("agent_name").focus();
            return false;
        }else if(agent_lastname.length == 0){
            alert("Please input agent lastname");
            document.getElementById("agent_lastname").focus();
            return false;
        }else if(agent_address.length == 0){
            alert("Please input agent address");
            document.getElementById("agent_address").focus();
            return false;
        }else if(province_id.length == 0){
            alert("Please input agent provice");
            document.getElementById("province_id").focus();
            return false;
        }else if(amphur_id.length == 0){
            alert("Please input agent amphur");
            document.getElementById("amphur_id").focus();
            return false;
        }else if(district_id.length == 0){
            alert("Please input agent district");
            document.getElementById("district_id").focus();
            return false;
        }else if(agent_status_code.length == 0){
            alert("Please input agent status");
            document.getElementById("agent_status_code").focus();
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
        <h1 class="page-header">จัดการนายหน้า / Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มนายหน้า / Add agent
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=agent&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label>คำนำหน้าชื่อ / Prename <font color="#F00"><b>*</b></font></label>
                        <select id="agent_prefix" name="agent_prefix" class="form-control">
                            <option value="">Select</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นาย">นางสาว</option>
                        </select>
                        <p class="help-block">Example : นาย.</p>
                    </div>
                </div> 
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อ / Name <font color="#F00"><b>*</b></font></label>
                        <input id="agent_name" name="agent_name" class="form-control" autocomplete="off">
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-8 col-lg-3">
                    <div class="form-group">
                        <label>นามสกุล / Lastname <font color="#F00"><b>*</b></font></label>
                        <input id="agent_lastname" name="agent_lastname" class="form-control" autocomplete="off">
                        <p class="help-block">Example : ชาญชัย.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>สถานะ / Status <font color="#F00"><b>*</b></font> </label>
                        <select class="form-control" id="agent_status_code" name="agent_status_code">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($agent_status) ; $i++){
                            ?>
                            <option value="<?php echo $agent_status[$i]['agent_status_code'] ?>"><?php echo $agent_status[$i]['agent_status_name'] ?></option>
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
                        <input id="agent_mobile" name="agent_mobile" type="text" class="form-control" autocomplete="off">
                        <p class="help-block">Example : 0610243003.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font> </label>
                        <input type="text" id="agent_address" name="agent_address" class="form-control" autocomplete="off">
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
                            <option value="<?php echo $add_province[$i]['PROVINCE_ID'] ?>"><?php echo $add_province[$i]['PROVINCE_NAME'] ?></option>
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
                        </select>
                        <p class="help-block">Example : เมือง.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ตำบล / Distict <font color="#F00"><b>*</b></font> </label>
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : ในเมือง.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>เลขไปรษณีย์ / Zipcode <font color="#F00"><b>*</b></font> </label>
                        <input id="agent_zipcode" name="agent_zipcode" type="text" readonly class="form-control" autocomplete="off">
                        <p class="help-block">Example : 30000.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label>รูปนายหน้า / Agent image </label>
                    <div class="form-group" align="center">
                        <img id="img_agent" src="../upload/default.png" style="width: 100%;max-width: 240px;"> 
                        <input accept=".jpg , .png" type="file" id="agent_image" name="agent_image" class="form-control" style="margin-top: 14px" onChange="readURL(this);">
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
                    <a href="?app=agent" class="btn btn-default">Back</a>
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
            $("#agent_zipcode").val(data);
        });
    }
</script>