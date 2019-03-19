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
                            <option <?php if($farmer['PROVINCE_ID'] == $province[$i]['PROVINCE_ID'] ){?> selected <?php } ?> value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
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
                            <option <?php if($farmer['AMPHUR_ID'] == $amphur[$i]['AMPHUR_ID'] ){?> selected <?php } ?> value="<?php echo $amphur[$i]['AMPHUR_ID'] ?>"><?php echo $amphur[$i]['AMPHUR_NAME'] ?></option>
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
                            <option <?php if($farmer['DISTRICT_ID'] == $district[$i]['DISTRICT_ID'] ){?> selected <?php } ?> value="<?php echo $district[$i]['DISTRICT_ID'] ?>"><?php echo $district[$i]['DISTRICT_NAME'] ?></option>
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
                            <option <?php if($farmer['village_id'] == $village[$i]['VILLAGE_ID'] ){?> selected <?php } ?> value="<?php echo $village[$i]['VILLAGE_ID'] ?>"><?php echo $village[$i]['VILLAGE_NAME'] ?></option>
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
                        <input id="farmer_zipcode" name="farmer_zipcode" type="text" class="form-control" value="<?php echo $farmer['POSTCODE']?>" autocomplete="off" readonly>
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

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                พิกัดเกษตรกร / Farmer Location
            </div>
            <div class="col-md-4">
                <?php if($menu['farmer']['add']){?> 
                    <a class="btn btn-success" style="float:right;" href="?app=farmer&action=insert-location&code=<?php echo $farmer_code; ?>"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr>
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">พิกัด <br>Location</th>
                    <th style="text-align:center;">ละติจูด <br>Latitude</th>
                    <th style="text-align:center;">ลองติจูด <br>Longitude</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($location); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;">
                        <fieldset class="gllpLatlonPicker">
                            <div class="gllpMap"></div>
                            <input required class="gllpLongitude form-control" value="<?php echo $location[$i]['location_long']; ?>" type="hidden">
                            <input required class="gllpLatitude form-control" value="<?php echo $location[$i]['location_lat']; ?>" type="hidden">
                            <input type="hidden" class="gllpZoom" value="8"/>
                        </fieldset>
                    </td>
                    <td style="text-align:center;"><?php echo $location[$i]['location_long']; ?></td>
                    <td style="text-align:center;"><?php echo $location[$i]['location_lat']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['farmer']['edit']){ ?> 
                        <a href="?app=farmer&action=update-location&location=<?php echo $location[$i]['location_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['farmer']['delete']){ ?> 
                        <a href="?app=farmer&action=delete-location&location=<?php echo $location[$i]['location_code'];?>" onclick="return confirm('คุณต้องการลบพิกัดนี้ ?');" style="color:red;">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    <?PHP }?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://maps-api-ssl.google.com/maps/api/js?key=AIzaSyBPYt_mZGd-2iotzhpiZKw1_GpZ6H9w3vs&sensor=false"></script>

<link rel="stylesheet" type="text/css" href="../template/map/css/jquery-gmaps-latlon-picker.css"/>
<script src="../template/map/js/jquery-gmaps-latlon-picker.js"></script>

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