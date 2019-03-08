<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">ข้อมูลผู้รับเหมา / Contractor Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ผู้รับเหมา / contractor 
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <label>คำนำหน้าชื่อ / Prename </label>
                    <input class="form-control" value="<?php echo $contractor['contractor_prefix']?>" readonly>
                </div>
            </div> 
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>ชื่อ / Name </label>
                    <input class="form-control" value="<?php echo $contractor['contractor_name']?>" readonly>
                </div>
            </div>
            <div class="col-md-8 col-lg-3">
                <div class="form-group">
                    <label>นามสกุล / Lastname </label>
                    <input class="form-control" value="<?php echo $contractor['contractor_lastname']?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>สถานะ / Status  </label>
                    <input class="form-control" value="<?php echo $contractor['contractor_status_name']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>โทรศัพท์ / Mobile </label>
                    <input type="text" class="form-control" value="<?php echo $contractor['contractor_mobile']?>" readonly>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>ที่อยู่ / Address  </label>
                    <input type="text" class="form-control" value="<?php echo $contractor['contractor_address']?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>จังหวัด / Province  </label>
                    <input type="text" class="form-control" value="<?php echo $contractor['PROVINCE_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>อำเภอ / Amphur  </label>
                    <input type="text" class="form-control" value="<?php echo $contractor['AMPHUR_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>ตำบล / Distict  </label>
                    <input type="text" class="form-control" value="<?php echo $contractor['DISTRICT_NAME']?>" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label>เลขไปรษณีย์ / Zipcode  </label>
                    <input id="contractor_zipcode" name="contractor_zipcode" type="text" readonly class="form-control" value="<?php echo $contractor['contractor_zipcode']?>"  readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <label>รูปผู้รับเหมา / Contractor image </label>
                <div class="form-group" align="center">
                    <img id="img_contractor" src="../upload/<?php if($contractor['contractor_image'] != "") echo 'contractor/'.$contractor['contractor_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($contractor['id_card_image'] != "") echo 'contractor/'.$contractor['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาทะเบียนบ้าน / Copy of House registration </label>
                <div class="form-group" align="center">
                    <img id="img_house_regis" src="../upload/<?php if($contractor['house_regis_image'] != "") echo 'contractor/'.$contractor['house_regis_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
            <div class="col-lg-4">
                <label>สำเนาหน้าสมุดบัญชี / Copy of account book page </label>
                <div class="form-group" align="center">
                    <img id="img_account" src="../upload/<?php if($contractor['account_image'] != "") echo 'contractor/'.$contractor['account_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=contractor&action=approve" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=contractor">Back</a>
                    <button class="btn btn-success" type="submit">Approve</button>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="contractor_code" name="contractor_code" value="<?php echo $contractor_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>