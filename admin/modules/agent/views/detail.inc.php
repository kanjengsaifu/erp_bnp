<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">ข้อมูลนายหน้า / Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        นายหน้า / agent 
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <label>คำนำหน้าชื่อ / Prename </label>
                    <input class="form-control" value="<?php echo $agent['agent_prefix']?>" readonly>
                </div>
            </div> 
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>ชื่อ / Name </label>
                    <input class="form-control" value="<?php echo $agent['agent_name']?>" readonly>
                </div>
            </div>
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>นามสกุล / Lastname </label>
                    <input class="form-control" value="<?php echo $agent['agent_lastname']?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>สถานะ / Status  </label>
                    <input class="form-control" value="<?php echo $agent['agent_status_name']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>โทรศัพท์ / Mobile </label>
                    <input type="text" class="form-control" value="<?php echo $agent['agent_mobile']?>" readonly>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>ที่อยู่ / Address  </label>
                    <input type="text" class="form-control" value="<?php echo $agent['agent_address']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>จังหวัด / Province  </label>
                    <input type="text" class="form-control" value="<?php echo $agent['PROVINCE_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>อำเภอ / Amphur  </label>
                    <input type="text" class="form-control" value="<?php echo $agent['AMPHUR_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>ตำบล / Distict  </label>
                    <input type="text" class="form-control" value="<?php echo $agent['DISTRICT_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>เลขไปรษณีย์ / Zipcode  </label>
                    <input id="agent_zipcode" name="agent_zipcode" type="text" readonly class="form-control" value="<?php echo $agent['agent_zipcode']?>"  readonly>
                </div>
            </div>

            <div class="col-md-12">
                <label>รูปนายหน้า / Agent image </label>
                <div class="form-group" align="center">
                    <img id="img_agent" src="../upload/<?php if($agent['agent_image'] != "") echo 'agent/'.$agent['agent_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($agent['id_card_image'] != "") echo 'agent/'.$agent['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาทะเบียนบ้าน / Copy of House registration </label>
                <div class="form-group" align="center">
                    <img id="img_house_regis" src="../upload/<?php if($agent['house_regis_image'] != "") echo 'agent/'.$agent['house_regis_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาหน้าสมุดบัญชี / Copy of account book page </label>
                <div class="form-group" align="center">
                    <img id="img_account" src="../upload/<?php if($agent['account_image'] != "") echo 'agent/'.$agent['account_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <a href="?app=agent" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>