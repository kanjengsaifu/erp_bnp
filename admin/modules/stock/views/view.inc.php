<div class="row">
    <div class="col-md-12">
        <h1>ระบบจัดการคลังสินค้า / Stock</h1>
        <h4 class="page-sub-header">เพิ่ม ลบ เเก้ไขข้อมูลคลังสินค้า</h4>

        <div align="right">
            <?php if($menu['stock']['add']){?> 
                <a class="btn btn-success" href="?app=stock&action=insert" style="margin-bottom: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มคลังสินค้า</a>
            <?PHP } ?>
        </div>
    </div>
</div>

<?php 
for($i=0; $i < count($stock_type); $i++){
    $stock_group = $stock_group_model->getStockGroupByType($stock_type[$i]['stock_type_code']);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $stock_type[$i]['stock_type_name']; ?>
    </div>

    <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
            <thead>
                <tr bgcolor="#92d051">
                    <th width="48px" style="text-align:center;">ลำดับ <br>(No.)</th>
                    <th style="text-align:center;">รหัสคลังสินค้า <br>(Stock Code)</th>
                    <th style="text-align:center;">คลังสินค้า <br>(Stock Name)</th>
                    <th width="96px"></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            for($j=0; $j<count($stock_group); $j++){
            ?>
                <tr class="odd gradeX">
                    <td><?php echo $j+1; ?></td>
                    <td><?php echo $stock_group[$j]['stock_group_code']; ?></td>
                    <td><?php echo '- '.$stock_group[$j]['stock_group_name']; ?></td>
                    <td style="<?php if($j==0){ echo 'border-top: unset;'; }?>text-align:center;">
                        <?php if($menu['stock']['edit']){ ?> 
                        <a href="?app=stock&action=stock_list&code=<?php echo $stock_group[$j]['stock_group_code'];?>">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a> 
                        <a href="?app=stock&action=update&code=<?php echo $stock_group[$j]['stock_group_code']; ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                        <?php } ?>
                        <?php if($menu['stock']['delete']){ ?> 
                        <a href="?app=stock&action=delete&code=<?php echo $stock_group[$j]['stock_group_code'];?>" onclick="return confirm('You want to delete stock : <?php echo $stock_group[$j]['stock_group_name']; ?>');" style="color:red;">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                        <?PHP }?>
                    </td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
</div>
<? } ?>