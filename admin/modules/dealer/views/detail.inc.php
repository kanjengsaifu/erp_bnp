<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการตัวเเทนจำหน่าย / Dealer Management</h1>
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
                            <img src="../upload/<?php if($dealer['profile_image'] != "") echo 'dealer/'.$dealer['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $dealer['dealer_prefix']?><?php echo $dealer['dealer_name']?> <?php echo $dealer['dealer_lastname']?></td>
                </tr>
                <tr>
                    <td>สถานะ / Status</td>
                    <td><?php echo $dealer['status_name']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $dealer['dealer_mobile']?></td>
                </tr>
                <tr>
                    <td>ไลน์ไอดี / LINE ID</td>
                    <td><?php echo $dealer['dealer_line']?></td>
                </tr>
                <tr>
                    <td>กองทุน / Fund Name</td>
                    <td>
                        <?php echo $dealer['dealer_fund_name']?>
                    </td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td>
                        <?php echo $dealer['dealer_address']?>
                        <?php echo $dealer['VILLAGE_NAME']?>
                        ตำบล<?php echo $dealer['DISTRICT_NAME']?>
                        อำเภอ<?php echo $dealer['AMPHUR_NAME']?>
                        จังหวัด<?php echo $dealer['PROVINCE_NAME']?>
                        <?php echo $dealer['POSTCODE']?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($dealer['id_card_image'] != "") echo 'dealer/'.$dealer['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=dealer&action=approve" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=dealer">Back</a>
                    <?php if($dealer['status_code'] == '00'){ ?>
                    <button class="btn btn-success" type="submit">Approve</button>
                    <?php } ?>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="dealer_code" name="dealer_code" value="<?php echo $dealer_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>