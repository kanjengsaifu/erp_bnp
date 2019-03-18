<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการข้อมูลพื้นที่ / Area Management</h1>
        <a class="btn btn-default" href="?app=address&action=province&province=<?php echo $amphur['PROVINCE_ID']; ?>">Back</a>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        ข้อมูลอำเภอ / amphur information 
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="180px">รหัสอำเภอ</td>
                <td><? echo $amphur['AMPHUR_CODE']; ?></td>
            </tr>
            <tr>
                <td>อำเภอ</td>
                <td><?php echo $amphur['AMPHUR_NAME']?></td>
            </tr>
            <tr>
                <td>จังหวัด</td>
                <td>
                    <a href="?app=address&action=province&province=<?php echo $amphur['PROVINCE_ID']; ?>">
                        <?php echo $amphur['PROVINCE_NAME']; ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>จำนวนตำบล</td>
                <td><?php echo $amphur['District']?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        รายชื่อตำบล / District List
    </div>
    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัสตำบล<br>Code</th>
                    <th style="text-align:center;">ตำบล<br>Distict</th>
                    <th style="text-align:center;">หมู่บ้าน<br>Village</th>
                    <th style="text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($district); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $district[$i]['DISTRICT_CODE']; ?></td>
                    <td style="text-align:center;"><?php echo $district[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $district[$i]['Village']; ?></td>
                    <td style="text-align:center;">
                        <a href="?app=address&action=district&district=<?php echo $district[$i]['DISTRICT_ID'];?>">
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