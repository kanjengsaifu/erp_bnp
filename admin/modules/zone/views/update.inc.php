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
                        <input id="zone_name" name="zone_name" class="form-control" value="<? echo $zone['zone_name']; ?>"  autocomplete="off">
                        <p class="help-block">Example : โคราช-ในเมือง.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>รายละเอียด :  <font color="#F00"><b>*</b></font></label>
                        <input id="zone_description" name="zone_description" class="form-control" value="<? echo $zone['zone_description']; ?>" autocomplete="off">
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
        <div>
            <div class="form-group" style="display: inline-block; width: 150px;">
                <span>จังหวัด : </span>
                <select id="province_id" name="province_id" data-live-search="true" class="form-control select" onchange="getAmphur()">
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
                <select id="amphur_id" name="amphur_id" data-live-search="true"  class="form-control select" onchange="getDistrict()">
                    <option value="">select</option>
                </select>
            </div>

            <div class="form-group" style="display: inline-block; width: 150px;">
                <span>ตำบล : </span>
                <select id="district_id" name="district_id" data-live-search="true" class="form-control select">
                    <option value="">select</option>
                </select>
            </div>
            
            <div align="right">
                <?php if($menu['zone']['add']){?> 
                    <a class="btn btn-success " style="float:right;" href="?app=zone&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                <?PHP } ?>
            </div>
        </div>
        
        <table width="100%" class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr bgcolor="#92d051">
                    <th style="text-align:center;">#</th>
                    <th style="text-align:center;">จังหวัด</th>
                    <th style="text-align:center;">อำเภอ</th>
                    <th style="text-align:center;">ตำบล</th>
                    <th style="text-align:center;">หมู่บ้าน</th>
                    <th style="text-align:center;">จัดการ</th>
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
                    <td style="text-align:center;"><?php echo $zone_list[$i]['village_name']; ?></td>
                    <td style="text-align:center;">
                    <?php if($menu['zone']['edit']){ ?> 
                        <a href="?app=zone&action=update&code=<?php echo $zone_list[$i]['zone_code'];?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a> 
                    <?PHP }?>
                    <?php if($menu['zone']['delete']){ ?> 
                        <a href="?app=zone&action=delete&code=<?php echo $zone_list[$i]['zone_code'];?>" onclick="return confirm('You want to delete zone : <?php echo $zone_list[$i]['name']; ?>');" style="color:red;">
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
        <div class="panel panel-default">
            <div class="panel-heading">
                ทีมส่งเสริม / Songserm Team
            </div>
            <div class="panel-body">
                <div>
                    <div class="form-group" style="display: inline-block; width: 150px;">
                        <span>ทีมส่งเสริม : </span>
                        <select id="province_id" name="province_id" data-live-search="true" class="form-control select">
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
                        <span>ตำเเหน่ง : </span>
                        <select id="position_code" name="position_code" data-live-search="true"  class="form-control select">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($position) ; $i++){
                            ?>
                            <option value="<?php echo $position[$i]['position_code'] ?>"><?php echo $position[$i]['position_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div align="right">
                        <?php if($menu['zone']['add']){?> 
                            <a class="btn btn-success " style="float:right;" href="?app=zone&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                        <?PHP } ?>
                    </div>
                </div>
                
                <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr bgcolor="#92d051">
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">ชื่อ</th>
                            <th style="text-align:center;">ตำเเหน่ง</th>
                            <th style="text-align:center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($songserm); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $songserm[$i]['songserm_name']; ?></td>
                            <td style="text-align:center;"><?php echo $songserm[$i]['songserm_type_name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['zone']['edit']){ ?> 
                                <a href="?app=zone&action=update&code=<?php echo $songserm[$i]['zone_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                            <?PHP }?>
                            <?php if($menu['zone']['delete']){ ?> 
                                <a href="?app=zone&action=delete&code=<?php echo $songserm[$i]['zone_code'];?>" onclick="return confirm('You want to delete zone : <?php echo $zone_list[$i]['name']; ?>');" style="color:red;">
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
                ผู้รับเหมา / Contractor
            </div>
            <div class="panel-body">
                <div>
                    <div class="form-group" style="display: inline-block; width: 150px;">
                        <span>ผู้รับเหมา : </span>
                        <select id="amphur_id" name="amphur_id" data-live-search="true"  class="form-control select">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($contractor_type) ; $i++){
                            ?>
                            <option value="<?php echo $contractor_type[$i]['contractor_type_code'] ?>"><?php echo $contractor_type[$i]['contractor_type_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="display: inline-block; width: 150px;">
                        <span>ประเภท : </span>
                        <select id="amphur_id" name="amphur_id" data-live-search="true"  class="form-control select">
                            <option value="">select</option>
                            <?php 
                            for($i =  0 ; $i < count($contractor_type) ; $i++){
                            ?>
                            <option value="<?php echo $contractor_type[$i]['contractor_type_code'] ?>"><?php echo $contractor_type[$i]['contractor_type_name'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div align="right">
                        <?php if($menu['zone']['add']){?> 
                            <a class="btn btn-success " style="float:right;" href="?app=zone&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม</a>
                        <?PHP } ?>
                    </div>
                </div>
                
                <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr bgcolor="#92d051">
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">ชื่อ</th>
                            <th style="text-align:center;">ประเภท</th>
                            <th style="text-align:center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($contractor); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td style="text-align:center;"><?php echo $i+1; ?></td>
                            <td style="text-align:center;"><?php echo $contractor[$i]['contractor_name']; ?></td>
                            <td style="text-align:center;"><?php echo $contractor[$i]['contractor_type_name']; ?></td>
                            <td style="text-align:center;">
                            <?php if($menu['zone']['edit']){ ?> 
                                <a href="?app=zone&action=update&code=<?php echo $contractor[$i]['zone_code'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                            <?PHP }?>
                            <?php if($menu['zone']['delete']){ ?> 
                                <a href="?app=zone&action=delete&code=<?php echo $contractor[$i]['zone_code'];?>" onclick="return confirm('You want to delete zone : <?php echo $zone_list[$i]['name']; ?>');" style="color:red;">
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

<script type="text/javascript">
    function getAmphur(){
        var province = document.getElementById("province_id").value;

        $.post("controllers/getAmphur.php", { 'province': province }, function( data ) {
            $("#amphur_id").html(data);
            $("#amphur_id").selectpicker('refresh');
        });

        document.getElementById("amphur_id").value = "";

        getDistrict();
    }

    function getDistrict(){
        var amphur = document.getElementById("amphur_id").value;

        $.post("controllers/getDistrict.php", { 'amphur': amphur }, function( data ) {
            $("#district_id").html(data);
            $("#district_id").selectpicker('refresh');
        });
    }
</script>