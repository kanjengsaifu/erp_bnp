<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการตัวเเทนจำหน่าย / Dealer Management</h1>

        <div style="margin-bottom: 10px;">
            <a href="?app=dealer" class="btn btn-primary <?php if ($_GET['status'] != 'pending' && $_GET['status'] != 'cease' ) echo 'active'; ?>">ตัวเเทนจำหน่าย / Dealer List</a>
            <a href="?app=dealer&status=pending" class="btn btn-primary <?php if ($_GET['status'] == 'pending') echo 'active'; ?>">
                รออนุมัติ / Pending <?php if($on_pending) { ?><span class="badge badge-danger" style="display: unset;font-weight: 400;"><? echo $on_pending; ?></span><? } ?>
            </a> 
            <a href="?app=dealer&status=cease" class="btn btn-primary <?php if ($_GET['status'] == 'cease') echo 'active'; ?>">
                ระงับการใช้งาน / Cease
            </a> 
        </div>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายชื่อตัวเเทนจำหน่าย / Dealer List
            </div>
            <div class="col-md-4">
                <?php if($menu['dealer']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=dealer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
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
                    <th style="text-align:center;">กองทุนที่ดูเเล <br>Fund Name</th>
                    <th style="text-align:center;">จังหวัด <br>Province</th>
                    <th style="text-align:center;">อำเภอ <br>Amphur</th>
                    <th style="text-align:center;">ตำบล <br>Distict</th>
                    <th style="text-align:center;">หมู่บ้าน <br>Village</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($dealer); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $dealer[$i]['dealer_code']; ?></td>
                    <td><?php echo $dealer[$i]['dealer_prefix'].$dealer[$i]['name']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['dealer_mobile']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['dealer_fund_name']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $dealer[$i]['VILLAGE_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['dealer']['view']){ ?> 
                        <a href="?app=dealer&action=detail&code=<?php echo $dealer[$i]['dealer_code'];?>">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    <?PHP }?>
                    <?php if($menu['dealer']['edit']){ ?> 
                        <a href="?app=dealer&action=update&code=<?php echo $dealer[$i]['dealer_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['dealer']['delete']){ ?> 
                        <a href="?app=dealer&action=delete&code=<?php echo $dealer[$i]['dealer_code'];?>" onclick="return confirm('You want to delete dealer : <?php echo $dealer[$i]['name']; ?>');" style="color:red;">
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