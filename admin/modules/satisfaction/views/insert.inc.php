<script> 

    function getMember(id){ 
        if(id=='ผู้รับเหมา'){   
            $.post("modules/satisfaction/controllers/getContractor.php", { 'user_code': '<?PHP echo $login_user['user_code'];?>' }, function( data ) {
                // console.log(data);
                $("#member_code").html(data);
                $("#member_code").selectpicker('refresh');
            }); 
            document.getElementById("member_code").value = "";
        }else if(id=='ตัวแทน'){   
            $.post("modules/satisfaction/controllers/getFundAgent.php", { 'user_code': '<?PHP echo $login_user['user_code'];?>' }, function( data ) {
                // console.log(data);
                $("#member_code").html(data);
                $("#member_code").selectpicker('refresh');
            }); 
            document.getElementById("member_code").value = "";
        }else if(id=='นายหน้า'){   
            $.post("modules/satisfaction/controllers/getAgent.php", { 'user_code': '<?PHP echo $login_user['user_code'];?>' }, function( data ) {
                // console.log(data);
                $("#member_code").html(data);
                $("#member_code").selectpicker('refresh');
            }); 
            document.getElementById("member_code").value = "";
        }else if(id=='เกษตรกร'){   
            $.post("modules/satisfaction/controllers/getFarmer.php", { 'user_code': '<?PHP echo $login_user['user_code'];?>' }, function( data ) {
                // console.log(data);
                $("#member_code").html(data);
                $("#member_code").selectpicker('refresh');
            }); 
            document.getElementById("member_code").value = "";
        }else{ 
            $("#member_code").html('<option value="">Select</option>'); 
            $("#member_code").selectpicker('refresh'); 
        }
    } 
    
    function check(){
        var check_status = true;
        var check_empty_data = [
            "member_type",
            "member_code",
            "contact_way_code",
            "contact_type_code",
            "satisfaction_detail",
            "satisfaction_score",
        ];
        var check_empty_alert = [
            "Please input member type",
            "Please input contact name",
            "Please input contact way ",
            "Please input contact type ",
            "Please input detail",
            "Please input score",
        ]; 

        for(i=0;i<check_empty_data.length;i++){ 
            var data = document.getElementById(check_empty_data[i]).value;
            data = $.trim(data);  
            if(data.length == 0){
                alert(check_empty_alert[i]);
                document.getElementById(check_empty_data[i]).focus();
                check_status = false;
                break;
            }
        }  
        return check_status; 
    } 

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Material Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['contact_way']['view']==1){ ?> 
        <a href="?app=contact_way" class="btn btn-primary btn-menu ">ช่องทางติดต่อ / Contact way</a>
        <?PHP }?>
        <?php if($menu['contact_type']['view']==1){ ?> 
        <a href="?app=contact_type" class="btn btn-primary btn-menu ">ประเภทการติดต่อ / Contact type</a>
        <?PHP }?>
        <?php if($menu['satisfaction']['view']==1){ ?> 
        <a href="?app=satisfaction" class="btn btn-primary btn-menu active">ความพึงพอใจ / Satisfaction</a> 
        <?PHP }?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มวัตถุดิบ / Add Material 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=satisfaction&action=add" enctype="multipart/form-data">
                 
                    <div class="row"> 
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ประเภทผู้ติดต่อ / Member type <font color="#F00"><b>*</b></font> </label>
                                <select id="member_type" name="member_type" class="form-control" onchange="getMember(this.value)">
                                    <option value="">Select</option> 
                                    <option value="ผู้รับเหมา">ผู้รับเหมา</option>
                                    <option value="ตัวแทน">ตัวแทน</option>
                                    <option value="นายหน้า">นายหน้า</option>
                                    <option value="เกษตรกร">เกษตรกร</option>
                                </select>
                                <p class="help-block">Example : ลิตร.</p>
                            </div>
                        </div>  
                        <div class="col-lg-6">   
                            <div class="form-group">
                                <label>ผู้ติดต่อ / Contact name <font color="#F00"><b>*</b></font> </label>
                                <select id="member_code" name="member_code" data-live-search="true" class="form-control select" >
                                <option value="">Select</option>
                                </select>
                                <p class="help-block">Example : Thana.</p>
                            </div>
                        </div>  
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ช่องทางติดต่อ / Contact way <font color="#F00"><b>*</b></font> </label>
                                <select id="contact_way_code" name="contact_way_code" class="form-control">
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($contact_way) ; $i++){
                                        ?>
                                        <option value="<?php echo $contact_way[$i]['contact_way_code'] ?>"><?php echo $contact_way[$i]['contact_way_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                <p class="help-block">Example : ลิตร.</p>
                            </div>
                        </div>   
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ประเภทการติดต่อ / Contact type <font color="#F00"><b>*</b></font> </label>
                                <select id="contact_type_code" name="contact_type_code" class="form-control">
                                        <option value="">Select</option>
                                        <?php 
                                        for($i =  0 ; $i < count($contact_type) ; $i++){
                                        ?>
                                        <option value="<?php echo $contact_type[$i]['contact_type_code'] ?>"><?php echo $contact_type[$i]['contact_type_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                <p class="help-block">Example : ลิตร.</p>
                            </div>
                        </div>   
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>รายละเอียด / Detail <font color="#F00"><b>*</b></font></label>
                                <input id="satisfaction_detail" name="satisfaction_detail" class="form-control" value=""  >
                                <p class="help-block">Example : รายละเอียด...</p>
                            </div>
                        </div>  
                    </div> 
                    <!-- /.row (nested) -->  
                    <div class="row">  
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>คะแนน (10/10) / Score <font color="#F00"><b>*</b></font></label>
                                <input id="satisfaction_score" name="satisfaction_score" type="number" class="form-control" value="" min="0" max="10" >
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div> 
                    </div>     
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="submit"  class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
 