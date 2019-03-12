<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการตัวเเทนจำหน่าย / Fund Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลตัวเเทนจำหน่าย / fund agent infomation
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>รูป / image</td>
                    <td>
                        <div class="form-group" align="center">
                            <img src="../upload/<?php if($contractor['profile_image'] != "") echo 'contractor/'.$contractor['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $contractor['contractor_prefix']?><?php echo $contractor['contractor_name']?> <?php echo $contractor['contractor_lastname']?></td>
                </tr>
                <tr>
                    <td>สถานะ / Status</td>
                    <td><?php echo $contractor['status_name']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $contractor['contractor_mobile']?></td>
                </tr>
                <tr>
                    <td>ไลน์ไอดี / LINE ID</td>
                    <td><?php echo $contractor['contractor_line']?></td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td>
                        <?php echo $contractor['contractor_address']?>
                        ตำบล<?php echo $contractor['AMPHUR_NAME']?>
                        อำเภอ<?php echo $contractor['AMPHUR_NAME']?>
                        จังหวัด<?php echo $contractor['PROVINCE_NAME']?>
                        <?php echo $contractor['contractor_zipcode']?>
                    </td>
                </tr>
            </tbody>
        </table>

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
                    <?php if($contractor['status_code'] == '00'){ ?>
                    <button class="btn btn-success" type="submit">Approve</button>
                    <?php } ?>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="contractor_code" name="contractor_code" value="<?php echo $contractor_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>