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
        เเก้ไขพื้นที่ / Add Zone
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=zone&action=update-list" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-8 col-lg-6">
                    <div class="form-group">
                        <label>ชื่อหมู่บ้าน : <font color="#F00"><b>*</b></font></label>
                        <input id="village_name" name="village_name" class="form-control" autocomplete="off" value="<? echo $zone_list['village_name']; ?>">
                        <p class="help-block">Example : บ้านส้ม.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <span>จังหวัด : <font color="#F00"><b>*</b></font></span>
                        <select id="province" name="province" data-live-search="true" class="form-control select" onchange="getAmphur()">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($province) ; $i++){
                            ?>
                            <option <?php if($zone_list['province_id'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($amphur) ; $i++){
                            ?>
                            <option <?php if($zone_list['amphur_id'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <span>ตำบล : <font color="#F00"><b>*</b></font></span>
                        <select id="district" name="district" data-live-search="true" class="form-control select">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($district) ; $i++){
                            ?>
                            <option <?php if($zone_list['district_id'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=zone&action=update&code=<?php echo $zone_list['zone_code']; ?>" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" name="zone_code" value="<?php echo $zone_list['zone_code']; ?>">
            <input type="hidden" name="zone_list_code" value="<?php echo $zone_list_code; ?>">
        </form>
    </div>
</div>
<script>
    function getAmphur(){
        var province = document.getElementById("province").value;

        $.post("controllers/getAmphur.php", { 'province': province }, function( data ) {
            $("#amphur").html(data);
            $("#amphur").selectpicker('refresh');
        });

        document.getElementById("amphur").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur").value;

        $.post("controllers/getDistrict.php", { 'amphur': amphur }, function( data ) {
            $("#district").html(data);
            $("#district").selectpicker('refresh');
        });
    }
</script>