<script>
    function check(){
        var village_name = document.getElementById("village_name").value;
        var province = document.getElementById("province").value;
        var amphur = document.getElementById("amphur").value;
        var district = document.getElementById("district").value;

        village_name = $.trim(village_name);

        if(village_name.length == 0){
            alert("Please input visit name");
            document.getElementById("village_name").focus();
            return false;
        }else if(province.length == 0){
            alert("Please select province");
            document.getElementById("province").focus();
            return false;
        }else if(amphur.length == 0){
            alert("Please select amphur");
            document.getElementById("amphur").focus();
            return false;
        }else if(district.length == 0){
            alert("Please select district");
            document.getElementById("district").focus();
            return false;
        }else{ 
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการแบบฟอร์มเยี่ยมชม / Visit Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เเก้ไขพื้นที่ / Add Visit
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=visit&action=edit-list" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>จังหวัด : <font color="#F00"><b>*</b></font></label>
                        <select id="province_id" name="province_id" data-live-search="true" class="form-control select" onchange="getAmphur()">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($province); $i++){
                            ?>
                            <option <?php if($visit_list['PROVINCE_ID'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : นครราชสีมา.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>อำเภอ : <font color="#F00"><b>*</b></font></label>
                        <select id="amphur_id" name="amphur_id" data-live-search="true" class="form-control select" onchange="getDistrict()">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($amphur); $i++){
                            ?>
                            <option <?php if($visit_list['AMPHUR_ID'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ตำบล : <font color="#F00"><b>*</b></font></label>
                        <select id="district_id" name="district_id" data-live-search="true" class="form-control select" onchange="getVillage()">
                            <option value="">Select</option>
                            <?php 
                            for($i=0 ;$i<count($district); $i++){
                            ?>
                            <option <?php if($visit_list['DISTRICT_ID'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>หมู่บ้าน : Village <font color="#F00"><b>*</b></font> </label>
                        <select id="village_id" name="village_id" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i=0 ;$i<count($village); $i++){
                            ?>
                            <option <?php if($visit_list['VILLAGE_ID'] == $village[$i]['VILLAGE_ID'] ){?> selected <?php } ?> value="<?php echo $village[$i]['VILLAGE_ID'] ?>"><?php echo $village[$i]['VILLAGE_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : บ้าน.</p>
                    </div>
                </div>
            </div>

            <div class="row"> 
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>นายหน้า : </label>
                        <select id="agent_code" name="agent_code" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i= 0; $i<count($agent); $i++){
                            ?>
                            <option <?php if($visit_list['agent_code'] == $agent[$i]['agent_code'] ){?> selected <?php } ?> value="<?php echo $agent[$i]['agent_code']?>"><?php echo $agent[$i]['name']?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>ตัวเเทนกองทุนหมู่บ้าน : </label>
                        <select id="dealer_code" name="dealer_code" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i=0; $i<count($dealer); $i++){
                            ?>
                            <option <?php if($visit_list['dealer_code'] == $dealer[$i]['dealer_code'] ){?> selected <?php } ?> value="<?php echo $dealer[$i]['dealer_code']?>"><?php echo $dealer[$i]['name']?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=visit&action=update&code=<?php echo $visit_list['visit_code']; ?>" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" name="visit_code" value="<?php echo $visit_list['visit_code']; ?>">
            <input type="hidden" name="visit_list_code" value="<?php echo $visit_list_code; ?>">
        </form>
    </div>
</div>
<script>
    function getAmphur(){
        var province = document.getElementById("province_id").value;

        $.post("controllers/getAmphur.php", { province: province }, function( data ) {
            $("#amphur_id").html(data);
            $("#amphur_id").selectpicker('refresh');
        });

        document.getElementById("amphur_id").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur_id").value;

        $.post("controllers/getDistrict.php", { amphur: amphur }, function( data ) {
            $("#district_id").html(data);
            $("#district_id").selectpicker('refresh');
        });
    }

    function getVillage(){
        var district = document.getElementById("district_id").value;

        $.post("controllers/getVillage.php", { district: district }, function( data ) {
            $("#village_id").html(data);
            $("#village_id").selectpicker('refresh');
        });

        $.post("modules/visit/controllers/getAgentByDistrict.php", { district: district }, function( data ) {
            $("#agent_code").html(data);
            $("#agent_code").selectpicker('refresh');
        });

        $.post("modules/visit/controllers/getDealerByDistrict.php", { district: district }, function( data ) {
            $("#dealer_code").html(data);
            $("#dealer_code").selectpicker('refresh');
        });
    }
</script>