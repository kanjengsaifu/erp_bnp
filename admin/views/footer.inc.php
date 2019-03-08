<div id="modal_msg" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div id="msg_detail" class="modal-content"></div>
	</div>
</div>

<!-- Bootstrap Core JavaScript -->
<script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Menu Plugin JavaScript -->
<script src="../template/vendor/metisMenu/menu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="../template/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../template/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../template/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js" ></script>

<!-- Custom Theme JavaScript -->
<script src="../template/dist/js/admin.js"></script>

<!-- Custom Dropdown Theme JavaScript -->
<script src="../template/dist/js/bootstrap-select.min.js"></script>

<!-- Morris Charts JavaScript -->
<?php if($_GET['app'] =="" ){?>
<script src="../template/dist/js/Chart.min.js"></script>
<script src="../template/dist/js/Chart.bundle.min.js"></script>
<?php }?>

<script type="text/javascript">
    $(function() {
        var integer = $('input[class*="integer"]');
        var float = $('input[class*="float"]');

        for(var i = 0; i < integer.length; i++){
            setInputFilter(integer[i], function(value) {
                return /^\d*$/.test(value);
            });
        }

        for(var i = 0; i < float.length; i++){
            setInputFilter(float[i], function(value) {
                return /^-?\d*[.,]?\d*$/.test(value);
            });
        }
    });
    
    $(document).ready(function() {
        $('.dataTables').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            bFilter: false,
            responsive: true 
        });

        $('.dataTables-filter').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true,
        });

        $('.select').selectpicker();
        
        $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });

        loadedScreen();
    });

    $('.modal').on('hidden.bs.modal', function (e) {
        if($('.modal').hasClass('in')) {
            $('body').addClass('modal-open');
        }
    });

    function setInputFilter(textbox, inputFilter) {["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
        textbox.addEventListener(event, function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                }
            });
        });

        // Integer values (both positive and negative):
        // /^-?\d*$/.test(value)
        // Integer values (positive only):
        // /^\d*$/.test(value)
        // Integer values (positive and up to a particular limit):
        // /^\d*$/.test(value) && (value === "" || parseInt(value) <= 500)
        // Floating point values (allowing both . and , as decimal separator):
        // /^-?\d*[.,]?\d*$/.test(value)
        // Currency values (i.e. at most two decimal places):
        // /^-?\d*[.,]?\d{0,2}$/.test(value)
        // Hexadecimal values:
        // /^[0-9a-f]*$/i.test(value)
    }

    function msgAlert(msg){
		$.post("controllers/msgAlert.php", { msg: msg })
			.done(function( data ) {
				$("#msg_detail").html(data);
				$('#modal_msg').modal('show');
		});
	}

    function loadingScreen() { 
        document.getElementById("loadScreen").style.display = "block";
        document.getElementById("wrapper").style.display = "none";
    }

    function loadedScreen() {
        document.getElementById("loadScreen").style.display = "none";
        document.getElementById("wrapper").style.display = "block"; 
    }
</script>