<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการผู้รับเหมา / Contractor Management</h1>

        <div style="margin-bottom: 10px;">
            <a href="?app=contractor" class="btn btn-primary <?php if ($_GET['status'] != 'pending' && $_GET['status'] != 'cease' ) echo 'active'; ?>">ผู้รับเหมา / Contractor List</a>
            <a href="?app=contractor&status=pending" class="btn btn-primary <?php if ($_GET['status'] == 'pending') echo 'active'; ?>">
                รออนุมัติ / Pending <?php if($on_pending) { ?><span class="badge badge-danger" style="display: unset;font-weight: 400;"><? echo $on_pending; ?></span><? } ?>
            </a> 
            <a href="?app=contractor&status=cease" class="btn btn-primary <?php if ($_GET['status'] == 'cease') echo 'active'; ?>">
                พักงาน / Cease
            </a> 
        </div>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อผู้รับเหมา / Contractor List
            </div>
            <div class="col-md-4">
                <?php if($menu['contractor']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=contractor&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
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
                    <th style="text-align:center;">จังหวัด <br>Province</th>
                    <th style="text-align:center;">อำเภอ <br>Amphur</th>
                    <th style="text-align:center;">ตำบล <br>Distict</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($contractor); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $contractor[$i]['contractor_code']; ?></td>
                    <td><?php echo $contractor[$i]['contractor_prefix'].$contractor[$i]['name']; ?></td>
                    <td style="text-align:center;"><?php echo $contractor[$i]['contractor_mobile']; ?></td>
                    <td style="text-align:center;"><?php echo $contractor[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $contractor[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $contractor[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['contractor']['view']){ ?> 
                        <a href="?app=contractor&action=profile&code=<?php echo $contractor[$i]['contractor_code'];?>">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    <?PHP }?>
                    <?php if($menu['contractor']['edit']){ ?> 
                        <a href="?app=contractor&action=update&code=<?php echo $contractor[$i]['contractor_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['contractor']['delete']){ ?> 
                        <a href="?app=contractor&action=delete&code=<?php echo $contractor[$i]['contractor_code'];?>" onclick="return confirm('You want to delete contractor : <?php echo $contractor[$i]['name']; ?>');" style="color:red;">
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