<div class="row">
    <div class="col-lg-12">
        <h1><?PHP echo $stock_group['stock_group_name']; ?></h1>
        <h4 class="page-sub-header">ข้อมูลคลังสินค้า</h4>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-8">
                ข้อมูลคลังสินค้า / Stock information 
            </div>
            <div class="col-md-4">
                <a class="btn btn-default" style="float:right;" href="?app=stock">Back</a>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="180px">รหัสคลังสินค้า</td>
                <td><? echo $stock_group['stock_group_code']; ?></td>
            </tr>
            <tr>
                <td>ประเภท</td>
                <td><?php echo $stock_group['stock_type_name']?></td>
            </tr>
            <tr>
                <td>แจ้งเตือนทุกวันที่ </td>
                <td><?php echo $stock_group['stock_group_day']?></td>
            </tr>
            <tr>
                <td>ผู้ดูเเล</td>
                <td><?php echo $admin['name']?></td>
            </tr>
        </tbody>
    </table>
</div>

<table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
    <thead>
        <tr bgcolor="#92d051">
            <th width="48" style="text-align:center;">ลำดับ <br>No.</th>
            <th style="text-align:center;">รหัสสินค้า <br>Product Code</th>
            <th style="text-align:center;">สินค้า <br>Product Name</th>
            <th style="text-align:center;">ประเภท <br>Product Type</th>
            <th style="text-align:center;">จำนวน <br>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        for($i=0; $i<count($stock_list); $i++){
        ?>
        <tr class="odd gradeX">
            <td><?php echo $i+1; ?></td>
            <td><?php echo $stock_list[$i]['product_code']; ?></td>
            <td><?php echo $stock_list[$i]['product_name']; ?></td>
            <td><?php echo $stock_list[$i]['product_type_name']; ?></td>
            <td style="text-align:center;"><?php echo $stock_list[$i]['stock_qty']; ?></td>
        </tr>
        <?
        }
        ?>
    </tbody>
</table>