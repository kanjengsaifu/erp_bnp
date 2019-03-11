<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการตัวเเทนกองทุน / Fund Agent Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลตัวเเทนกองทุน / fund agent infomation
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>รูป / image</td>
                    <td>
                        <div class="form-group" align="center">
                            <img src="../upload/<?php if($fund_agent['fund_agent_image'] != "") echo 'fund_agent/'.$fund_agent['fund_agent_image']; else echo "default.png" ?>" style="width: 100%;max-width: 240px;"> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ชื่อ / Name</td>
                    <td><?php echo $fund_agent['fund_agent_prefix']?><?php echo $fund_agent['fund_agent_name']?> <?php echo $fund_agent['fund_agent_lastname']?></td>
                </tr>
                <tr>
                    <td>สถานะ / Status</td>
                    <td><?php echo $fund_agent['status_name']?></td>
                </tr>
                <tr>
                    <td>โทรศัพท์ / Mobile</td>
                    <td><?php echo $fund_agent['fund_agent_mobile']?></td>
                </tr>
                <tr>
                    <td>ที่อยู่ / Address</td>
                    <td><?php echo $fund_agent['fund_agent_address']?></td>
                </tr>
                <tr>
                    <td>จังหวัด / Province</td>
                    <td><?php echo $fund_agent['PROVINCE_NAME']?></td>
                </tr>
                <tr>
                    <td>อำเภอ / Amphur</td>
                    <td><?php echo $fund_agent['AMPHUR_NAME']?></td>
                </tr>
                <tr>
                    <td>ตำบล / Distict</td>
                    <td><?php echo $fund_agent['DISTRICT_NAME']?></td>
                </tr>
                <tr>
                    <td>เลขไปรษณีย์ / Zipcode</td>
                    <td><?php echo $fund_agent['fund_agent_zipcode']?></td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-4">
                <label>สำเนาบัตรประชาชน / Copy of ID card </label>
                <div class="form-group" align="center">
                    <img id="img_id_card" src="../upload/<?php if($fund_agent['id_card_image'] != "") echo 'fund_agent/'.$fund_agent['id_card_image']; else echo "default.png" ?>" style="width: 100%;max-width: 320px;"> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-9 col-lg-3" align="right">
                <form role="form" method="post" action="index.php?app=fund_agent&action=approve" enctype="multipart/form-data">
                    <a class="btn btn-default" href="?app=fund_agent">Back</a>
                    <?php if($fund_agent['status_code'] == '00'){ ?>
                    <button class="btn btn-success" type="submit">Approve</button>
                    <?php } ?>
                    <button class="btn btn-primary">Print</button>
                    <input type="hidden" id="fund_agent_code" name="fund_agent_code" value="<?php echo $fund_agent_code; ?>">
                </form>
            </div>
        </div>
    </div>
</div>