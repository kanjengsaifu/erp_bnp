<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการนายหน้า / Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลนายหน้า / agent infomation
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>รูป / image</td>
                    <td>
                        <div class="form-group" align="center">
                            <img src="../upload/<?php if($agent['profile_image'] != "") echo 'agent/'.$agent['profile_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $agent['agent_prefix']?><?php echo $agent['agent_name']?> <?php echo $agent['agent_lastname']?></td>
                </tr>
                <tr>
                    <td>สถานะ / Status</td>
                    <td><?php echo $agent['status_name']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $agent['agent_mobile']?></td>
                </tr>
                <tr>
                    <td>ไลน์ไอดี / LINE ID</td>
                    <td><?php echo $agent['agent_line']?></td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td><?php echo $agent['agent_address']?></td>
                </tr>
                <tr>
                    <td>จังหวัด / Province</td>
                    <td><?php echo $agent['PROVINCE_NAME']?></td>
                </tr>
                <tr>
                    <td>อำเภอ / Amphur</td>
                    <td><?php echo $agent['AMPHUR_NAME']?></td>
                </tr>
                <tr>
                    <td>ตำบล / Distict</td>
                    <td><?php echo $agent['DISTRICT_NAME']?></td>
                </tr>
                <tr>
                    <td>เลขไปรษณีย์ / Zipcode</td>
                    <td><?php echo $agent['agent_zipcode']?></td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($agent['id_card_image'] != "") echo 'agent/'.$agent['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=agent&action=approve" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=agent">Back</a>
                    <?php if($agent['status_code'] == '00'){ ?>
                    <button class="btn btn-success" type="submit">Approve</button>
                    <?php } ?>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="agent_code" name="agent_code" value="<?php echo $agent_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>