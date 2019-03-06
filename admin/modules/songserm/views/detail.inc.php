<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">ข้อมูลทีมส่งเสริม / Songserm Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ทีมส่งเสริม / Songserm 
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <label>คำนำหน้าชื่อ / Prename </label>
                    <input class="form-control" value="<?php echo $songserm['songserm_prefix']?>" readonly>
                </div>
            </div> 
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>ชื่อ / Name </label>
                    <input class="form-control" value="<?php echo $songserm['songserm_name']?>" readonly>
                </div>
            </div>
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>นามสกุล / Lastname </label>
                    <input class="form-control" value="<?php echo $songserm['songserm_lastname']?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>สถานะ / Status  </label>
                    <input class="form-control" value="<?php echo $songserm['songserm_status_name']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>โทรศัพท์ / Mobile </label>
                    <input type="text" class="form-control" value="<?php echo $songserm['songserm_mobile']?>" readonly>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>ที่อยู่ / Address  </label>
                    <input type="text" class="form-control" value="<?php echo $songserm['songserm_address']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>จังหวัด / Province  </label>
                    <input type="text" class="form-control" value="<?php echo $songserm['PROVINCE_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>อำเภอ / Amphur  </label>
                    <input type="text" class="form-control" value="<?php echo $songserm['AMPHUR_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>ตำบล / Distict  </label>
                    <input type="text" class="form-control" value="<?php echo $songserm['DISTRICT_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>เลขไปรษณีย์ / Zipcode  </label>
                    <input id="songserm_zipcode" name="songserm_zipcode" type="text" readonly class="form-control" value="<?php echo $songserm['songserm_zipcode']?>"  readonly>
                </div>
            </div>

            <div class="col-md-12">
                <label>รูปทีมส่งเสริม / Songserm image </label>
                <div class="form-group" align="center">
                    <img id="img_songserm" src="../upload/<?php if($songserm['songserm_image'] != "") echo 'songserm/'.$songserm['songserm_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($songserm['id_card_image'] != "") echo 'songserm/'.$songserm['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาทะเบียนบ้าน / Copy of House registration </label>
                <div class="form-group" align="center">
                    <img id="img_house_regis" src="../upload/<?php if($songserm['house_regis_image'] != "") echo 'songserm/'.$songserm['house_regis_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาหน้าสมุดบัญชี / Copy of account book page </label>
                <div class="form-group" align="center">
                    <img id="img_account" src="../upload/<?php if($songserm['account_image'] != "") echo 'songserm/'.$songserm['account_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <a href="?app=songserm" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>