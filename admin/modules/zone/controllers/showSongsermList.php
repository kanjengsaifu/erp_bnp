<?php 
require_once('../../../../models/SongsermModel.php');

$songserm_model = new SongsermModel;

$songserm = $songserm_model->getSongsermNotInZone($_POST['zone_code']);

?>
<div class="modal-body">
    <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
        <thead>
            <tr bgcolor="#92d051">
                <th style="text-align:center;">#</th>
                <th style="text-align:center;">รหัส</th>
                <th style="text-align:center;">ชื่อ</th>
                <th style="text-align:center;">ตำเเหน่ง</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($songserm); $i++){
            ?>
            <tr class="odd gradeX">
                <td style="text-align:center;"><?php echo $i+1; ?></td>
                <td style="text-align:center;"><?php echo $songserm[$i]['user_code']; ?></td>
                <td style="text-align:center;"><?php echo $songserm[$i]['name']; ?></td>
                <td style="text-align:center;"><?php echo $songserm[$i]['user_position_name']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>

    <hr>

    <div align="right">
        <button type="button" class="right btn btn-default" data-dismiss="modal" aria-hidden="true">ยกเลิก</button>
        <button type="button" class="right btn btn-success" aria-hidden="true">ยืนยัน</button>
    </div>
</div>