<script>
    function check_code(){
        var code = document.getElementById("zone_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : ZONE0001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/zone/controllers/getZoneByCode.php", { zone_code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("zone_code").focus();
                        $('#alert_code').html('This code : '+code+' is already in the system.');
                        $('#alert_code').addClass('alert-danger');
                        $('#alert_code').removeClass('alert-success');
                    }else{
                        $('#alert_code').html('This code : '+code+' can be used.');
                        $('#alert_code').removeClass('alert-danger');
                        $('#alert_code').addClass('alert-success');
                    }
            });
        }
    }

    function check(){
        var code = document.getElementById("zone_code").value;
        var zone_name = document.getElementById("zone_name").value;
        var zone_description = document.getElementById("zone_description").value;

        code = $.trim(code);
        zone_name = $.trim(zone_name);
        zone_description = $.trim(zone_description);

        if(zone_name.length == 0){
            alert('Please input zone name');
            document.getElementById("zone_name").focus();
            return false;
        }else if(code.length != 0 && $('#alert_code').hasClass('alert-danger')){
            alert('This code : '+code+' is already in the system.');
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
        เพิ่มเขตการขาย / Add zone
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=zone&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสเขตการขาย : </label>
                        <input id="zone_code" name="zone_code" class="form-control" autocomplete="off" onchange="check_code();" maxlength="50">
                        <p id="alert_code" class="help-block">Example : ZONE0001.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อเขตการขาย : <font color="#F00"><b>*</b></font></label>
                        <input id="zone_name" name="zone_name" class="form-control" autocomplete="off" maxlength="150">
                        <p class="help-block">Example : โคราช-ในเมือง.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>รายละเอียด : </label>
                        <input id="zone_description" name="zone_description" class="form-control" autocomplete="off" maxlength="200"
                        <p class="help-block">Example : รายละเอียด.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=zone" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>