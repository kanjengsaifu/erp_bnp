<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการแบบฟอร์มเยี่ยมชม / Visit Management</h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อแบบฟอร์มเยี่ยมชม / Visit List
            </div>
            <div class="col-md-4">
                <?php if($menu['visit']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=visit&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
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
                    <th style="text-align:center;">แบบฟอร์มเยี่ยมชม <br>Sales area</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i<count($visit); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td ><?php echo $visit[$i]['visit_code']; ?></td>
                    <td ><?php echo $visit[$i]['visit_name']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['visit']['edit']){ ?> 
                        <a href="?app=visit&action=update&code=<?php echo $visit[$i]['visit_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['visit']['delete']){ ?> 
                        <a href="?app=visit&action=delete&code=<?php echo $visit[$i]['visit_code'];?>" onclick="return confirm('You want to delete visit : <?php echo $visit[$i]['name']; ?>');" style="color:red;">
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