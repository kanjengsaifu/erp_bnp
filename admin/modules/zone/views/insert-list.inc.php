<script>
    function check(){
        var village_name = document.getElementById("village_name").value;
        var province = document.getElementById("province").value;
        var amphur = document.getElementById("amphur").value;
        var district = document.getElementById("district").value;

        village_name = $.trim(village_name);

        if(village_name.length == 0){
            alert("Please input zone name");
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
        <h1 class="page-header">จัดการเขตการขาย / Zone Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มพื้นที่ / Add Zone
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=zone&action=add-list" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-8">
                    <div class="form-group">
                        <label>ชื่อหมู่บ้าน : <font color="#F00"><b>*</b></font></label>
                        <input id="village_name" name="village_name" class="form-control" autocomplete="off">
                        <p class="help-block">Example : บ้านส้ม.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <span>จังหวัด : <font color="#F00"><b>*</b></font></span>
                        <select id="province" name="province" data-live-search="true" class="form-control select" onchange="getAmphur()">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($province) ; $i++){
                            ?>
                            <option value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <span>อำเภอ : <font color="#F00"><b>*</b></font></span>
                        <select id="amphur" name="amphur" data-live-search="true"  class="form-control select" onchange="getDistrict()">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <span>ตำบล : <font color="#F00"><b>*</b></font></span>
                        <select id="district" name="district" data-live-search="true" class="form-control select" onchange="getDistrictAgent()">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row"> 
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>นายหน้า : </label>
                        <select id="agent_code" name="agent_code" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label>ตัวเเทนกองทุนหมู่บ้าน : </label>
                        <select id="fund_agent_code" name="fund_agent_code" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                        </select>
                        <p class="help-block">Example : วินัย.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=zone&action=update&code=<?php echo $zone_code ?>" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" name="zone_code" value="<?php echo $_GET['code']; ?>">
        </form>
    </div>
</div>

<script>
    function getAmphur(){
        var province = document.getElementById("province").value;

        $.post("controllers/getAmphur.php", { province: province }, function( data ) {
            $("#amphur").html(data);
            $("#amphur").selectpicker('refresh');
        });

        document.getElementById("amphur").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur").value;

        $.post("controllers/getDistrict.php", { amphur: amphur }, function( data ) {
            $("#district").html(data);
            $("#district").selectpicker('refresh');
        });
    }

    function getDistrictAgent(){
        var district = document.getElementById("district").value;

        $.post("modules/zone/controllers/getAgentByDistrict.php", { district: district }, function( data ) {
            $("#agent_code").html(data);
            $("#agent_code").selectpicker('refresh');
        });

        $.post("modules/zone/controllers/getFundAgentByDistrict.php", { district: district }, function( data ) {
            $("#fund_agent_code").html(data);
            $("#fund_agent_code").selectpicker('refresh');
        });
    }
</script>