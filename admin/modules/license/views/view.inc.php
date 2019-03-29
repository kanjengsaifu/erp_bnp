<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">จัดการสิทธิ์การใช้งาน / License Management</h1>
    </div>
    <div class="col-lg-6" align="right">  
        <?php if($menu['user']['view']){ ?> 
        <a href="?app=user" class="btn btn-primary  btn-menu">พนักงาน / Employee</a>
        <?PHP } ?>
        <?php if($menu['license']['view']){ ?> 
        <a href="?app=license" class="btn btn-primary active btn-menu">สิทธิ์การใช้งาน / License</a>
        <?PHP } ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายการสิทธิ์การใช้งาน / License List
            </div>
            <div class="col-md-4">
                <?php if($menu['license']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=license&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th style="text-align:center;">ลำดับ</th>
                    <th style="text-align:center;">รหัส</th> 
                    <th style="text-align:center;">ชื่อ</th> 
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($license); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $license[$i]['license_code']; ?></td> 
                    <td><?php echo $license[$i]['license_name']; ?></td> 
                    <td>
                    <?php if($menu['license']['edit']){ ?> 
                        <a href="?app=license&action=update&code=<?php echo $license[$i]['license_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP } ?>
                    <?php if($menu['license']['delete']){ ?> 
                        <a href="?app=license&action=delete&code=<?php echo $license[$i]['license_code'];?>" onclick="return confirm('You want to delete License : <?php echo $license[$i]['name']; ?>');" style="color:red;">
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