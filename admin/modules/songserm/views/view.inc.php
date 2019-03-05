<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการส่งเสริม / Songserm Management</h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อส่งเสริม / Songserm List
            </div>
            <div class="col-md-4">
                <?php if($menu['songserm']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=songserm&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
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
                    <th style="text-align:center;">สถาณะ <br>Status</th>
                    <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                    <th style="text-align:center;">อีเมล์ <br>Email</th>
                    <th style="text-align:center;">สถานะ <br>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($songserm); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $songserm[$i]['songserm_code']; ?></td>
                    <td><?php echo $songserm[$i]['songserm_prefix'].$songserm[$i]['name']; ?></td>
                    <td><?php echo $songserm[$i]['songserm_status_name']; ?></td>
                    <td class="center"><?php echo $songserm[$i]['songserm_mobile']; ?></td>
                    <td class="center"><?php echo $songserm[$i]['songserm_email']; ?></td>
                    <td class="center"><?php echo $songserm[$i]['songserm_status_name']; ?></td>
                    <td>
                    <?php if($menu['songserm']['edit']){ ?> 
                        <a href="?app=songserm&action=update&code=<?php echo $songserm[$i]['songserm_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['songserm']['delete']){ ?> 
                        <a href="?app=songserm&action=delete&code=<?php echo $songserm[$i]['songserm_code'];?>" onclick="return confirm('You want to delete songserm : <?php echo $songserm[$i]['name']; ?>');" style="color:red;">
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