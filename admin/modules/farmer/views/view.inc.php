<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการเกษตรกร / Farmer Management</h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อเกษตรกร / Farmer List
            </div>
            <div class="col-md-4">
                <?php if($menu['farmer']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=farmer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr>
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัส <br>Code</th>
                    <th style="text-align:center;">ชื่อ <br>Name</th>
                    <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                    <th style="text-align:center;">ไลน์ไอดี <br>LINE ID</th>
                    <th style="text-align:center;">จังหวัด <br>Province</th>
                    <th style="text-align:center;">อำเภอ <br>Amphur</th>
                    <th style="text-align:center;">ตำบล <br>Distict</th>
                    <th style="text-align:center;">หมู่บ้าน <br>Village</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($farmer); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $farmer[$i]['farmer_code']; ?></td>
                    <td><?php echo $farmer[$i]['farmer_prefix'].$farmer[$i]['name']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['farmer_mobile']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['farmer_line']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $farmer[$i]['VILLAGE_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['farmer']['view']){ ?> 
                        <a href="?app=farmer&action=detail&code=<?php echo $farmer[$i]['farmer_code'];?>">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    <?PHP }?>
                    <?php if($menu['farmer']['edit']){ ?> 
                        <a href="?app=farmer&action=update&code=<?php echo $farmer[$i]['farmer_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['farmer']['delete']){ ?> 
                        <a href="?app=farmer&action=delete&code=<?php echo $farmer[$i]['farmer_code'];?>" onclick="return confirm('You want to delete farmer : <?php echo $farmer[$i]['name']; ?>');" style="color:red;">
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