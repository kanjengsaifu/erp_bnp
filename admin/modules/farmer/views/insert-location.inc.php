<script>
        function check(){
        var location_long = document.getElementById("location_long").value;
        var location_lat = document.getElementById("location_lat").value;

        location_long = $.trim(location_long);
        location_lat = $.trim(location_lat);

        if(location_long.length == 0){
            alert("กรุณาระบุตำเเหน่ง");
            document.getElementById("location_long").focus();
            return false;
        }else if(location_lat.length == 0){
            alert("กรุณาระบุตำเเหน่ง");
            document.getElementById("location_lat").focus();
            return false;
        }else{
            return true;
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
        เพิ่มพิกัด / Add Location
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=farmer&action=add-location" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>LOCATION <font color="#F00"><b>*</b></font></label>
                        <fieldset class="gllpLatlonPicker">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="gllpSearchField form-control" placeholder="ค้นหาตำแหน่ง">
                                </div>
                                <div class="col-md-6">
                                    <input type="button" class="gllpSearchButton btn btn-primary" value="ค้นหา">
                                </div>
                            </div>
                            <div class="row" style="padding:16px 0px;">
                                <div class="col-md-12">
                                    <div class="gllpMap"></div>
                                </div>
                            </div>
                            <div class="row" style="padding:16px 0px;">	
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input required class="gllpLongitude form-control" id="location_long" name="location_long" value="100.5056860485729" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input required class="gllpLatitude form-control" id="location_lat" name="location_lat" value="13.698439421193884" type="text">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="gllpZoom" value="8">
                        </fieldset>
                    </div>
                </div>
            </div>

            <input id="farmer_code" name="farmer_code" value="<?php echo $farmer_code; ?>" type="hidden">

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=farmer&action=update&code=<?php echo $farmer_code; ?>" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://maps-api-ssl.google.com/maps/api/js?key=AIzaSyBPYt_mZGd-2iotzhpiZKw1_GpZ6H9w3vs&sensor=false"></script>

<link rel="stylesheet" type="text/css" href="../template/map/css/jquery-gmaps-latlon-picker.css"/>
<script src="../template/map/js/jquery-gmaps-latlon-picker.js"></script>