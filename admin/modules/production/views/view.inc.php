<style>
.modal-content {
	margin: 5% auto;
	padding: 20px;
	border-radius: 0px;
}
</style>

<div class="row">
	<div class="col-xs-12">
		<h2>Production Board</h2>
		<hr>
		<a class="btn <?php if(!isset($_GET['status'])) echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production">
			ทั้งหมด
		</a>
		<a class="btn <?php if($_GET['status'] == "wash") echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production&status=wash">
			ซัก
		</a>
		<a class="btn <?php if($_GET['status'] == "drying") echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production&status=drying">
			อบ
		</a>
		<a class="btn <?php if($_GET['status'] == "iron") echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production&status=iron">
			รีด
		</a>
		<a class="btn <?php if($_GET['status'] == "fold") echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production&status=fold">
			พับ
		</a>
		<a class="btn <?php if($_GET['status'] == "check") echo "btn-primary"; else echo "btn-default"; ?>" href="?app=production&status=check">
			ตรวจสอบ
		</a>
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-4">
		<div class="text-center">
			<h3>รอผลิต</h3>
		</div>
		<div id="wait-drag-area">
		<?php for($i=0; $i<count($wait); $i++){ ?>
			<table class="table" data-code="<?php echo $wait[$i]['order_procress_code']; ?>" style="background-color: #ebebeb;">
				<thead>
					<tr style="color: #fff;" bgcolor="#4d98dc">
						<td><div class="draggable"><?php echo $wait[$i]['order_code']; ?> (<?php echo $wait[$i]['procress_name_th']; ?>)</div></td>
						<td class="text-right" colspan="2"><div class="draggable">กำหนดส่ง <?php if ($wait[$i]['due_date'] != '') echo date("d/m/y", strtotime($wait[$i]['due_date'])); else echo "-" ?></div></td>
					</tr>
				</thead>
				<tbody onclick="showProduction('<?php echo $wait[$i]['order_procress_code']; ?>','<?php echo $wait[$i]['procress_code']; ?>')">
					<tr>
						<td style="border-top: 0px solid #ddd;"><?php echo $wait[$i]['order_procress_code']; ?></td>
						<td class="text-right" colspan="2" style="border-top: 0px solid #ddd;"><?php echo $wait[$i]['finish_date']; ?></td>
					</tr>
					<tr>
						<td style="border-top: 0px solid #ddd;"><?php echo $wait[$i]['cloth_type_name']; ?></td>
						<td style="border-top: 0px solid #ddd;"><?php echo $wait[$i]['customer_name']; ?></td>
						<td class="text-right" style="border-top: 0px solid #ddd;"><?php echo $wait[$i]['qty']; ?></td>
					</tr>
				</tbody>
			</table>
		<?php } ?>
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<h3>กำลังผลิต</h3>
		</div>
	<?php for($i=0; $i<count($production); $i++){ ?>
		<table class="table" data-code="<?php echo $production[$i]['order_procress_code']; ?>" style="background-color: #ebebeb;">
			<thead>
				<tr style="color: #fff;" bgcolor="#4d98dc">
					<td><div class="draggable"><?php echo $production[$i]['order_code']; ?> (<?php echo $production[$i]['procress_name_th']; ?>)</div></td>
					<td class="text-right" colspan="2"><div class="draggable">กำหนดส่ง <?php if ($production[$i]['due_date'] != '') echo date("d/m/y", strtotime($production[$i]['due_date'])); else echo "-" ?></div></td>
				</tr>
			</thead>
			<tbody onclick="showProduction('<?php echo $production[$i]['order_procress_code']; ?>','<?php echo $production[$i]['procress_code']; ?>')">
				<tr>
					<td style="border-top: 0px solid #ddd;"><?php echo $production[$i]['order_procress_code']; ?></td>
					<td class="text-right" colspan="2" style="border-top: 0px solid #ddd;"><?php echo $production[$i]['finish_date']; ?></td>
				</tr>
				<tr>
					<td style="border-top: 0px solid #ddd;"><?php echo $production[$i]['cloth_type_name']; ?></td>
					<td style="border-top: 0px solid #ddd;"><?php echo $production[$i]['customer_name']; ?></td>
					<td class="text-right" style="border-top: 0px solid #ddd;"><?php echo $production[$i]['qty']; ?></td>
				</tr>
			</tbody>
		</table>
	<?php } ?>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<h3>เสร็จ</h3>
		</div>
	<?php for($i=0; $i<count($finished); $i++){ ?>
		<table class="table" data-code="<?php echo $finished[$i]['order_procress_code']; ?>" style="background-color: #ebebeb;">
			<thead>
				<tr style="color: #fff;" bgcolor="#4d98dc">
					<td><div class="draggable"><?php echo $finished[$i]['order_code']; ?> (<?php echo $finished[$i]['procress_name_th']; ?>)</div></td>
					<td class="text-right" colspan="2"><div class="draggable">กำหนดส่ง <?php if ($finished[$i]['due_date'] != '') echo date("d/m/y", strtotime($finished[$i]['due_date'])); else echo "-" ?></div></td>
				</tr>
			</thead>
			<tbody onclick="showProduction('<?php echo $finished[$i]['order_procress_code']; ?>','<?php echo $finished[$i]['procress_code']; ?>')">
				<tr>
					<td style="border-top: 0px solid #ddd;"><?php echo $finished[$i]['order_procress_code']; ?></td>
					<td class="text-right" colspan="2" style="border-top: 0px solid #ddd;"><?php echo $finished[$i]['finish_date']; ?></td>
				</tr>
				<tr>
					<td style="border-top: 0px solid #ddd;"><?php echo $finished[$i]['cloth_type_name']; ?></td>
					<td style="border-top: 0px solid #ddd;"><?php echo $finished[$i]['customer_name']; ?></td>
					<td class="text-right" style="border-top: 0px solid #ddd;"><?php echo $finished[$i]['qty']; ?></td>
				</tr>
			</tbody>
		</table>
	<?php } ?>
	</div>
