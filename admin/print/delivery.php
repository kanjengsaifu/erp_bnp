<?php 
date_default_timezone_set("Asia/Bangkok");

require_once('../../models/CarModel.php');
require_once('../../models/DeliveryModel.php');
require_once('../../models/DeliveryListModel.php');
require_once('../../models/DeliveryCarModel.php');

$car_model = new CarModel;
$delivery_model = new DeliveryModel;
$delivery_list_model = new DeliveryListModel;
$delivery_car_model = new DeliveryCarModel;

$car = $car_model->getCarBy();
$delivery = $delivery_model->getDeliveryByCode($_GET['code']);
$delivery_list = $delivery_list_model->getDeliveryListByDelivery($_GET['code']);

function filter_by_value($array, $index, $value){
    if(is_array($array) && count($array)>0) 
    {
        foreach(array_keys($array) as $key){
            $temp[$key] = $array[$key][$index];
            
            if ($temp[$key] == $value){
                $newarray[$key] = $array[$key];
            }
        }
    }
    return $newarray;
}

$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"); 

$str='
<style type="text/css">
@page{
	margin: 0.5cm;
}
body { 
	font-size: 10px; 
}
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}
th {
	height: 30px;
}
td {
	height: 20px;
}
</style>
<meta charset="utf-8">

<div class="panel-heading" style=" font-size: 16px;font-weight: bold;padding:0px;">
	<div align="center"> รายการรับ-ส่งผ้า ประจำวันที่ ( '.date("d/m/y", strtotime($delivery['delivery_date'])).' )';

$str.='
	</div>
</div>
<div class="panel-body" style="padding: 0px;">';

for($i=0; $i<count($car); $i++){ 
    $no = 0;
    $results = filter_by_value($delivery_list, 'car_code', $car[$i]['car_code']); 
    $delivery_car = $delivery_car_model->getDeliveryCarByCode(($_GET['code'].'-'.$car[$i]['car_code']));

    if (count($results) || $delivery_car['gasoline'] > 0){

		$str.='
	<div class="panel-body" style="margin-bottom:24px; padding: 0px;">
		<table width="100%" class="table">
			<thead>
				<tr bgcolor="#fff7ec">
					<th width="20%" align="left" style="padding: 8px; border: unset;">ทะเบียนรถ '.$delivery_car['car_license_plate'].'</th>
					<th align="center" style="padding: 8px; border: unset;">พนักงานขับรถ '.$delivery_car['user_name'].'</th>
					<th width="20%" align="right" style="padding: 8px; border: unset;">ค่าน้ำมัน '.$delivery_car['gasoline'].'</th>
				</tr>
			</thead>
		</table>
		<table width="100%" class="table">
			<thead>
				<tr style="color: #fff;" bgcolor="#d2e3fc">
					<td align="center" width="64px">#</td>
					<td align="center" width="40%">ลูกค้า</td>
					<td align="center" width="280px">รายการ</td>
					<td align="center" >หมายเหตุ</td>
				</tr>
			</thead>
			<tbody>';

		for($j=0; $j<count($delivery_list); $j++){ 
			if ($delivery_list[$j]['car_code'] == $car[$i]['car_code']) {
				$no++;

				if ($delivery_list[$j]['send'] == 1 && $delivery_list[$j]['recieve'] == 1){
					$operetion = 'รับ - ส่งผ้า';
				}else if ($delivery_list[$j]['recieve'] == 1){
					$operetion = 'รับผ้า';
				}else if ($delivery_list[$j]['send'] == 1){
					$operetion = 'ส่งผ้า';
				}

				$str.='
				<tr>
					<td align="center">'.$no.'</td>
					<td align="center">'.$delivery_list[$j]['customer_name'].'</td>
					<td align="center">'.$operetion.'</td>
					<td align="center" >'.$delivery_list[$j]['remark'].'</td>
				</tr>';
			}
		}
			
		$str.='
			</tbody>
		</table>
	</div>';
	}
}

if($_GET['type'] == "Excel"){
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=report_order$d1-$d2-$d3 $d4:$d5:$d6.xls");
	echo $str;
}else{
	include("../../plugins/mpdf/mpdf.php");
	$mpdf=new mPDF('th', 'A4-L', '0', 'garuda');   
	$mpdf->mirrorMargins = true;
	$mpdf->SetDisplayMode('fullpage','two');
	$mpdf->WriteHTML($str);
	$mpdf->Output();
}
?>