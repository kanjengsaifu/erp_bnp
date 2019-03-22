<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการข้อมูลพื้นที่ / Area Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-8">
                ข้อมูลจังหวัด / district information 
            </div>
            <div class="col-sm-4">
                <a class="btn btn-default" style="float:right;" href="?app=address&action=amphur&amphur=<?php echo $district['AMPHUR_ID']; ?>">Back</a>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="180px">รหัสตำบล</td>
                <td><? echo $district['DISTRICT_CODE']; ?></td>
            </tr>
            <tr>
                <td>ตำบล</td>
                <td><?php echo $district['DISTRICT_NAME']; ?></td>
            </tr>
            <tr>
                <td>อำเภอ</td>
                <td>
                    <a href="?app=address&action=amphur&amphur=<?php echo $district['AMPHUR_ID']; ?>">
                        <?php echo $district['AMPHUR_NAME']; ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>จังหวัด</td>
                <td>
                    <a href="?app=address&action=province&province=<?php echo $district['PROVINCE_ID']; ?>">
                        <?php echo $district['PROVINCE_NAME']; ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>จำนวนหมู่บ้าน</td>
                <td><?php echo $district['Village']; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อหมู่บ้าน / Village List
            </div>
            <div class="col-md-4">
                <?php if($menu['address']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=address&action=insert&district=<?php echo $district['DISTRICT_ID']; ?>"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัสหมู่บ้าน<br>Code</th>
                    <th style="text-align:center;">หมู่ที่<br>Village No.</th>
                    <th style="text-align:center;">หมู่บ้าน<br>Village</th>
                    <th style="text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($village); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $village[$i]['VILLAGE_CODE']; ?></td>
                    <td style="text-align:center;"><?php echo $village[$i]['VILLAGE_NO']; ?></td>
                    <td style="text-align:center;"><?php echo $village[$i]['VILLAGE_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['address']['edit']){ ?> 
                        <a href="?app=address&action=update&village=<?php echo $village[$i]['VILLAGE_ID'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['address']['delete']){ ?> 
                        <a href="?app=address&action=delete&village=<?php echo $village[$i]['VILLAGE_ID'];?>" onclick="return confirm('คุณต้องการลบหมู่บ้าน : <?php echo $village[$i]['VILLAGE_NAME']; ?>');" style="color:red;">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    <?PHP }?>
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>