</div>

<div id="modal_production_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div id="production_detail" class="modal-content"></div>
	</div>
</div>

<link href="../template/draggable/dragula.css" rel="stylesheet">
<script src="../template/draggable/dragula.js"></script>

<script>
	$(document).ready(function(){
		// dragula([document.getElementById("card-drag-area")]),
		// dragula([document.getElementById("card-move")]).on("drag",function(e){e.className=e.className.replace("card-moved","")}).on("drop",function(e){e.className+=" card-moved"}).on("over",function(e,t){t.className+=" card-over"}).on("out",function(e,t){t.className=t.className.replace("card-over","")}),
		// dragula([document.getElementById("copy-left"),document.getElementById("copy-right")],{copy:!0}),
		// dragula([document.getElementById("left-handles"),document.getElementById("right-handles")],{
		// 	moves:function(e,t,n){return n.classList.contains("handle")}
		// }),

		// dragula([document.getElementById("wait-drag-area")],{
		// 	moves:function(e,t,n){
		// 		return n.classList.contains("draggable")
		// 	}
		// }).on('drop', function (e,n) {
		// 	console.log('move :',e.dataset.code);
		// 	for(var i=0; i<n.children.length; i++){
		// 		console.log(n.children[i].dataset.code);
		// 	}
		// });
	});

	$('#modal_production_detail').on('hidden.bs.modal', function (e) {
		window.location.reload();
	})

	function getMachineData(){
		var machine_code = $.trim($('#machine_code').val());

		if(machine_code != ''){
			$.post("modules/production/controls/getMachineData.php", { machine_code: machine_code })
				.done(function(data) {
					if(data != null){
						$.post("modules/production/controls/getProgramList.php", { machine_model_code: data.machine_model_code }, function( data ) {
							$('.remove-able').remove();
							$("#tb_production").append(data);
						});
					}else{
						$('.remove-able').remove();
					}
			});
		}else{
			$('.remove-able').remove();
		}
	}

	function showProduction(code,procress){
		loadingScreen();

		$.post("modules/production/controls/showProduction.php", { order_procress_code: code, procress_code: procress }, function( data ) {
			$("#production_detail").html(data);
			$('#modal_production_detail').modal('show');
			loadedScreen();
		});
	}

	function procressBegin(){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());

        if(order_procress_code == '' || procress_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$.post("modules/production/controls/procressBegin.php", { order_procress_code: order_procress_code, procress_code: procress_code })
				.done(function( data ) {
					if(data){
						showProduction(order_procress_code,procress_code);
					}else{
						msgAlert('Sorry, Someting worng cannot be processed.');
					}
			});
		}
	}

	function addProduction(){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());
        var machine_code = $.trim($('#machine_code').val());
        var program_code = $.trim($('#program_code').val());
        var qty = $.trim($('#qty').val());
        
        if(order_procress_code == '' || procress_code == ''){
			var msg =
			'<div class="alert alert-warning alert-dismissible">'+
				'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
				'Sorry,Someting worng this action cannot be made.'+
			'</div>';

			$('.alert-addproduction-panel').html(msg);
        }else if(machine_code == ''){
			var msg =
			'<div class="alert alert-warning alert-dismissible">'+
				'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
				'Please specify the machine.'+
			'</div>';

			$('.alert-addproduction-panel').html(msg);
			$("#machine_code").focus();
        }else if(program_code == ''){
			var msg =
			'<div class="alert alert-warning alert-dismissible">'+
				'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
				'Please specify the program used.'+
			'</div>';

			$('.alert-addproduction-panel').html(msg);
			$("#program_code").focus();
        }else if(qty == ''){
			var msg =
			'<div class="alert alert-warning alert-dismissible">'+
				'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
				'Please specify qty.'+
			'</div>';

			$('.alert-addproduction-panel').html(msg);
			$("#qty").focus();
        }else{
			$.post("modules/production/controls/addProduction.php", { 
				order_procress_code: order_procress_code ,
				procress_code: procress_code, 
				machine_code: machine_code, 
				program_code: program_code, 
				qty: qty,
			}).done(function( data ) {
				if(data){
					showProduction(order_procress_code,procress_code);
				}else{
					var msg =
					'<div class="alert alert-danger alert-dismissible">'+
						'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
						'<strong>Sorry!</strong> Someting worng cannot be processed.'+
					'</div>';

					$('.alert-addproduction-panel').html(msg);
				}
			});
		}
	}

	function updateRemark(){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());
		var order_list_code = document.getElementsByName('order_list_code[]');
        var remark = document.getElementsByName('remark[]');

		var order_list = [];
        for(var i=0; i<order_list_code.length; i++){
			order_list.push({
				order_list_code: order_list_code[i].value,
				remark: remark[i].value
			});
		}
        
		$.post("modules/production/controls/updateRemark.php", { order_list:JSON.stringify(order_list) })
		 	.done(function( data ) {
				if(data){
					var msg =
					'<div class="alert alert-success alert-dismissible">'+
						'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
						'<strong>Success!</strong> Saved.'+
					'</div>';

					$('.alert-panel').html(msg);
				}else{
					var msg =
					'<div class="alert alert-danger alert-dismissible">'+
						'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
						'<strong>Error!</strong> Saved failed.'+
					'</div>';

					$('.alert-panel').html(msg);
				}
		});
	}

	function msgProductionCancel(production_code){
        if(production_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$.post("modules/production/controls/msgProductionCancel.php", { production_code: production_code })
				.done(function( data ) {
					$("#msg_detail").html(data);
					$('#modal_msg').modal('show');
			});
		}
	}

	function productionCancel(production_code){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());

        if(production_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$('#modal_msg').modal('hide');

			$.post("modules/production/controls/productionCancel.php", { production_code: production_code })
				.done(function( data ) {
					if(data){
						showProduction(order_procress_code,procress_code);
					}else{
						msgAlert('Sorry, Someting worng cannot be processed.');
					}
			});
		}
	}

	function msgProductionFinish(production_code){
        if(production_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$.post("modules/production/controls/msgProductionFinish.php", { production_code: production_code })
				.done(function( data ) {
					$("#msg_detail").html(data);
					$('#modal_msg').modal('show');
			});
		}
	}

	function productionFinish(production_code){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());

        if(production_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$('#modal_msg').modal('hide');

			$.post("modules/production/controls/productionFinish.php", { production_code: production_code })
				.done(function( data ) {
					if(data){
						showProduction(order_procress_code,procress_code);
					}else{
						msgAlert('Sorry, Someting worng cannot be processed.');
					}
			});
		}
	}

	function msgProcressFinish(){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());

        if(order_procress_code == '' || procress_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$.post("modules/production/controls/msgProcressFinish.php", { order_procress_code: order_procress_code, procress_code: procress_code })
				.done(function( data ) {
					$("#msg_detail").html(data);
					$('#modal_msg').modal('show');
			});
		}
	}

	function procressFinish(){
		var order_procress_code = $.trim($('#order_procress_code').val());
		var procress_code = $.trim($('#procress_code').val());

        if(order_procress_code == '' || procress_code == ''){
            msgAlert('Sorry,Someting worng this action cannot be made.');
        }else{
			$('#modal_msg').modal('hide');

			$.post("modules/production/controls/procressFinish.php", { order_procress_code: order_procress_code, procress_code: procress_code })
				.done(function( data ) {
					if(data){
						showProduction(order_procress_code,procress_code);
					}else{
						msgAlert('Sorry, Someting worng cannot be processed.');
					}
			});
		}
	}
</script>