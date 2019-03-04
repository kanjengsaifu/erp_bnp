<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการผู้รับเหมา / Contractor Management</h1>
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
                <a class="btn btn-success " style="float:right;" href="?app=contractor&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัส <br>ID</th>
                    <th style="text-align:center;">ชื่อ <br>Name</th>
                    <th style="text-align:center;">ตำแหน่ง <br>Position</th>
                    <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                    <th style="text-align:center;">อีเมล์ <br>Email</th>
                    <th style="text-align:center;">สถานะ <br>Status</th>
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
                    <td><?php echo $contractor[$i]['name']; ?></td>
                    <td><?php echo $contractor[$i]['contractor_position_name']; ?></td>
                    <td class="center"><?php echo $contractor[$i]['contractor_mobile']; ?></td>
                    <td class="center"><?php echo $contractor[$i]['contractor_email']; ?></td>
                    <td class="center"><?php echo $contractor[$i]['contractor_status_name']; ?></td>
                    <td>
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