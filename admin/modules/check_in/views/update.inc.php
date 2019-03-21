<script>
    function check(){
        var check_in_topic = document.getElementById("check_in_topic").value;
        var check_in_type_code = document.getElementById("check_in_type_code").value;

        check_in_topic = $.trim(check_in_topic);

        if(check_in_topic.length == 0){
            alert('Please input check in topic');
            document.getElementById("check_in_topic").focus();
            return false;
        }else if(check_in_type_code.length == 0){
            alert('Please select type');
            document.getElementById("check_in_type_code").focus();
            return false;
        }else{ 
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการหัวข้อการเช็คอิน / CheckIn Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขหัวข้อการเช็คอิน / Edit Check-In Topic
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=check_in&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสหัวข้อการเช็คอิน : </label>
                        <input id="check_in_code" name="check_in_code" class="form-control" value="<? echo $check_in['check_in_code']; ?>" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="form-group">
                        <label>ชื่อหัวข้อการเช็คอิน : <font color="#F00"><b>*</b></font></label>
                        <input id="check_in_topic" name="check_in_topic" class="form-control" value="<? echo $check_in['check_in_topic']; ?>" autocomplete="off" maxlength="150">
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
                            for($i=0; $i<count($check_in_type); $i++){
                            ?>
                            <option <?php if($check_in['check_in_type_code'] == $check_in_type[$i]['check_in_type_code'] ){?> selected <?php } ?> value="<?php echo $check_in_type[$i]['check_in_type_code'] ?>"><?php echo $check_in_type[$i]['check_in_type_name'] ?></option>
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
                        <input id="score" name="score" class="form-control" value="<? echo $check_in['score']; ?>" autocomplete="off" maxlength="3">
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

            <input type="hidden" id="check_in_code" name="check_in_code" value="<?php echo $check_in_code ?>">
        </form>
    </div>
</div>