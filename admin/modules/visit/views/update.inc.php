<script>
    function check(){
        var visit_name = document.getElementById("visit_name").value;
        var visit_description = document.getElementById("visit_description").value;

        visit_name = $.trim(visit_name);
        visit_description = $.trim(visit_description);

        if(visit_name.length == 0){
            alert("Please input visit name");
            document.getElementById("visit_name").focus();
            return false;
        }else{ 
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการแบบฟอร์มเยี่ยมชม / Visit Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขแบบฟอร์มเยี่ยมชม / Edit visit 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=visit&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสแบบฟอร์มเยี่ยมชม : </label>
                        <input id="visit_code" name="visit_code" class="form-control" value="<? echo $visit['visit_code']; ?>" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อแบบฟอร์มเยี่ยมชม : <font color="#F00"><b>*</b></font></label>
                        <input id="visit_name" name="visit_name" class="form-control" value="<? echo $visit['visit_name']; ?>"  autocomplete="off" maxlength="150">
                        <p class="help-block">Example : โคราช-ในเมือง.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>รายละเอียด : </label>
                        <input id="visit_description" name="visit_description" class="form-control" value="<? echo $visit['visit_description']; ?>" autocomplete="off" maxlength="200">
                        <p class="help-block">Example : รายละเอียด.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=visit" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" id="visit_code" name="visit_code" value="<?php echo $visit_code ?>">
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        รายชื่อพื้นที่การขาย / Visit List
    </div>
    <div class="panel-body">
        <form role="form" method="get" onsubmit="return check();" action="index.php" enctype="multipart/form-data">
            <input type="hidden" name="app" value="visit">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="code" value="<?php echo $_GET['code']; ?>">
            <div class="form-group" style="display: inline-block; width: 150px;">
                <span>จังหวัด : </span>
                <select id="province" name="province" data-live-search="true" class="form-control select" onchange="getAmphur()">
                    <option value="">select</option>
                    <?php 
                    for($i =  0 ; $i < count($province) ; $i++){
                    ?>
                    <option value="<?php echo $province[$i]['PROVINCE_ID'] ?>"><?php echo $province[$i]['PROVINCE_NAME'] ?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" style="display: inline-block; width: 150px;">
                <span>อำเภอ : </span>
                <select id="amphur" name="amphur" data-live-search="true"  class="form-control select" onchange="getDistrict()">
                    <option value="">select</option>
                </select>
            </div>

            <div class="form-group" style="display: inline-block; width: 150px;">
                <span>ตำบล : </span>
                <select id="district" name="district" data-live-search="true" class="form-control select">
                    <option value="">select</option>
                </select>
            </div>

            <div class="form-group" style="display: inline-block; width: 150px; vertical-align: bottom;">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>
        
        <div align="right">
            <?php if($menu['visit']['add']){?> 
            <a class="btn btn-success " style="float:right;" href="?app=visit&action=insert-list&code=<? echo $_GET['code']; ?>" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
            <?PHP } ?>
        </div>
        
        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">จังหวัด<br>Province</th>
                    <th style="text-align:center;">อำเภอ<br>Amphur</th>
                    <th style="text-align:center;">ตำบล<br>Distict</th>
                    <th style="text-align:center;">หมู่บ้าน<br>Village</th>
                    <th style="text-align:center;">นายหน้า<br>agent</th>
                    <th style="text-align:center;">ตัวเเทนจำหน่าย<br>fund agent</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($visit_list); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['VILLAGE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['agent_name']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_list[$i]['dealer_name']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['visit']['edit']){ ?> 
                        <a href="?app=visit&action=update-list&list=<?php echo $visit_list[$i]['visit_list_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['visit']['delete']){ ?> 
                        <a href="?app=visit&action=delete-list&list=<?php echo $visit_list[$i]['visit_list_code'];?>" onclick="return confirm('คุณต้องการลบพื้นที่การขาย : <?php echo $visit_list[$i]['village_name']; ?>');" style="color:red;">
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

<div class="panel panel-default">
    <div class="panel-heading">
        ผู้รับเหมา / Contractor
    </div>
    <div class="panel-body">
        <form role="form" method="get" onsubmit="return check();" action="index.php" enctype="multipart/form-data">
            <input type="hidden" name="app" value="visit">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="code" value="<?php echo $_GET['code']; ?>">
            <div class="form-group" style="display: inline-block; width: 150px; vertical-align: middle;">
                <span>ค้นหา : </span>
                <input id="contractor" name="contractor" type="search" class="form-control" placeholder="search">
            </div>

            <div class="form-group" style="display: inline-block; width: 150px; vertical-align: bottom;">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>
        
        <div align="right">
            <?php if($menu['visit']['add']){?> 
            <a class="btn btn-success" style="float:right;" onclick="showContractorList();"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
            <?PHP } ?>
        </div>
        
        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">ลำดับ <br>No.</th>
                    <th style="text-align:center;">ชื่อ <br>Name</th>
                    <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                    <th style="text-align:center;">จังหวัด <br>Province</th>
                    <th style="text-align:center;">อำเภอ <br>Amphur</th>
                    <th style="text-align:center;">ตำบล <br>Distict</th>
                    <th style="text-align:center;"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($i=0; $i < count($visit_contractor); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $visit_contractor[$i]['name']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_contractor[$i]['contractor_mobile']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_contractor[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_contractor[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $visit_contractor[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['visit']['delete']){ ?> 
                        <a href="?app=visit&action=delete-contractor&contractor=<?php echo $visit_contractor[$i]['contractor_code'];?>" onclick="return confirm('คุณต้องการลบผู้รับเหมา : <?php echo $visit_contractor[$i]['name']; ?> จากแบบฟอร์มเยี่ยมชมนี้ ?');" style="color:red;">
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

<div class="row">
    <div class="col-md-6">
        <div id="songserm-panel" class="panel panel-default">
            <div class="panel-heading">
                ทีมส่งเสริม / Songserm Team
            </div>
            <div class="panel-body">
                <form role="form" method="get" onsubmit="return check();" action="index.php" enctype="multipart/form-data">
                    <input type="hidden" name="app" value="visit">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="code" value="<?php echo $_GET['code']; ?>">
                    <div class="form-group" style="display: inline-block; width: 150px; vertical-align: middle;">
                        <span>ค้นหา : </span>
                        <input id="songserm" name="songserm" type="search" class="form-control" placeholder="search">
                    </div>
                    <div class="form-group" style="display: inline-block; width: 150px; vertical-align: middle;">
                        <span>ตำเเหน่ง : </span>
                        <select id="position" name="position" data-live-search="true"  class="form-control select">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($songserm_position) ; $i++){
                            ?>
                            <option value="<?php echo $songserm_position[$i]['songserm_position_code'] ?>"><?php echo $songserm_position[$i]['songserm_position_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" style="display: inline-block; width: 150px; vertical-align: bottom;">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>

                <div align="right">
                    <?php if($menu['visit']['add']){?> 
                    <a class="btn btn-success" style="float:right;" onclick="showSongsermList();"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                    <?PHP } ?>
                </div>

                <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr bgcolor="#92d051">
                            <th style="text-align:center;">ลำดับ <br>No.</th>
                            <th style="text-align:center;">ชื่อ<br>Name</th>
                            <th style="text-align:center;">ตำเเหน่ง<br>Position</th>
                            <th style="text-align:center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($visit_songserm); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $visit_songserm[$i]['name']; ?></td>
                            <td style="text-align:center;"><?php echo $visit_songserm[$i]['songserm_position_name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['visit']['delete']){ ?> 
                                <a href="?app=visit&action=delete-songserm&songserm=<?php echo $visit_songserm[$i]['visit_songserm_code'];?>" onclick="return confirm('คุณต้องการลบส่งเสริม : <?php echo $visit_songserm[$i]['name']; ?> จากแบบฟอร์มเยี่ยมชมนี้ ?');" style="color:red;">
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
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                ฝ่ายบริการ / Call center
            </div>
            <div class="panel-body">
                <form role="form" method="get" onsubmit="return check();" action="index.php" enctype="multipart/form-data">
                    <input type="hidden" name="app" value="visit">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="code" value="<?php echo $_GET['code']; ?>">
                    <div class="form-group" style="display: inline-block; width: 150px; vertical-align: middle;">
                        <span>ค้นหา : </span>
                        <input id="call_center" name="call_center" type="search" class="form-control" placeholder="search">
                    </div>

                    <div class="form-group" style="display: inline-block; width: 150px; vertical-align: bottom;">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>
                
                <div align="right">
                    <?php if($menu['visit']['add']){?> 
                    <a class="btn btn-success" style="float:right;" onclick="showCallCenterList()"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                    <?PHP } ?>
                </div>
                
                <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr bgcolor="#92d051">
                            <th style="text-align:center;">ลำดับ <br>No.</th>
                            <th style="text-align:center;">ชื่อ<br>Name</th>
                            <th style="text-align:center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($visit_call_center); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $visit_call_center[$i]['name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['visit']['delete']){ ?> 
                                <a href="?app=visit&action=delete-callcenter&callcenter=<?php echo $visit_call_center[$i]['visit_call_center_code'];?>" onclick="return confirm('คุณต้องการลบ call center : <?php echo $visit_call_center[$i]['name']; ?>');" style="color:red;">
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
    </div>
</div>

<div id="modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div id="modal_detail" class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
    function getAmphur(){
        var province = document.getElementById("province").value;

        $.post("controllers/getAmphur.php", { 'province': province }, function( data ) {
            $("#amphur").html(data);
            $("#amphur").selectpicker('refresh');
        });

        document.getElementById("amphur").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur").value;

        $.post("controllers/getDistrict.php", { 'amphur': amphur }, function( data ) {
            $("#district").html(data);
            $("#district").selectpicker('refresh');
        });
    }

    function tableFilter(){
        $('.dataTables-filter').DataTable({
            "columnDefs": [{ "orderable": false, "targets": 0 }],
            "order": [[ 1, "asc" ]],
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true,
        });
    }

    function showSongsermList(){
        var visit_code = document.getElementById("visit_code").value;

		$.post("modules/visit/controllers/showSongsermList.php", { visit_code: visit_code }, function( data ) {
            $("#modal_detail").html(data);
            tableFilter();
			$('#modal').modal('show');
		});
	}

    function showContractorList(){
        var visit_code = document.getElementById("visit_code").value;

		$.post("modules/visit/controllers/showContractorList.php", { visit_code: visit_code }, function( data ) {
            $("#modal_detail").html(data);
            tableFilter();
			$('#modal').modal('show');
		});
	}

    function showCallCenterList(){
        var visit_code = document.getElementById("visit_code").value;

		$.post("modules/visit/controllers/showCallCenterList.php", { visit_code: visit_code }, function( data ) {
            $("#modal_detail").html(data);
            tableFilter();
			$('#modal').modal('show');
		});
	}

    function ckbCheckAll(e){
        if (e.checked){
            $('input[name="'+ e.dataset.target +'"]').prop('checked', true);
        }else{
            $('input[name="'+ e.dataset.target +'"]').prop('checked', false);
        }
    }

	function ckbChecked(e){
        if (!e.checked){
            $('#'+ e.dataset.type +'_all')[0].checked = false;
        }
	}

    function addSongserm(){
        var visit_code = document.getElementById("visit_code").value;
        var checkbox = document.getElementsByName('ckb_songserm[]');
        var assis_director = document.getElementsByName('assis_director');
        var manager = document.getElementsByName('manager');

        var songserm = [];
        for(var i=0; i<checkbox.length; i++){
            if(checkbox[i].checked){
                songserm.push({
                    songserm_code: checkbox[i].value,
                });
            }
        }

        for(var i=0; i<manager.length; i++){
            if(manager[i].checked){
                songserm.push({
                    songserm_code: manager[i].value,
                });
            }
        }

        for(var i=0; i<assis_director.length; i++){
            if(assis_director[i].checked){
                songserm.push({
                    songserm_code: assis_director[i].value,
                });
            }
        }


        if (songserm.length){
            $.post("modules/visit/controllers/addSongserm.php", { visit_code: visit_code, songserm: JSON.stringify(songserm) })
                .done(function(data) {
                    if(data){
                        window.location.reload();
                    }else{
                        var msg =
                        '<div class="alert alert-danger alert-dismissible">'+
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                            '<strong>ผิดพลาด!</strong> เพิ่มรายการล้มเหลว.'+
                        '</div>';

						$('.alert-panel').html(msg);
                    }
            });
        }else{
            var msg =
            '<div class="alert alert-warning alert-dismissible">'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                '<strong>แจ้งเตือน!</strong> โปรดเลือกอย่างน้อยหนึ่งรายการ.'+
            '</div>';
            
            $('.alert-panel').html(msg);
        }
	}

    function addContractor(){
        var visit_code = document.getElementById("visit_code").value;
        var checkbox = document.getElementsByName('ckb_contractor[]');
        
        var contractor = [];
        for(var i=0; i<checkbox.length; i++){
            if(checkbox[i].checked){
                contractor.push({
                    contractor_code: checkbox[i].value,
                });
            }
        }

        if (contractor.length){
            $.post("modules/visit/controllers/addContractor.php", { visit_code: visit_code, contractor: JSON.stringify(contractor) })
                .done(function(data) {
                    if(data){
                        window.location.reload();
                    }else{
                        var msg =
                        '<div class="alert alert-danger alert-dismissible">'+
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                            '<strong>ผิดพลาด!</strong> เพิ่มรายการล้มเหลว.'+
                        '</div>';

						$('.alert-panel').html(msg);
                    }
            });
        }else{
            var msg =
            '<div class="alert alert-warning alert-dismissible">'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                '<strong>แจ้งเตือน!</strong> โปรดเลือกอย่างน้อยหนึ่งรายการ.'+
            '</div>';
            
            $('.alert-panel').html(msg);
        }
	}

    function addCallCenter(){
        var visit_code = document.getElementById("visit_code").value;
        var checkbox = document.getElementsByName('ckb_user[]');
        
        var user = [];
        for(var i=0; i<checkbox.length; i++){
            if(checkbox[i].checked){
                user.push({
                    user_code: checkbox[i].value,
                });
            }
        }

        if (user.length){
            $.post("modules/visit/controllers/addCallCenter.php", { visit_code: visit_code, user: JSON.stringify(user) })
                .done(function(data) {
                    if(data){
                        window.location.reload();
                    }else{
                        var msg =
                        '<div class="alert alert-danger alert-dismissible">'+
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                            '<strong>ผิดพลาด!</strong> เพิ่มรายการล้มเหลว.'+
                        '</div>';

						$('.alert-panel').html(msg);
                    }
            });
        }else{
            var msg =
            '<div class="alert alert-warning alert-dismissible">'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                '<strong>แจ้งเตือน!</strong> โปรดเลือกอย่างน้อยหนึ่งรายการ.'+
            '</div>';
            
            $('.alert-panel').html(msg);
        }
	}
</script>