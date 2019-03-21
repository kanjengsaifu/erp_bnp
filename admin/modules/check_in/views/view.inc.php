<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการหัวข้อการเช็คอิน / CheckIn Management</h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อหัวข้อการเช็คอิน / CheckIn List
            </div>
            <div class="col-md-4">
                <?php if($menu['check_in']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=check_in&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
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
                    <th style="text-align:center;">หัวข้อการเช็คอิน <br>Check-In topics</th>
                    <th style="text-align:center;">ประเภท <br>Check-In Type</th>
                    <th style="text-align:center;">คะแนน <br>Score</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($check_in); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td ><?php echo $check_in[$i]['check_in_code']; ?></td>
                    <td ><?php echo $check_in[$i]['check_in_topic']; ?></td>
                    <td ><?php echo $check_in[$i]['check_in_type_name']; ?></td>
                    <td style="text-align:center;"><?php echo $check_in[$i]['score']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['check_in']['edit']){ ?> 
                        <a href="?app=check_in&action=update&code=<?php echo $check_in[$i]['check_in_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['check_in']['delete']){ ?> 
                        <a href="?app=check_in&action=delete&code=<?php echo $check_in[$i]['check_in_code'];?>" onclick="return confirm('You want to delete check_in : <?php echo $check_in[$i]['name']; ?>');" style="color:red;">
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