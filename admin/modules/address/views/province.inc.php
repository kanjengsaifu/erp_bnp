<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการข้อมูลพื้นที่ / Area Management</h1>
        <a class="btn btn-default" href="?app=address">Back</a>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลจังหวัด / Province information 
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="180px">รหัสจังหวัด</td>
                <td><? echo $province['PROVINCE_CODE']; ?></td>
            </tr>
            <tr>
                <td>จังหวัด</td>
                <td><?php echo $province['PROVINCE_NAME']?></td>
            </tr>
            <tr>
                <td>จำนวนอำเภอ</td>
                <td><?php echo $province['Amphur']?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        รายชื่ออำเภอ / Amphur List
    </div>
    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัสอำเภอ<br>Code</th>
                    <th style="text-align:center;">อำเภอ<br>Amphur</th>
                    <th style="text-align:center;">ตำบล<br>District</th>
                    <th style="text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($amphur); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $amphur[$i]['AMPHUR_CODE']; ?></td>
                    <td style="text-align:center;"><?php echo $amphur[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $amphur[$i]['District']; ?></td>
                    <td style="text-align:center;">
                        <a href="?app=address&action=amphur&amphur=<?php echo $amphur[$i]['AMPHUR_ID'];?>">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>