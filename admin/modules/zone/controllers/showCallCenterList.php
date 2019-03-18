<?php 
require_once('../../../../models/UserModel.php');

$user_model = new UserModel;

$user = $user_model->getCallCenterNotInZone($_POST['zone_code']);

?>
<div class="modal-body">
    <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
        <thead>
            <tr bgcolor="#92d051">
                <th style="text-align:center;"><input id="user_all" data-target="ckb_user[]" onclick="ckbCheckAll(this)" type="checkbox"></th>
                <th style="text-align:center;">#</th>
                <th style="text-align:center;">รหัส</th>
                <th style="text-align:center;">ชื่อ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($user); $i++){
            ?>
            <tr class="odd gradeX">
                <td style="text-align:center;"><input type="checkbox" name="ckb_user[]" data-type="user" value="<?php echo $user[$i]['user_code']; ?>" onclick="ckbChecked(this)"></td>
                <td style="text-align:center;"><?php echo $i+1; ?></td>
                <td style="text-align:center;"><?php echo $user[$i]['user_code']; ?></td>
                <td style="text-align:center;"><?php echo $user[$i]['name']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>

    <div class="alert-panel"></div>

    <div align="right">
        <button type="button" class="right btn btn-default" data-dismiss="modal" aria-hidden="true">ยกเลิก</button>
        <button type="button" class="right btn btn-success" aria-hidden="true" onclick="addCallCenter()">ยืนยัน</button>
    </div>
</div>