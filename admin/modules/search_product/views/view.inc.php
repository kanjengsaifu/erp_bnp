<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
        <?php
        for($i=0; $i<count($stock_group); $i++){
            if($stock_group[$i]['stock_group_code'] == $stock_group_code){ echo $stock_group[$i]['stock_group_name']; }
        }
        ?>
        </h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Search Product.
    </div>
    <div class="panel-body">
        <form action="">
            <input type="hidden" name="app" value="search_product">
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label>คลังสินค้า / Stock </label>
                        <select id="code" name="code" class="form-control select"  data-live-search="true">
                            <option value="">ทั้งหมด</option>
                            <?php 
                            for($i =  0 ; $i < count($stock_group) ; $i++){
                            ?>
                            <option <?php if($stock_group[$i]['stock_group_code'] == $stock_group_code){?> selected <?php }?> value="<?php echo $stock_group[$i]['stock_group_code'] ?>"><?php echo $stock_group[$i]['stock_group_name'] ?> </option>
                            <?
                            }
                            ?>
                        </select>
                        <p class="help-block">Example : - .</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>คำค้น <font color="#F00"><b>*</b></font></label>
                        <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                        <p class="help-block">Example : T001.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary" style="float:right; margin:0px 4px;">Search</button>
                    <a href="index.php?app=search_product" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                </div>
            </div>
        </form>

        <div class="row" style="margin:0px;">
            <div class="col-sm-6">
                <div class="dataTables_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($stock_list),0);?> entries</div>
            </div>
            <div class="col-sm-6">
                <div class="dataTables_paginate paging_simple_numbers" >
                    <ul class="pagination">
                        <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                            <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page; }?>">Previous</a>
                        </li>
                        <?PHP if($page > 0){ ?>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=1">1</a>
                        </li>
                        <li class="paginate_button disabled"   >
                            <a href="#">…</a>
                        </li>
                        <?PHP } ?>
                            
                        <li class="paginate_button active"  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                        </li>

                        <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                        </li>
                        <?PHP } ?>
                    
                        <?PHP if($page < $page_max){ ?>
                        <li class="paginate_button disabled"   >
                            <a href="#">…</a>
                        </li>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                        </li>
                        <?PHP } ?>

                        <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                            <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <table width="100%" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="10">No.</th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสสินค้า" width="100">product code </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อสินค้า" width="200"> Product Name </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รายละเอียดสินค้า" width="100"> Details </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ประเภทสินค้า" width="100"> Category </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="สถานะสินค้า" width="100"> Product status </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="คลังสินค้า" width="200"> warehouse </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="จำนวน" width="100"> quantity </th>
                    <th class="datatable-th text-center"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รายละเอียด" width="100">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=$page * $page_size; $i < count($stock_list) && $i < $page * $page_size + $page_size; $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $stock_list[$i]['product_code']; ?></td>
                    <td><?php echo $stock_list[$i]['product_name']; ?></td>
                    <td><?php echo $stock_list[$i]['product_description']; ?></td>
                    <td><?php echo $stock_list[$i]['product_type_name']; ?></td>
                    <td><?php echo $stock_list[$i]['product_status']; ?></td>
                    <td><?php echo $stock_list[$i]['stock_group_name']; ?></td>
                    <td><?php echo $stock_list[$i]['stock_report_qty']; ?></td>
                    <td>
                        <a target="_blank" href="?app=product_detail&code=<?php echo $stock_list[$i]['product_code'];?>">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>

        <div class="row" style="margin:0px;">
            <div class="col-sm-6">
                <div class="dataTables_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($stock_list),0);?> entries</div>
            </div>
            <div class="col-sm-6">
                <div class="dataTables_paginate paging_simple_numbers" >
                    <ul class="pagination">
                        <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                            <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page; }?>">Previous</a>
                        </li>

                        <?PHP if($page > 0){ ?>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=1">1</a>
                        </li>
                        <li class="paginate_button disabled"   >
                            <a href="#">…</a>
                        </li>
                        <?PHP } ?>

                        <li class="paginate_button active"  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                        </li>

                        <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>=&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                        </li>
                        <?PHP } ?>
                        
                        <?PHP if($page < $page_max){ ?>
                        <li class="paginate_button disabled"   >
                            <a href="#">…</a>
                        </li>
                        <li class="paginate_button "  >
                            <a href="index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                        </li>
                        <?PHP } ?>

                        <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                            <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=search_product&code=<?php echo $stock_group_code;?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>