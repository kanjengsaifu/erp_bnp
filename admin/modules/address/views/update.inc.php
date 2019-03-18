<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการข้อมูลพื้นที่ / Area Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เเก้ไขข้อมูลหมู่บ้าน / Edit village information
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=address&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสหมู่บ้าน : </label>
                        <input id="village_code" name="village_code" class="form-control" autocomplete="off" value="<? echo $village['VILLAGE_CODE']; ?>" maxlength="10">
                        <p class="help-block">Example : 10010101.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>หมู่ที่ : </label>
                        <input id="village_no" name="village_no" class="form-control integer" autocomplete="off" value="<? echo $village['VILLAGE_NO']; ?>" maxlength="2">
                        <p class="help-block">Example : 1.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-8 col-lg-6">
                    <div class="form-group">
                        <label>ชื่อหมู่บ้าน : <font color="#F00"><b>*</b></font></label>
                        <input id="village_name" name="village_name" class="form-control" autocomplete="off" value="<? echo $village['VILLAGE_NAME']; ?>" maxlength="150">
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
                            for($i=0; $i<count($province); $i++){
                            ?>
                            <option <?php if($village['PROVINCE_ID'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <?php 
                            for($i=0; $i<count($amphur); $i++){
                            ?>
                            <option <?php if($village['AMPHUR_ID'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
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
                            <option value="">Select</option>
                            <?php 
                            for($i=0 ;$i<count($district); $i++){
                            ?>
                            <option <?php if($village['DISTRICT_ID'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="village_id" value="<?php echo $village_id; ?>">

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=address&action=district&district=<?php echo $village['DISTRICT_ID']; ?>" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
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
</script>