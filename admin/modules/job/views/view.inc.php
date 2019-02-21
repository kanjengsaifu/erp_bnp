            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">จัดการบริษัท / Company Management</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                รายชื่อบริษัท / Company List
                                </div>
                                <div class="col-md-4">
                                    <?php if($menu['company']['add']==1){?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=company&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">ลำดับ<br>No.</th>
                                        <th style="text-align:center;">รหัส<br>Code</th>
                                        <th style="text-align:center;">ชื่อไทย<br>Name thai</th>
                                        <th style="text-align:center;">ชื่ออังกฤษ<br>Name english</th> 
                                        <th style="text-align:center;">เลขผู้เสียภาษี<br>TAX ID</th>
                                        <th style="text-align:center;">โทรศัพท์<br>Mobile</th>
                                        <th style="text-align:center;">อีเมล์<br>Email</th>
                                        <th style="text-align:center;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($company); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $company[$i]['company_code']; ?></td>
                                        <td><?php echo $company[$i]['company_name_th']; ?></td>
                                        <td><?php echo $company[$i]['company_name_en']; ?></td> 
                                        <td><?php echo $company[$i]['company_tax']; ?></td>
                                        <td class="center"><?php echo $company[$i]['company_tel']; ?></td>
                                        <td class="center"><?php echo $company[$i]['company_email']; ?></td>
                                        <td>
                                        <?php if($menu['company']['edit']==1){ ?> 
                                            <a href="?app=company&action=update&code=<?php echo $company[$i]['company_code'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                        <?php if($menu['company']['delete']==1){ ?> 
                                            <a href="?app=company&action=delete&code=<?php echo $company[$i]['company_code'];?>" onclick="return confirm('You want to delete company : <?php echo $company[$i]['name']; ?>');" style="color:red;">
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
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
