<script>
    function check(){
        var zone_name = document.getElementById("zone_name").value;
        var zone_description = document.getElementById("zone_description").value;

        zone_name = $.trim(zone_name);
        zone_description = $.trim(zone_description);

        if(zone_name.length == 0){
            alert("Please input zone name");
            document.getElementById("zone_name").focus();
            return false;
        }else{ 
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการเขตการขาย / Zone Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        แก้ไขเขตการขาย / Edit zone 
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=zone&action=edit" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>รหัสเขตการขาย : </label>
                        <input id="zone_code" name="zone_code" class="form-control" value="<? echo $zone['zone_code']; ?>" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ชื่อเขตการขาย : <font color="#F00"><b>*</b></font></label>
                        <input id="zone_name" name="zone_name" class="form-control" value="<? echo $zone['zone_name']; ?>"  autocomplete="off" maxlength="150">
                        <p class="help-block">Example : โคราช-ในเมือง.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>รายละเอียด : </label>
                        <input id="zone_description" name="zone_description" class="form-control" value="<? echo $zone['zone_description']; ?>" autocomplete="off" maxlength="200">
                        <p class="help-block">Example : รายละเอียด.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-9 col-lg-3" align="right">
                    <a href="?app=zone" class="btn btn-default">Back</a>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

            <input type="hidden" id="zone_code" name="zone_code" value="<?php echo $zone_code ?>">
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        รายชื่อพื้นที่การขาย / Zone List
    </div>
    <div class="panel-body">
        <form role="form" method="get" onsubmit="return check();" action="index.php" enctype="multipart/form-data">
            <input type="hidden" name="app" value="zone">
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
            <?php if($menu['zone']['add']){?> 
            <a class="btn btn-success " style="float:right;" href="?app=zone&action=insert-list&code=<? echo $_GET['code']; ?>" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
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
                for($i=0; $i < count($zone_list); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['VILLAGE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['agent_name']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_list[$i]['dealer_name']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['zone']['edit']){ ?> 
                        <a href="?app=zone&action=update-list&list=<?php echo $zone_list[$i]['zone_list_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['zone']['delete']){ ?> 
                        <a href="?app=zone&action=delete-list&list=<?php echo $zone_list[$i]['zone_list_code'];?>" onclick="return confirm('คุณต้องการลบพื้นที่การขาย : <?php echo $zone_list[$i]['village_name']; ?>');" style="color:red;">
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
            <input type="hidden" name="app" value="zone">
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
            <?php if($menu['zone']['add']){?> 
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
                for($i=0; $i < count($zone_contractor); $i++){
                ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?php echo $i+1; ?></td>
                    <td style="text-align:center;"><?php echo $zone_contractor[$i]['name']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_contractor[$i]['contractor_mobile']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_contractor[$i]['PROVINCE_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_contractor[$i]['AMPHUR_NAME']; ?></td>
                    <td style="text-align:center;"><?php echo $zone_contractor[$i]['DISTRICT_NAME']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['zone']['delete']){ ?> 
                        <a href="?app=zone&action=delete-contractor&contractor=<?php echo $zone_contractor[$i]['contractor_code'];?>" onclick="return confirm('คุณต้องการลบผู้รับเหมา : <?php echo $zone_contractor[$i]['name']; ?> จากเขตการขายนี้ ?');" style="color:red;">
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
                    <input type="hidden" name="app" value="zone">
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
                    <?php if($menu['zone']['add']){?> 
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
                        for($i=0; $i < count($zone_songserm); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $zone_songserm[$i]['name']; ?></td>
                            <td style="text-align:center;"><?php echo $zone_songserm[$i]['songserm_position_name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['zone']['delete']){ ?> 
                                <a href="?app=zone&action=delete-songserm&songserm=<?php echo $zone_songserm[$i]['zone_songserm_code'];?>" onclick="return confirm('คุณต้องการลบส่งเสริม : <?php echo $zone_songserm[$i]['name']; ?> จากเขตการขายนี้ ?');" style="color:red;">
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
                    <input type="hidden" name="app" value="zone">
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
                    <?php if($menu['zone']['add']){?> 
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
                        for($i=0; $i < count($zone_call_center); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $zone_call_center[$i]['name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['zone']['delete']){ ?> 
                                <a href="?app=zone&action=delete-callcenter&callcenter=<?php echo $zone_call_center[$i]['zone_call_center_code'];?>" onclick="return confirm('คุณต้องการลบ call center : <?php echo $zone_call_center[$i]['name']; ?>');" style="color:red;">
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
        var zone_code = document.getElementById("zone_code").value;

		$.post("modules/zone/controllers/showSongsermList.php", { zone_code: zone_code }, function( data ) {
            $("#modal_detail").html(data);
            tableFilter();
			$('#modal').modal('show');
		});
	}

    function showContractorList(){
        var zone_code = document.getElementById("zone_code").value;

		$.post("modules/zone/controllers/showContractorList.php", { zone_code: zone_code }, function( data ) {
            $("#modal_detail").html(data);
            tableFilter();
			$('#modal').modal('show');
		});
	}

    function showCallCenterList(){
        var zone_code = document.getElementById("zone_code").value;

		$.post("modules/zone/controllers/showCallCenterList.php", { zone_code: zone_code }, function( data ) {
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
        var zone_code = document.getElementById("zone_code").value;
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
            $.post("modules/zone/controllers/addSongserm.php", { zone_code: zone_code, songserm: JSON.stringify(songserm) })
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
        var zone_code = document.getElementById("zone_code").value;
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
            $.post("modules/zone/controllers/addContractor.php", { zone_code: zone_code, contractor: JSON.stringify(contractor) })
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
        var zone_code = document.getElementById("zone_code").value;
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
            $.post("modules/zone/controllers/addCallCenter.php", { zone_code: zone_code, user: JSON.stringify(user) })
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