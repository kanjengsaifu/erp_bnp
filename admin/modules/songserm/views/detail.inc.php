<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการนายหน้า / Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลนายหน้า / songserm infomation
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>รูป / image</td>
                    <td>
                        <div class="form-group" align="center">
                            <img src="../upload/<?php if($songserm['profile_image'] != "") echo 'songserm/'.$songserm['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $songserm['songserm_prefix']?><?php echo $songserm['songserm_name']?> <?php echo $songserm['songserm_lastname']?></td>
                </tr>
                <tr>
                    <td>สถานะ / Status</td>
                    <td><?php echo $songserm['songserm_status_name']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $songserm['songserm_mobile']?></td>
                </tr>
                <tr>
                    <td>ไลน์ไอดี / LINE ID</td>
                    <td><?php echo $songserm['songserm_line']?></td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td>
                        <?php echo $songserm['songserm_address']?>
                        ตำบล<?php echo $songserm['AMPHUR_NAME']?>
                        อำเภอ<?php echo $songserm['AMPHUR_NAME']?>
                        จังหวัด<?php echo $songserm['PROVINCE_NAME']?>
                        <?php echo $songserm['songserm_zipcode']?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($songserm['id_card_image'] != "") echo 'songserm/'.$songserm['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=songserm" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=songserm">Back</a>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="songserm_code" name="songserm_code" value="<?php echo $songserm_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>