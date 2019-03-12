<?php 
require_once('../../../../models/ContractorModel.php');

$contractor_model = new ContractorModel;

$contractor = $contractor_model->getContractorNotInZone($_POST['zone_code']);

?>
<div class="modal-body">
    <table width="100%" class="table table-striped table-bordered table-hover dataTables-filter">
        <thead>
            <tr bgcolor="#92d051">
                <th width="20px" style="text-align:center;"><input id="contractor_all" data-target="ckb_contractor[]" onclick="ckbCheckAll(this)" type="checkbox"></th>
                <th style="text-align:center;">#</th>
                <th style="text-align:center;">รหัส</th>
                <th style="text-align:center;">ชื่อ</th>
                <th style="text-align:center;">จังหวัด</th>
                <th style="text-align:center;">อำเภอ</th>
                <th style="text-align:center;">ตำบล</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i < count($contractor); $i++){
            ?>
            <tr class="odd gradeX">
                <td style="text-align:center;"><input type="checkbox" name="ckb_contractor[]" data-type="contractor" value="<?php echo $contractor[$i]['contractor_code']; ?>" onclick="ckbChecked(this)"></td>
                <td style="text-align:center;"><?php echo $i+1; ?></td>
                <td style="text-align:center;"><?php echo $contractor[$i]['contractor_code']; ?></td>
                <td style="text-align:center;"><?php echo $contractor[$i]['name']; ?></td>
                <td style="text-align:center;"><?php echo $contractor[$i]['PROVINCE_NAME']; ?></td>
                <td style="text-align:center;"><?php echo $contractor[$i]['AMPHUR_NAME']; ?></td>
                <td style="text-align:center;"><?php echo $contractor[$i]['DISTRICT_NAME']; ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>

    <div class="alert-panel"></div>

    <div align="right">
        <button type="button" class="right btn btn-default" data-dismiss="modal" aria-hidden="true">ยกเลิก</button>
        <button type="button" class="right btn btn-success" aria-hidden="true" onclick="addContractor()">ยืนยัน</button>
    </div>
</div>