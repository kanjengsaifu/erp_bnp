<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">จัดการเขตการขาย / Zone Management</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        เพิ่มทีมส่งเสริม / Add songserm
    </div>
    <div class="panel-body">
        <form role="form" method="post" onsubmit="return check();" action="index.php?app=zone&action=add-songserm" enctype="multipart/form-data">
            <div class="row"> 
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>ส่งเสริม : <font color="#F00"><b>*</b></font></label>
                        <input id="songserm_code" name="songserm_code" class="form-control" autocomplete="off">
                        <p class="help-block">Example : AGT0001 / วินัย.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>ชื่อ : </label>
                        <input id="zone_description" name="zone_description" class="form-control" readonly>
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
        </form>

        <div>
            พื้นที่ดูเเล / Zone List
        </div>

        <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
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
<script>
    $(function() {
        $.post("modules/zone/controllers/getSongserm.php", {})
            .done(function( data ) {
                $('.songserm_code').autocomplete({
                    source: data
                }).data("ui-autocomplete")._renderItem = function (ul, item) { 
                    return $( "<li>" )
                        .attr( "data-value", item.value )
                        .append("<div><img src='../upload/logo.png' style='height: 32px;display: inline;'>" + item.label + "</div>")
                        .appendTo( ul );
                };
        });
    });
</script>