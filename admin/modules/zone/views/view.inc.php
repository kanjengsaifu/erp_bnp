<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการเขตการขาย / Zone Management</h1>

	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อเขตการขาย / Zone List
            </div>
            <div class="col-md-4">
                <?php if($menu['zone']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=zone&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No. </th>
                    <th style="text-align:center;">รหัส <br>Code</th>
                    <th style="text-align:center;">เขตการขาย <br>Sales area</th>
                    <th style="text-align:center;">ผู้ช่วยผู้อำนวยการ <br>Assistant director</th>
                    <th style="text-align:center;">ผู้จัดการ <br>Manager</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($zone); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td ><?php echo $zone[$i]['zone_code']; ?></td>
                    <td ><?php echo $zone[$i]['zone_name']; ?></td>
                    <td style="text-align:center;"><?php echo $zone[$i]['assis_director']; ?></td>
                    <td style="text-align:center;"><?php echo $zone[$i]['manager']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['zone']['edit']){ ?> 
                        <a href="?app=zone&action=update&code=<?php echo $zone[$i]['zone_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['zone']['delete']){ ?> 
                        <a href="?app=zone&action=delete&code=<?php echo $zone[$i]['zone_code'];?>" onclick="return confirm('You want to delete zone : <?php echo $zone[$i]['name']; ?>');" style="color:red;">
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