<script>
    function check_code(){
        var code = document.getElementById("visit_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : ZONE0001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/visit/controllers/getVisitByCode.php", { visit_code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("visit_code").focus();
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
        var code = document.getElementById("visit_code").value;
        var visit_name = document.getElementById("visit_name").value;
        var visit_description = document.getElementById("visit_description").value;

        code = $.trim(code);
        visit_name = $.trim(visit_name);
        visit_description = $.trim(visit_description);

        if(visit_name.length == 0){
            alert('Please input visit name');
            document.getElementById("visit_name").focus();
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
        <h1 class="page-header">จัดการแบบฟอร์มเยี่ยมชม / Visit Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มแบบฟอร์มเยี่ยมชม / Add visit
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=visit&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสแบบฟอร์มเยี่ยมชม : </label>
                        <input id="visit_code" name="visit_code" class="form-control" autocomplete="off" onchange="check_code();" maxlength="50">
                        <p id="alert_code" class="help-block">Example : ZONE0001.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อแบบฟอร์มเยี่ยมชม : <font color="#F00"><b>*</b></font></label>
                        <input id="visit_name" name="visit_name" class="form-control" autocomplete="off" maxlength="150">
                        <p class="help-block">Example : โคราช-ในเมือง.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>รายละเอียด : </label>
                        <input id="visit_description" name="visit_description" class="form-control" autocomplete="off" maxlength="200"
                        <p class="help-block">Example : รายละเอียด.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=visit" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>