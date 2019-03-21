<div class="row">
    <div class="col-lg-12">
        <h1><?PHP echo $stock_group['stock_group_name']; ?></h1>
        <h4 class="page-sub-header">ข้อมูลคลังสินค้า</h4>
    </div>
</div>

<table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
    <thead>
        <tr>
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
            <td><?php echo $stock_list[$i]['product_type']; ?></td>
            <td><?php echo $stock_list[$i]['amount']; ?></td>
        </tr>
        <?
        }
        ?>
    </tbody>
</table>