<script>
    function search(){ 
        var date_start = $("#date_start").val(); 
        var date_end = $("#date_end").val();  

        window.location = "index.php?app=satisfaction&date_start="+date_start+"&date_end="+date_end  ;
    } 
</script>
           
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Satisfaction Management</h1>
    </div>
    <div class="col-lg-6" align="right"> 
        <?php if($menu['contact_way']['view']==1){ ?> 
        <a href="?app=contact_way" class="btn btn-primary btn-menu ">ช่องทางติดต่อ / Contact way</a>
        <?PHP }?>
        <?php if($menu['contact_type']['view']==1){ ?> 
        <a href="?app=contact_type" class="btn btn-primary btn-menu ">ประเภทการติดต่อ / Contact type</a>
        <?PHP }?>
        <?php if($menu['satisfaction']['view']==1){ ?> 
        <a href="?app=satisfaction" class="btn btn-primary btn-menu active">ความพึงพอใจ / Satisfaction</a> 
        <?PHP }?>
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
                        รายการความพึงพอใจ / Satisfaction List
                    </div>
                    <div class="col-md-6">
                    <?php if($menu['satisfaction']['add']==1){ ?>  
                        <div class="row"> 
                            <div class="col-md-12" align="right"> 
                                <a class="btn btn-success " style="float:right;margin-left:8px;" href="?app=satisfaction&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                            </div>
                        </div>   
                    <?PHP } ?>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">  
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ถึงวันที่</label> 
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-12-2018 - 31-12-2018 </p>
                        </div>
                    </div>   
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4"> 
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search('');">Search</button>
                        <a href="index.php?app=satisfaction" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>
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
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($satisfaction),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=satisfaction&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=satisfaction&page=<?PHP echo $page + 2; }?>" >Next</a>
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
                                    <th>วันที่ <br>Date </th> 
                                    <th>ประเภทผู้ติดต่อ <br>Contact type </th>
                                    <th>ชื่อผู้ติดต่อ <br>Contact name </th> 
                                    <th>ช่องทางติดต่อ <br>Contact way </th> 
                                    <th>ประเภทการติดต่อ <br>Contact type </th> 
                                    <th>คะแนน <br>Score </th> 
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                for($i=$page * $page_size ; $i < count($satisfaction) && $i < $page * $page_size + $page_size; $i++){
                                ?>

                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td> 
                                    <td><?php echo date_format(date_create($satisfaction[$i]['satisfaction_date']),"d-m-Y"); ?></td>  
                                    <td><?php echo $satisfaction[$i]['member_type']; ?></td> 
                                    <td><?php echo $satisfaction[$i]['name']; ?></td> 
                                    <td><?php echo $satisfaction[$i]['contact_way_name']; ?></td> 
                                    <td><?php echo $satisfaction[$i]['contact_type_name']; ?></td> 
                                    <td><?php echo $satisfaction[$i]['satisfaction_score']; ?></td> 
                                    <td> 
                                    <?php if($menu['satisfaction']['edit']==1){ ?> 
                                        <a href="?app=satisfaction&action=update&code=<?php echo $satisfaction[$i]['satisfaction_code'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                    <?PHP } ?>
                                    <?php if($menu['satisfaction']['delete']==1){ ?> 
                                        <a href="?app=satisfaction&action=delete&code=<?php echo $satisfaction[$i]['satisfaction_code'];?>" onclick="return confirm('You want to delete satisfaction : <?php echo $satisfaction[$i]['satisfaction_name']; ?>');" style="color:red;">
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
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($satisfaction),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=satisfaction&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=satisfaction&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=satisfaction&page=<?PHP echo $page + 2; }?>" >Next</a>
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
 