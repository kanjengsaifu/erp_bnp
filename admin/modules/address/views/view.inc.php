<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการข้อมูลพื้นที่ / Area Management</h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                รายชื่อข้อมูลพื้นที่ / Area List
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No. </th>
                    <th style="text-align:center;">รหัส <br>Code</th>
                    <th style="text-align:center;">จังหวัด <br>Province</th>
                    <th style="text-align:center;">อำเภอ <br>Amphur</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($province); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td ><?php echo $province[$i]['PROVINCE_CODE']; ?></td>
                    <td ><?php echo $province[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $province[$i]['Amphur']; ?></td>
                    <td style="text-align:center;">
                        <a href="?app=address&action=province&province=<?php echo $province[$i]['PROVINCE_ID'];?>">
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