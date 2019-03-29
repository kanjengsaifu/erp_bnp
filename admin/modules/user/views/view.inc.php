<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">จัดการพนักงาน / Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <?php if($menu['user']['view']){?> 
        <a href="?app=user" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <?PHP } ?>
        <?php if($menu['license']['view']){?> 
        <a href="?app=license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a>
        <?PHP } ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อพนักงาน / Employee List
            </div>
            <div class="col-md-4">
                <?php if($menu['user']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=user&action=insert"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">รหัส <br>Code</th>
                    <th style="text-align:center;">ชื่อ <br>Name</th>
                    <th style="text-align:center;">ตำแหน่ง <br>Position</th>
                    <th style="text-align:center;">สิทธิ์การใช้งาน <br>License</th>
                    <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                    <th style="text-align:center;">อีเมล์ <br>Email</th>
                    <th style="text-align:center;">สถานะ <br>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($user); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $user[$i]['user_code']; ?></td>
                    <td><?php echo $user[$i]['name']; ?></td>
                    <td><?php echo $user[$i]['user_position_name']; ?></td>
                    <td><?php echo $user[$i]['license_name']; ?></td>
                    <td class="center"><?php echo $user[$i]['user_mobile']; ?></td>
                    <td class="center"><?php echo $user[$i]['user_email']; ?></td>
                    <td class="center"><?php echo $user[$i]['user_status_name']; ?></td>
                    <td>
                    <?php if($menu['user']['edit']){ ?> 
                        <a href="?app=user&action=update&code=<?php echo $user[$i]['user_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['user']['delete']){ ?> 
                        <a href="?app=user&action=delete&code=<?php echo $user[$i]['user_code'];?>" onclick="return confirm('You want to delete user : <?php echo $user[$i]['name']; ?>');" style="color:red;">
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