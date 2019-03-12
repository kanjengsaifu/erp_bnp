<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการเกษตรกร / Farmer Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลเกษตรกร /  infomation
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>รูป / image</td>
                    <td>
                        <div class="form-group" align="center">
                            <img src="../upload/<?php if($farmer['profile_image'] != "") echo 'farmer/'.$farmer['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $farmer['farmer_prefix']?><?php echo $farmer['farmer_name']?> <?php echo $farmer['farmer_lastname']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $farmer['farmer_mobile']?></td>
                </tr>
                <tr>
                    <td>ไลน์ไอดี / LINE ID</td>
                    <td><?php echo $farmer['farmer_line']?></td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td>
                        <?php echo $farmer['farmer_address']?>
                        ตำบล<?php echo $farmer['AMPHUR_NAME']?>
                        อำเภอ<?php echo $farmer['AMPHUR_NAME']?>
                        จังหวัด<?php echo $farmer['PROVINCE_NAME']?>
                        <?php echo $farmer['farmer_zipcode']?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=farmer&action=approve" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=farmer">Back</a>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="farmer_code" name="farmer_code" value="<?php echo $farmer_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>