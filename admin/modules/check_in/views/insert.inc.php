<script>
    function check_code(){
        var code = document.getElementById("check_in_code").value;

        code = $.trim(code);

        if(code.length == 0){
            $('#alert_code').html('Example : VQS00001.');
            $('#alert_code').removeClass('alert-danger');
            $('#alert_code').removeClass('alert-success');
        }else{
            $.post("modules/check_in/controllers/getCheckInByCode.php", { check_in_code: code })
                .done(function(data) {
                    if(data != null){ 
                        document.getElementById("check_in_code").focus();
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
        var code = document.getElementById("check_in_code").value;
        var check_in_topic = document.getElementById("check_in_topic").value;
        var check_in_type_code = document.getElementById("check_in_type_code").value;

        code = $.trim(code);
        check_in_topic = $.trim(check_in_topic);
        check_in_type_code = $.trim(check_in_type_code);

        if(check_in_topic.length == 0){
            alert('Please input check in topic');
            document.getElementById("check_in_topic").focus();
            return false;
        }else if(check_in_type_code.length == 0){
            alert('Please select type');
            document.getElementById("check_in_type_code").focus();
            return false;
        }else if($('#alert_code').hasClass('alert-danger')){
            alert('This code : '+code+' is already in the system.');
            return false;
        }else{ 
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการหัวข้อการเช็คอิน / Check-In Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มหัวข้อการเช็คอิน / Add Check-In Topic
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=check_in&action=add" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสหัวข้อการเช็คอิน : </label>
                        <input id="check_in_code" name="check_in_code" class="form-control" autocomplete="off" onchange="check_code();" maxlength="50">
                        <p id="alert_code" class="help-block">Example : VQS00001.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="form-group">
                        <label>ชื่อหัวข้อการเช็คอิน : <font color="#F00"><b>*</b></font></label>
                        <input id="check_in_topic" name="check_in_topic" class="form-control" autocomplete="off" maxlength="150">
                        <p class="help-block">Example : เข้าพบลูกค้า.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>ประเภท : <font color="#F00"><b>*</b></font></label>
                        <select id="check_in_type_code" name="check_in_type_code" data-live-search="true" class="form-control select">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($check_in_type) ; $i++){
                            ?>
                            <option value="<?php echo $check_in_type[$i]['check_in_type_code'] ?>"><?php echo $check_in_type[$i]['check_in_type_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : เช็คอินทั่วไป.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="form-group">
                        <label>คะแนน : </label>
                        <input id="score" name="score" class="form-control" autocomplete="off" maxlength="3">
                        <p class="help-block">Example : 1.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=check_in" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>