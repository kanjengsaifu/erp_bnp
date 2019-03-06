     
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Project Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายการโครงการปรับปรุงดิน / Project List
                    </div>
                    <div class="col-md-6">
                    <?php if($menu['project']['add']==1){ ?>  
                        <div class="row"> 
                            <div class="col-md-12" align="right"> 
                                <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=project&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                            </div>
                        </div>   
                    <?PHP } ?>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=project">
                    <input type="hidden" name="app" value="project" />
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier </label>
                                <select id="supplier_code" name="supplier_code" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option <?php if($suppliers[$i]['supplier_code'] == $supplier_code){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_code'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                            </div>
                        </div>
                            
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label>ประเภท / Type </label>
                                <select id="project_type_code" name="project_type_code" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($project_type) ; $i++){
                                    ?>
                                    <option <?php if($project_type[$i]['project_type_code'] == $project_type_code){?> selected <?php }?> value="<?php echo $project_type[$i]['project_type_code'] ?>"><?php echo $project_type[$i]['project_type_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : - .</p>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                <p class="help-block">Example : T001.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                            <a href="index.php?app=project" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="dataTables-example_filter" class="dataTables_filter">
                            
                        </div>
                    </div>
                </div>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($project),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=project&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=project&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=project&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>รหัสโครงการ <br>Project Code</th>
                                    <th>ชื่อโครงการ <br>Project Name</th>
                                    <th>รายละเอียด <br> Description</th> 
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                for($i=$page * $page_size ; $i < count($project) && $i < $page * $page_size + $page_size; $i++){
                                ?>

                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $project[$i]['project_code']; ?></td>
                                    <td><?php echo $project[$i]['project_name']; ?></td>
                                    <td class="center"><?php echo $project[$i]['project_description']; ?></td> 
                                    <td> 
                                    <?php if($menu['project']['edit']==1){ ?> 
                                        <a href="?app=project&action=update&code=<?php echo $project[$i]['project_code'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                    <?PHP } ?>
                                    <?php if($menu['project']['delete']==1){ ?> 
                                        <a href="?app=project&action=delete&code=<?php echo $project[$i]['project_code'];?>" onclick="return confirm('You want to delete project : <?php echo $project[$i]['project_name']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?PHP } ?>
                                    </td>
                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($project),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=project&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=project&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=project&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=project&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
 