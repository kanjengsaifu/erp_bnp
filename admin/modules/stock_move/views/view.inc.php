<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var keyword = $("#keyword").val();

        window.location = "index.php?app=stock_move&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Stock Transfer Management</h1>
    </div> 
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                รายการใบย้ายคลังสินค้า / Stock Transfer List
            </div>
            <div class="col-md-4">
                <a class="btn btn-success " style="float:right;" href="?app=stock_move&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>
        </div>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="form-group" style="display: inline-block;">
            <label>วันที่ออกใบโอนคลังสินค้า</label>
            <br>
            <div class="form-group" style="display: inline-block; width: 150px;">
                <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>" class="form-control calendar" readonly>
            </div>
            -
            <div class="form-group" style="display: inline-block; width: 150px;">
                <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>" class="form-control calendar" readonly>
            </div>
        </div>

        <div class="form-group" style="display: inline-block; width: 300px;">
            <label>คำค้น <font color="#F00"><b>*</b></font></label>
            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
        </div>

        <div class="form-group" style="display: inline-block; width: 150px;">
            <button class="btn btn-primary" onclick="search();">Search</button>
            <a href="index.php?app=stock_move" class="btn btn-default">Reset</a>
        </div>

        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="10"> No.</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลยใบย้าย" width="10"> Transfer No.</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ย้าย" width="10"> Transfer Date</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จากคลัง" width="10"> From stock</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ไปยังคลัง" width="10"> To stock</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ย้าย" width="10"> Transfer by</th>
                    <th style="text-align:center;" class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" width="10"> Remark</th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="" width="10"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($stock_moves); $i++){
                ?>
                <tr class="odd gradeX">
                    <td class="text-center"><?php echo $i+1; ?></td>
                    <td><?php echo $stock_moves[$i]['stock_move_code']; ?></td>
                    <td data-order="<?php echo $timestamp = strtotime($stock_moves[$i]['stock_move_date']) ?>"><?php echo ($stock_moves[$i]['stock_move_date']); ?></td>
                    <td><?php echo $stock_moves[$i]['move_group_name_out']; ?></td>
                    <td><?php echo $stock_moves[$i]['move_group_name_in']; ?></td>
                    <td><?php echo $stock_moves[$i]['employee_name']; ?></td>
                    <td><?php echo $stock_moves[$i]['stock_move_remark']; ?></td>
                    <td>
                        <a href="index.php?app=stock_move&action=print&id=<?PHP echo $stock_moves[$i]['stock_move_code'];?>" >
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </a>
                        <a href="?app=stock_move&action=detail&id=<?php echo $stock_moves[$i]['stock_move_code'];?>">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a>

                        <a href="?app=stock_move&action=update&id=<?php echo $stock_moves[$i]['stock_move_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                        <a href="?app=stock_move&action=delete&id=<?php echo $stock_moves[$i]['stock_move_code'];?>" onclick="return confirm('You want to delete Stock Move : <?php echo $stock_moves[$i]['stock_move_code']; ?>');" style="color:red;">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>